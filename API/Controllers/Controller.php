<?php

namespace API\Controllers;

use Exception;
use API\Core\JWT;
use API\Models\ { UsersModel };

abstract class Controller{

    protected function addImage(string $nameImage, array $files, string $dir): string|array
    {

        $errors = [];
        
        if($files['error'] === 0){

            // On vérifie que l'image soit d'une extension .jpg, .jpeg ou .png
            if(strpos($files['type'], 'png') || strpos($files['type'], 'jpg') || strpos($files['type'], 'jpeg')){      
            }else{
                $errors['errors'][1] = ['Votre fichier doit être du format .jpg, .jpeg ou .png.'];
            }
            
            // On vérifie que la taille de l'image soit inférieure a 1mo
            if($files['size'] > 1000000){
                $errors['errors'][2] = ['Votre fichier image est trop volumineux ! (max 1Mo).'];
            }

            if(!empty($errors)){
                return $errors;
            }

            // On sépare l'extension de son nom de fichier dans un tableau
            $extension = explode('.', $files['name']);
            
            // On l'enregistre en lui donnant son nom
            $rand = rand(1, 999999);
            $path = $dir . str_replace(' ', '_', $nameImage) . '_' . $rand . '.' . end($extension);
            move_uploaded_file($files['tmp_name'], $path);

            return $path;
            
        }else{
            $errors['errors'][0] = ['Erreur lors de l\'envoi.'];
        }

        return $errors;
    }

    protected function deleteImage(string $url_image): bool
    {

        if(!file_exists($url_image)){
            throw new Exception('Image non trouvée', 404);
            exit;
        }

        return unlink($url_image);
    }

    protected function isAuthenticated(): JWT|false
    {

        if(!isset($_SERVER['HTTP_X_XSRF_TOKEN'])){
            return false;
        }

        if(!isset($_COOKIE['access_token'])){
            return false;
        }

        $xsrfToken = htmlspecialchars($_SERVER['HTTP_X_XSRF_TOKEN']);
        $jwtToken = htmlspecialchars($_COOKIE['access_token']);

        $JWT = new JWT();
        if(!$JWT->setToken($jwtToken, $xsrfToken)){
            return false;
        }

        if(!$JWT->checkToken()){
            return false;
        }

        return $JWT;
    }
}