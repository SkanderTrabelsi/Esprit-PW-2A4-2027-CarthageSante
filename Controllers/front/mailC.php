<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../Views/assets/lib/Exception.php';
require_once __DIR__ . '/../../Views/assets/lib/PHPMailer.php';
require_once __DIR__ . '/../../Views/assets/lib/SMTP.php';

class mailC{
    public function envoyer($destinataire, $sujet, $contenu){
        //nekraw les infos mta SMTP mel .env
        $env = @parse_ini_file(__DIR__ . '/../../.env');

        $mail = new PHPMailer(true);
        try{
            //parametre du serveur SMTP
            $mail->isSMTP();
            $mail->Host = $env['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $env['MAIL_USER'];
            $mail->Password = $env['MAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int) $env['MAIL_PORT'];
            $mail->CharSet = 'UTF-8';

            //expediteur et destinataire
            $mail->setFrom($env['MAIL_USER'], 'Carthage Santé');
            $mail->addAddress($destinataire);

            //contenu du message
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $contenu;

            $mail->send();
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}

?>