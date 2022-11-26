<?php

namespace API\Core;

use Exception;

class Router{

    public function start(){
        
        try{
    
            $params = explode('/', $_GET['p']);

            if(end($params) === ''){
                array_pop($params);
            }

            if(empty($params)){
                throw new Exception('', 400);
                exit;
            }
    
            if($params[0] !== ''){

                $controller = '\\API\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

                if(!class_exists($controller)){
                    throw new Exception('', 400);
                    exit();
                }

                $controller = new $controller();
                $method = (!empty($params[0])) ? array_shift($params) : 'index';

                if(!method_exists($controller, $method)){
                    throw new Exception('', 400);
                    exit;
                }
                
                isset($params[0]) ? call_user_func_array([$controller, $method], $params) : $controller->$method();

            }else{
                http_response_code(400);
            }

        }catch(Exception $e){

            if($e->getMessage()){
                echo json_encode([
                    'message' => $e->getMessage()
                ]);
            }else if($e->getCode() !== 200){
                echo json_encode([
                    'message' => 'erreur'
                ]);
            }

            http_response_code($e->getCode());
            die;
        }
    }
}