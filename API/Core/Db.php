<?php

namespace API\Core;

use PDO, PDOException;

class Db extends PDO{

    private static $instance;

    private function __construct(){
        $config = parse_ini_file(ROOT . '/config.ini');
        $_dsn ='mysql:dbname=' . $config['DB_NAME'] . ';host=' . $config['DB_HOST'] . ';port=' . $config['DB_PORT']. ';charset=utf8mb4';

        try{
            parent::__construct($_dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);

            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance === null){

            self::$instance = new self();
        }
        return self::$instance;
    }
}