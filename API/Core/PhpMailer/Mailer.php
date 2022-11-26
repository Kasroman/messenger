<?php

namespace API\Core\PhpMailer;

use API\Core\PhpMailer\{ PHPMailer, SMTP };
use Exception;

class Mailer {

    public function __construct(){
        $config = parse_ini_file(ROOT . '/config.ini');

        $this->mail = new PHPmailer(true);

        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host       = $config['MAIL_HOST'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $config['MAIL_USERNAME'];
        $this->mail->Password   = $config['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port       = $config['MAIL_PORT'];

        $this->mail->setFrom($config['MAIL_EMAIL'], $config['MAIL_NAME']);
        $this->mail->CharSet = 'UTF-8';
    }

    public function resetPassword(string $to, string $link){

        $this->mail->addAddress($to);

        $this->mail->Subject = 'Changez votre mot de passe';
        $this->mail->Body = "Veuillez vous rendre à l'adresse suivante pour changer votre mot de passe : $link";

        if(!$this->mail->send()){
            throw new Exception("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
        }
    }

    public function confirmRegister(string $to, string $link){

        $this->mail->addAddress($to);

        $this->mail->Subject = 'Confirmez votre inscription';
        $this->mail->Body = "Veuillez vous rendre à l'adresse suivante pour confirmer votre inscription : $link";

        if(!$this->mail->send()){
            throw new Exception("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
        }
    }
}

