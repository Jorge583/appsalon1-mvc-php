<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion() {
        // crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
       
        //$mail->addAddress($this->email);  
        $mail->setFrom('cuentas@appsalon.com', );
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'Confirmar tu cuenta';

       //set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
       
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado tu cuenta en app
        Salon, solo debes confirmar presionando en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token="
        . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puede ignorar este mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //enviar email
        $mail->send();
    }
    public function enviarInstrucciones() {
          // crear el objeto de email
          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = $_ENV['EMAIL_HOST'];
          $mail->SMTPAuth = true;
          $mail->Port = $_ENV['EMAIL_PORT'];
          $mail->Username = $_ENV['EMAIL_USER'];
          $mail->Password = $_ENV['EMAIL_PASS'];

          $mail->setFrom('cuentas@appsalon.com', );
          $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
          $mail->Subject = 'Reestablecer tu password';
         //set HTML
          $mail->isHTML(TRUE);
          $mail->CharSet = 'UTF-8';
         
          $contenido = "<html>";
          $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu password , sigue el sigueinte enlace para hacerlo</p>";
          $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer password</a> </p>";
          $contenido .= "<p>Si tu no solicitaste esta cuenta, puede ignorar este mensaje</p>";
          $contenido .= "</html>";
          $mail->Body = $contenido;
          //enviar email
          $mail->send();
      }
    

}
