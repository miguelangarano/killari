<?php

use PHPMailer\PHPMailer\PHPMailer;
//use phpmailer\phpmailer\src\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


class Email{

    public function sendEmail($proxy, $obj, $kind){
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
            if($kind==1){
                $mail->addAddress($obj->correo, 'Cliente');     // Add a recipient
            }else{
                $mail->addAddress('centroderelajacion@killari.com.ec', 'Killari');     // Add a recipient
            }
            
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reservacion de Killari Spa';
            //echo ('proxy: '.$proxy->getProxy());
            if($kind==1){
                $homepage = file_get_contents($proxy.'libs/phpmailer/formato.html');
                $variables=array();
                $variables['nombre']=$obj->nombre;
                $variables['fecha']=$obj->fecha;
                $variables['hora']=$obj->hora;
                $variables['servicio']=$obj->servicio;
                $variables['id']=$obj->id;
                foreach($variables as $key=>$value){
                    $homepage=str_replace('{{ '.$key.' }}',$value,$homepage);
                }
            }else{
                $homepage = file_get_contents($proxy.'libs/phpmailer/formato1.html');
                $variables=array();
                $variables['nombre']=$obj->nombre;
                $variables['email']=$obj->email;
                $variables['numero']=$obj->numero;
                $variables['fecha']=$obj->fecha;
                $variables['hora']=$obj->hora;
                $variables['id']=$obj->id;
                $variables['servicio']=$obj->servicio;
                foreach($variables as $key=>$value){
                    $homepage=str_replace('{{ '.$key.' }}',$value,$homepage);
                }
            }
            
            $mail->Body    = $homepage;
            //$mail->Body    ='hola';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
?>



