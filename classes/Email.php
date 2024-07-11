<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion(){
        //crear objeto de email
     $mail = new PHPMailer();
     $mail->isSMTP();
     $mail->Host = $_ENV['EMAIL_HOST'];
     $mail->SMTPAuth = true;
     $mail->Port = $_ENV['EMAIL_PORT'];
     $mail->Username = $_ENV['EMAIL_USER'];
     $mail->Password = $_ENV['EMAIL_PASS'];

     $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
     $mail->Subject = 'Confirma Tu Cuenta';

     //set html
    $mail->isHTML(TRUE);
    $mail->CharSet ='UTF-8';

     $mail->Body =" <html>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
        h2 {
            font-size: 25px;
            font-weight: 500;
            line-height: 25px;
        }
    
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
    
        p {
            line-height: 18px;
        }
    
        a {
            position: relative;
            z-index: 0;
            display: inline-block;
            margin: 20px 0;
        }
    
        a button {
            padding: 0.7em 2em;
            font-size: 16px !important;
            font-weight: 500;
            background: #000000;
            color: #ffffff;
            border: none;
            text-transform: uppercase;
            cursor: pointer;
        }
        p span {
            font-size: 12px;
        }
        div p{
            border-bottom: 1px solid #000000;
            border-top: none;
            margin-top: 40px;
        }
    </style>
    <body>
        <h1>App Salon</h1>
        <h2>¡Hola " . $this->nombre . ", Gracias Por Registrarte!</h2>
        <p>Por Favor Confirma Tu Correo Electrónico Para Que Puedas Comenzar a Disfrutar de Nuestros Servicios</p>
        <a href='". $_ENV['APP_URL']  ."/confirmar-cuenta?token=". $this->token . "'><button>Verificar</button></a>
        <p>Si tú no te registraste en App Salon, por favor ignora este correo electrónico.</p>
        <div><p></p></div>
        <p><span>Este correo electrónico fue enviado para crear una cuenta en nuestro sitio web APP SALON. Si tu no te registraste, por favor ignora este correo.</span></p>
    </body>
    </html>";

     //enviar mail
     $mail -> send();

    }
    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
   
        $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';
   
        //set html
       $mail->isHTML(TRUE);
       $mail->CharSet ='UTF-8';
   
        $mail->Body =" <html>
           <style>
           @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
           h2 {
               font-size: 25px;
               font-weight: 500;
               line-height: 25px;
           }
       
           body {
               font-family: 'Poppins', sans-serif;
               background-color: #ffffff;
               max-width: 400px;
               margin: 0 auto;
               padding: 20px;
           }
       
           p {
               line-height: 18px;
           }
       
           a {
               position: relative;
               z-index: 0;
               display: inline-block;
               margin: 20px 0;
           }
       
           a button {
               padding: 0.7em 2em;
               font-size: 16px !important;
               font-weight: 500;
               background: #000000;
               color: #ffffff;
               border: none;
               text-transform: uppercase;
               cursor: pointer;
           }
           p span {
               font-size: 12px;
           }
           div p{
               border-bottom: 1px solid #000000;
               border-top: none;
               margin-top: 40px;
           }
       </style>
       <body>
           <h1>App Salon</h1>
           <h2>¡Hola " . $this->nombre . ", Has Solicitado Reestablecer Tu Password!</h2>
           <p>Por Sigue El Siguiente Enlace Para Hacerlo</p>
            <a href='". $_ENV['APP_URL'] ."/recuperar?token=". $this->token . "'><button>Reestablecer Password</button></a>
           <p>Si tú no te registraste en App Salon, por favor ignora este correo electrónico.</p>
           <div><p></p></div>
           <p><span>Este correo electrónico fue enviado para reestablecer el password de una cuenta en nuestro sitio web APP SALON. Si tu no lo solicitaste, por favor ignora este correo.</span></p>
       </body>
       </html>";
   
        //enviar mail
        $mail -> send();
    }
}