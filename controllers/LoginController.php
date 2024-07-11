<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public  static function Login(Router $router){
        $alertas=[];
        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $auth = new Usuario($_POST);

           $alertas = $auth -> validarLogin();
            if(empty($alertas)){
                //comprobar que el user existe
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //verificar password
                  if($usuario->comprobarPasswordAndVerificado($auth->password)){
                    if(!isset($_SESSION)){
                        //autenticar usuario
                        session_start();
                    };

                    $_SESSION['id']=$usuario->id;
                    $_SESSION['nombre']=$usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email']=$usuario->email;
                    $_SESSION['login']=true;

                    //redireccionar
                    if($usuario->admin == '1'){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');
                    }else{
                        header('Location: /cita');
                    }
                  }
                }else{
                    Usuario::setAlerta('error', 'usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public  static function logout(){
        session_start();

        $_SESSION = [];
        header('Location:/');
    }
    public  static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
           $alertas = $auth->validarEmail();

           if(empty($alertas)){
            $usuario = Usuario::where('email', $auth->email);

            if($usuario && $usuario->confirmado == '1'){
                //generar token
                $usuario->crearToken();
                $usuario->guardar();

                //enviar token al email
                $email= new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarInstrucciones();

                //alertas
                Usuario::setAlerta('exito', 'Revisa tu email');
            }else{
                Usuario::setAlerta('error', 'el usuario no existe o no está confirmado');
            }
           }
        }
        $alertas = Usuario::getAlertas();

       $router->render('auth/olvide-password', [
        'alertas' => $alertas
       ]);
    }
    public  static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //buscar user por token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta( 'error', 'Token No Valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer y guardar nuevo password
            $password = new Usuario($_POST);
           $alertas = $password->validarPassword();

           if(empty($alertas)){
            $usuario->password = null;
            $usuario->password = $password->password;
            $usuario->hashPassword();
            $usuario->token = null;

            $resultado =$usuario->guardar();
            if($resultado){
                header('Location:/');
            }
           }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public  static function crear(Router $router){
        $usuario = new Usuario;
        //alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $usuario-> sincronizar($_POST);
           $alertas = $usuario->validarNuevaCuenta();

           //revisar que la alerta este vacia
            if (empty($alertas)){
                //verificar que el usuario no este registrado
               $resultado = $usuario -> existeUsuario();

               if($resultado->num_rows){
                $alertas = Usuario::getAlertas();
               } else {
                //regustrar nuevo usuario
                $usuario->hashPassword();

                //generar token unico
                $usuario->crearToken();

                //enviar el email
                $email = new Email($usuario->email,$usuario->nombre, $usuario->token);

                $email -> enviarConfirmacion();
                //crear usuario
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /mensaje');
                }
               }
            }
        }
      $router->render('auth/crear-cuenta', [
        'usuario' => $usuario,
        'alertas'=> $alertas
      ]);
    }
    public  static function mensaje(Router $router){
        $router ->render('auth/mensaje');
    }
    public  static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);

       $usuario = Usuario::where('token', $token);

       if(empty($usuario)){
        //mostrar error
        Usuario::setAlerta('error', 'Token No Válido');
       }else{
        //modificar a usuario confirmado
        $usuario->confirmado = '1';
        $usuario->token = null;
        $usuario->guardar();
        Usuario::setAlerta('exito', 'cuenta confirmada correctamente');
       }
       $alertas = Usuario::getAlertas();
        $router ->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
