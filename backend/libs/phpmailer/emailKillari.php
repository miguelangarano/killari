<?php

use PHPMailer\PHPMailer\PHPMailer;
//use phpmailer\phpmailer\src\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class Email{

    public function sendEmail($proxy, $authUrl){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP

            $mail->Host = 'mail.killari.com.ec';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'centroderelajacion@killari.com.ec';                 // SMTP username
            $mail->Password = 'Killari6!02';                           // SMTP password
            //$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 26;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('centroderelajacion@killari.com.ec', 'Killari SPA');
            //$mail->addAddress('centroderelajacion@killari.com.ec', 'Sistema Killari');     // Add a recipient
            $mail->addAddress('killari.spa@gmail.com', 'Sistema Killari');     // Add a recipient


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Token para sincronizacion de calendario';
            //echo ('proxy: '.$proxy->getProxy());
                $homepage = file_get_contents($proxy.'libs/phpmailer/formato2.html');
                $variables=array();
                $variables['authurl']=$authUrl;
                foreach($variables as $key=>$value){
                    $homepage=str_replace('{{ '.$key.' }}',$value,$homepage);
                }
            
            $mail->Body    = $homepage;
            //$mail->Body    ='hola';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->Debugoutput = function() {};
            $mail->send();
            //echo 'Message has been sent';
            $ok='ok';
            echo 'correoooooo '.$ok;
        } catch (Exception $e) {
            $message= 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            echo $message;

        }
    }
}
?>



