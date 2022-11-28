<?php

namespace API\Controllers;

use Exception;
use API\Models\{ UsersModel, MessagesModel, ResetPasswordsModel };
use API\Core\JWT;
use API\Core\PhpMailer\Mailer;
use DateTime;

class ApiController extends Controller{

    public function test(){
        echo json_encode([
            'message' => 'OK : domaine accessible via https'
        ]);
        http_response_code(200);
    }

    public function login(){
        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json");
        header("Access-Control-Allow-Methods: POST");

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        $JWT = $this->isAuthenticated();

        if($JWT){
            echo json_encode([
                'exp' => $JWT->getPayload()->exp,
                'xsrfToken' => $JWT->getXsrfToken()
            ]);
            throw new Exception('', 200);
            die;
        };

        $post = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('', 405);
            exit;
        }

        if(isset($post->mail) && isset($post->password)){
            if(!empty($post->mail) && !empty($post->password)){

                $usersModel = new UsersModel();
                $user = $usersModel->getOneBy('mail', htmlspecialchars($post->mail));

                if($user){

                    $usersModel = new UsersModel();
                    $user = $usersModel->hydrate($user);

                    if(!password_verify(htmlspecialchars($post->password), $user->getPassword())){
                        throw new Exception('Identifiants invalides', 401);
                        exit;
                    }

                    $token = new JWT();
                    $token->generate($user);
                    
                    // setcookie('access_token', $token->getToken(), $token->getExp(), '/', null, true, true);
                    setcookie('access_token', $token->getToken(), [
                        'expires' => $token->getExp(),
                        'path' => '/',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'None'
                    ]);

                    echo json_encode([
                        'xsrfToken' => $token->getXsrfToken()
                    ]);
                    throw new Exception('', 200);
                    die;
                }else{
                    throw new Exception('Identifiants invalides', 401);
                }

            }else{
                throw new Exception('Champs manquant(s).', 400);
            }
        }else{
            throw new Exception('Champs manquant(s).', 400);
        }
    }

    public function register(){

        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');

        $post = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('', 405);
            exit;
        }

        if(isset($post->mail) && isset($post->password) && isset($post->pseudo)){
            if(!empty($post->mail) && !empty($post->password) && !empty($post->pseudo)){

                if(!filter_var($post->mail, FILTER_VALIDATE_EMAIL)){
                    throw new Exception('Format adresse mail non valide', 406);
                    exit;
                }

                $usersModel = new UsersModel();
                if(!$usersModel->getOneBy('mail', $post->mail) && !$usersModel->getOneBy('pseudo', $post->pseudo)){
                    $usersModel = new UsersModel();
                    $user = $usersModel->hydrate([
                        'mail' => filter_var($post->mail, FILTER_VALIDATE_EMAIL),
                        'password' => password_hash($post->password, PASSWORD_DEFAULT),
                        'pseudo' => $post->pseudo,
                    ]);

                    $user->create();

                    $user = $usersModel->getOneBy('mail', $user->getMail());
                    $user = $usersModel->hydrate($user);

                    echo json_encode([
                        'id' => $user->getId(),
                        'mail' => $user->getMail(),
                        'pseudo' => $user->getPseudo(),
                        'image' => $user->getImage(),
                        'created_at' => $user->getCreated_at()
                    ]);
                    throw new Exception('', 200);
                    die;
                }else{
                    throw new Exception('Adresse mail ou pseudo deja utilisee', 406);
                }

            }else{
                throw new Exception('', 400);
            }
        }else{
            throw new Exception('', 400);
        }
    }

    public function logout(){

        header("Access-Control-Allow-Headers: X-XSRF-TOKEN, *");
        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET');

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('', 405);
            exit;
        }
        
        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('no token', 401);
            die;
        }

        setcookie('access_token', '', time()-86400, '/');
        
        throw new Exception('', 200);
        die;
    }

    public function resetPassword(string $token = null){

        header("Access-Control-Allow-Origin: https://localhost:3000");
        header('Content-Type: application/json');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: POST, PUT');
        $post = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($post->mail)){

                if(!empty($post->mail)){
    
                    if(!filter_var($post->mail, FILTER_VALIDATE_EMAIL)){
                        throw new Exception('Format adresse mail non valide', 406);
                        exit;
                    }
    
                    $usersModel = new UsersModel();
                    $user = $usersModel->getOneBy('mail', $post->mail);
    
                    if(!$user){
                        throw new Exception('Aucun utilisateur lié à ce mail', 400);
                        exit;
                    }
    
                    $user = $usersModel->hydrate($user);
    
                    $resetPasswordsModel = new ResetPasswordsModel();
    
                    do{
                        $token = bin2hex(random_bytes(35));
                    }while($resetPasswordsModel->getOneBy('token', $token));
                    
                    $resetPassword = $resetPasswordsModel->hydrate([
                        'token' => $token,
                        'user' => $user->getId()
                    ]);
    
                    $resetPassword->create();
    
    
                    $mailer = new Mailer();
                    $mailer->resetPassword($user->getMail(), "http://localhost:3000/reset/$token");
    
                    throw new Exception('Un mail vient de vous être envoyé.', 200);
                    die;
                }
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            if($token && isset($post->password)){
                if(!empty($post->password)){
                    $resetPasswordsModel = new ResetPasswordsModel();
                    $resetPassword = $resetPasswordsModel->getOneBy('token', $token);
    
                    if(!$resetPassword){
                        throw new Exception('', 400);
                        exit;
                    }
    
                    $resetPassword = $resetPasswordsModel->hydrate($resetPassword);
    
                    if($resetPassword->getIs_used()){
                        throw new Exception('Expiré', 400);
                        exit;
                    }
    
                    $now = new DateTime();
                    $config = parse_ini_file(ROOT . '/config.ini');
                    $created_at = new DateTime($resetPassword->getCreated_at());
                    if ($now->getTimestamp() > $created_at->getTimestamp() + (int)$config['EXP_TIME']){
                        throw new Exception('Ex', 410);
                        exit;
                    }
    
                    $usersModel = new UsersModel();
                    $user = $usersModel->getOneBy('id', $resetPassword->getUser());
                    $user = $usersModel->hydrate($user);
                    $user->setPassword(password_hash($post->password, PASSWORD_DEFAULT));
                    $user->update();
    
                    $resetPassword->setIs_used(true);
                    $resetPassword->update();
    
                    throw new Exception('', 200);
                    die;
                }
            }
        }

    }

    // ----------------------------------------------------------

    public function postMessage(int $receiver){

        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Headers: X-XSRF-TOKEN, *");
        header("Access-Control-Allow-Credentials: true");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            throw new Exception('', 405);
            exit;
        }

        $post = json_decode(file_get_contents('php://input'));

        if(isset($post->content) || isset($_FILES['image'])){
            if(!empty($post->content) || !empty($_FILES['image'])){
                
                $usersModel = new UsersModel();
                $sender = $usersModel->getOneBy('id', $JWT->getUser()->getId());
                if(!$sender){
                    throw new Exception('', 400);
                    exit;
                }
                $sender = $usersModel->hydrate($sender);

                $usersModel = new UsersModel();
                $receiver = $usersModel->getOneBy('id', $receiver);
                if(!$receiver){
                    throw new Exception('', 400);
                    exit;
                }
                $receiver = $usersModel->hydrate($receiver);

                if(isset($_FILES['image'])){
                    $content = $this->addImage($sender->getPseudo(), $_FILES['image'], 'images/conversations/');
                    if(isset($content['errors'])){
                        throw new Exception(json_encode($content['errors']), 415);
                        exit;
                    }
                    $type = 'image';
                }else{
                    $content = $post->content;
                    $type = 'text';
                }

                $messagesModel = new MessagesModel();
                $message = $messagesModel->hydrate([
                    'sender' => $sender->getId(),
                    'receiver' => $receiver->getId(),
                    'content' => $content,
                    'type' => $type
                ]);
                $message->create();

                throw new Exception('', 200);
                die;
            }
        }else{
            throw new Exception('', 400);
            die;
        }
    }

    public function deleteMessage(){
        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: DELETE');
        $post = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
            throw new Exception('', 405);
            exit;
        }

        if(isset($post->id)){
            if(!empty($post->id)){
                $messagesModel = new MessagesModel();
                $message = $messagesModel->getOneBy('id', $post->id);

                if(!$message){
                    throw new Exception('', 400);
                    exit;
                }
                $message = $messagesModel->hydrate($message);

                if($message->getSender() !== $JWT->getUser()->getId()){
                    throw new Exception('', 403);
                    exit;
                }

                if(file_exists($message->getContent())){
                    if(strpos($message->getContent(), $JWT->getUser()->getPseudo())){
                        $this->deleteImage($message->getContent());
                    }
                }

                $message->delete();
                throw new Exception('', 200);
                die;
            }
        }

    }

    public function getConversations(){
        header("Access-Control-Allow-Headers: X-XSRF-TOKEN, *");
        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET');
        header("Content-Type: application/json");

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('', 405);
            exit;
        }

        $usersModel = new UsersModel();
        $user = $usersModel->getOneBy('id', $JWT->getUser()->getId());
        if(!$user){
            throw new Exception('', 400);
            exit;
        }
        $user = $usersModel->hydrate($user);

        $messagesModel = new MessagesModel();
        $conversations = $messagesModel->getConversations($user);
        if(!$conversations){
            throw new Exception('Aucune conversation', 404);
            exit;
        }

        $data = [];
        foreach($conversations as $userId => $conversation){
            $usersModel = new UsersModel();
            $contact = $usersModel->getOneBy('id', $userId);
            $contact = $usersModel->hydrate($contact);

            $messagesModel = new MessagesModel();
            $message = $messagesModel->hydrate($conversation[array_key_last($conversation)]);

            if($message->getSender() === $user->getId()){
                $sent = true;
            }else{
                $sent = false;
            }

            $data[$contact->getPseudo()] = [
                'id' => $message->getId(),
                'pseudo_contact' => $contact->getPseudo(),
                'img_contact' => $contact->getImage(),
                'id_contact' => $contact->getId(),
                'sent' => $sent,
                'content' => $message->getContent(),
                'type' => $message->getType(),
                'is_read' => $message->getIs_read(),
                'created_at' => $message->getCreated_at()
            ];
        }

        echo json_encode(array_reverse($data));
        throw new Exception('', 200);
        die;
    }

    public function getMessages(int $idContact){
        header("Access-Control-Allow-Headers: X-XSRF-TOKEN, *");
        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET');
        header("Content-Type: application/json");

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        if($_SERVER['REQUEST_METHOD'] !== 'GET'){
            throw new Exception('', 405);
            exit;
        }

        $usersModel = new UsersModel();
        $user = $usersModel->getOneBy('id', $JWT->getUser()->getId());
        if(!$user){
            throw new Exception('', 400);
            exit;
        }
        $user = $usersModel->hydrate($user);

        $usersModel = new UsersModel();
        $contact = $usersModel->getOneBy('id', $idContact);
        if(!$contact){
            throw new Exception('', 400);
            exit;
        }
        $contact = $usersModel->hydrate($contact);

        $messagesModel = new MessagesModel();
        $messages = $messagesModel->getMessages($user, $contact);
        if(!$messages){
            throw new Exception('Aucun message', 404);
            exit;
        }

        $data = [];
        $i = 0;
        foreach($messages as $message){

            $messagesModel = new MessagesModel();
            $message = $messagesModel->hydrate($message);

            if($message->getSender() === $user->getId()){
                $sent = true;
            }else{
                $sent = false;
            }

            $data[$i] = [
                'id' => $message->getId(),
                'img_contact' => $contact->getImage(),
                'sent' => $sent,
                'content' => $message->getContent(),
                'type' => $message->getType(),
                'is_read' => $message->getIs_read(),
                'created_at' => $message->getCreated_at()
            ];
            $i++;
        }

        echo json_encode($data);
        throw new Exception('', 200);
        die;
    }

    public function readMessage(){

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: PUT');
        $post = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] !== 'PUT'){
            throw new Exception('', 405);
            exit;
        }

        if(isset($post->id)){
            if(!empty($post->id)){
                $messagesModel = new MessagesModel;
                $message = $messagesModel->getOneBy('id', $post->id);

                if(!$message){
                    throw new Exception('', 400);
                    exit;
                }

                $message = $messagesModel->hydrate($message);
                if($message->getReceiver() != $JWT->getUser()->getId()){
                    throw new Exception('', 403);
                    exit;
                }

                $message->setIs_read(true);
                $message->update();

                throw new Exception('', 200);
                die;
            }
        }
    }

    // ----------------------------------------------------------

    public function searchUsers(string $pseudo){

        header("Access-Control-Allow-Headers: X-XSRF-TOKEN, *");
        header("Access-Control-Allow-Origin: https://localhost:3000");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET');
        header("Content-Type: application/json");

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            throw new Exception('', 200);
            exit;
        }

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        $usersModel = new UsersModel();
        $users = $usersModel->search(htmlspecialchars($pseudo));

        if(!$users){
            throw new Exception('', 404);
            exit;
        }

        $data = [];
        $i = 0;
        foreach($users as $user){
            $usersModel = new UsersModel();
            $user = $usersModel->hydrate($user);

            $data[$user->getId()] = [
                'id' => $user->getId(),
                'pseudo' => $user->getPseudo(),
                'img' => $user->getImage()
            ];
            $i++;
        }

        echo json_encode($data);
        throw new Exception('', 200);
        die;
    }

    public function setProfilePicture(){

        $JWT = $this->isAuthenticated();

        if(!$JWT){
            throw new Exception('', 401);
            exit;
        };

        header('Access-Control-Allow-Methods: POST');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_FILES['image'])){
                if(!empty($_FILES['image'])){
                    $usersModel = new UsersModel();
                    $user = $usersModel->getOneBy('id', $JWT->getUser()->getId());
                    $user = $usersModel->hydrate($user);
    
                    $is_add = $this->addImage($user->getPseudo(), $_FILES['image'], 'images/profile/');
    
                    if(isset($is_add['errors'])){
                        throw new Exception(json_encode($is_add['errors']), 415);
                        exit;
                    }

                    if($user->getImage() !== "images/profile/default_pp.svg"){
                        $this->deleteImage($user->getImage());
                    }

                    $user->setImage($is_add);
                    $user->update();
                    
                    throw new Exception('', 200);
                    die();
                }else{
                    throw new Exception('', 400);
                    exit;
                }
            }else{
                throw new Exception('', 400);
                exit;
            }
        }else{
            throw new Exception('', 405);
            exit;
        }
    }

}