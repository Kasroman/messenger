<?php

namespace API\Core;

use DateTime;
use API\Models\{ UsersModel };

class JWT
{
    protected UsersModel $user;
    protected int $validity;
    protected string $secret;
    protected string $token;
    protected string $xsrfToken;
    protected string $iat;
    protected string $exp;
    

    public function __construct()
    {
        $config = parse_ini_file(ROOT . '/config.ini');
        $this->validity = (int)$config['EXP_TIME'];
        $this->secret = base64_encode($config['JWT_SECRETKEY']);
    }

    public function generate(UsersModel $user): void
    {   
        $this->user = $user;

        $this->xsrfToken  = bin2hex(random_bytes(35));

        $hashHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ])));

        $now = new DateTime();
        $this->iat = $now->getTimestamp();
        $this->exp = $now->getTimestamp() + $this->validity;

        $hashPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
            'id' => $this->user->getId(),
            'xsrfToken' => $this->xsrfToken,
            'iat' => $this->iat,
            'exp' => $this->exp
        ])));



        $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(hash_hmac('sha256', $hashHeader . '.' . $hashPayload, $this->secret, true)));

        $this->token = $hashHeader . '.' .$hashPayload . '.' . $signature;
    }

    public function setToken(string $token, string $xsrfToken): self|false
    {
        $this->token = htmlspecialchars($token);

        if(!$this->isFormated()){
            return false;
        }

        $payload = $this->getPayload();

        $usersModel = new UsersModel();
        $user = $usersModel->getOneBy('id', $payload->id);
        if(!$user){
            return false;
        }

        $user = $usersModel->hydrate($user);

        $this->user = $user;
        $this->xsrfToken = htmlspecialchars($xsrfToken);
        $this->iat = $payload->iat;
        $this->exp = $payload->exp;

        return $this;
    }

    public function checkToken(): null|bool
    {
        if(empty($this->token)){
            return null;
        }
        if($this->isExpired()){
            return false;
        }

        $hashHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ])));


        $hashPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
            'id' => $this->user->getId(),
            'xsrfToken' => $this->xsrfToken,
            'iat' => $this->iat,
            'exp' => $this->exp
        ])));



        $generatedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(hash_hmac('sha256', $hashHeader . '.' . $hashPayload, $this->secret, true)));

        return $generatedSignature === $this->getSignature();
    }

    public function isFormated(): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $this->token
        ) === 1;
    }

    public function isExpired(): bool
    {
        $now = new DateTime();
        $now = $now->getTimestamp();

        if($now > $this->exp){
            return true;
        }

        return false;
    }

    public function getUser(){
        if(empty($this->user)){
            return null;
        }
        return $this->user;
    }

    public function getHeader(): null|object
    {

        if(empty($this->token)){
            return null;
        }

        return json_decode(base64_decode(explode('.', $this->token)[0]));
    }

    public function getPayload(): null|object
    {

        if(empty($this->token)){
            return null;
        }

        return json_decode(base64_decode(explode('.', $this->token)[1]));
    }

    public function getSignature(): null|string
    {

        if(empty($this->token)){
            return null;
        }

        return explode('.', $this->token)[2];
    }

    public function getToken(): null|string
    {
        if(empty($this->token)){
            return null;
        }

        return $this->token;
    }

    public function getXsrfToken(): null|string
    {
        if(empty($this->xsrfToken)){

            $payload = $this->getPayload();
            if(!$payload){
                return null;
            }
            
            return $payload['xsrfToken'];
        }

        return $this->xsrfToken;
    }


    /**
     * Get the value of exp
     */ 
    public function getExp()
    {
        return $this->exp;
    }
}
