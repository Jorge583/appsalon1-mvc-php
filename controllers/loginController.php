<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

//require './includes/database.php';


class LoginController {
     public static function login(Router $router) {
     $alertas = [];
           if($_SERVER['REQUEST_METHOD'] === 'POST') {
                 $auth = new Usuario($_POST);
                 $alertas = $auth->validarLogin();

                if (empty($alertas)) {
                     //comprobar que hiciste un usuario
                    $usuario = Usuario::where('email', $auth->email);
                      
                    if($usuario) {          
                       //verificar passror
                         if( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                                   // Autenticar el usuario
      
                                session_start();
                                   
                                $_SESSION['id'] = $usuario->id;
                                $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                                $_SESSION['email'] = $usuario->email;
                                $_SESSION['login'] = true;
                                //redireccionar 
                                if ($usuario->admin === "1") {
                                    $_SESSION['admin'] = $usuario->admin ?? null;
                                    header('Location: /admin');
                                } else {
                                    header('Location: /cita');
                                }
                                
                                //debuguear($_SESSION);
                                    }
                                } else {
                                    Usuario::setAlerta('error', 'Usuario no Encontrado');
                                }
                            }     
                    } 
                                
                    $alertas = Usuario::getAlertas();
        
                    $router->render('auth/login', [
                        'alertas' => $alertas
                        ]);
            }  
        
    
public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router) {
            $alertas = [];
            //$error = false;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new Usuario($_POST);
                $alertas = $auth->validarEmail();
                    if (empty($alertas)) {
                        $usuario = Usuario::where('email', $auth->email);

                            if ($usuario && $usuario->confirmado === "1") {
                               //generar un token 
                               $usuario->crearToken();
                               $usuario->guardar();
                               
                               // enviar email
                               $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                               $email->enviarInstrucciones();
                               Usuario::setAlerta('exito', 'revisa tu email');
                            }else {
                                Usuario::setAlerta('error', 'El Usuario no existe o no esta Confirmado');
                            }   
                    }
            }
            $alertas = Usuario::getAlertas();
        
            $router->render('auth/olvide-password',[
                'alertas' => $alertas
            ]);
        }
    public static function recuperar(Router $router ) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        //buiscar usuario por si token
        $usuario = Usuario::where('token', $token);
            if(empty($usuario)) {
                Usuario::setAlerta('error', 'token no Valido');
                $error = true;
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // LEER EL NUEVO PASSWORD
                $password = new Usuario($_POST);
                $alertas = $password->validarPassword();
                if (empty($alertas)) {
                    $usuario->password = null;

                    $usuario->password = $password->password;
                    $usuario->hashPassword();
                    $usuario->token = null;

                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /');
                    }
                   
                }
        }


        //debuguear($usuario);
            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar-password', [
                'alertas' => $alertas,
                'error' => $error
            ]);
        }
    public static function crear(Router $router) {
        $usuario = new Usuario;
        // alertas vacias 
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
             if (empty($alertas)) {
               //verificar  que el ususario no esta registrado
             $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                     } else {
                         //haschear el password
                        $usuario->hashPassword();
                        // crear un token para verificar si el correo es real 
                        $usuario->crearToken();
                        //enviar email
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarConfirmacion();
                       // crear el usuario
                        $resultado = $usuario->guardar();
                        //debuguear($usuario);
                         if($resultado) {
                            header('Location: /mensaje');
                        }
                    }
                }
            
        }
                    $router->render('auth/crear-cuenta', [
                        'usuario' => $usuario,
                        'alertas' => $alertas
                     ]);
            }
    public static function mensaje(Router $router) {
             $router->render('auth/mensaje');
            }
    public static function confirmar(Router $router) {
         $alertas = [];        
         $token = s($_GET['token']);
         //debuguear($token);
         $usuario = Usuario::where('token', $token);
         //debuguear($usuario);
             if (empty($usuario)) {
               //mostrar mensaje error
              Usuario::setAlerta('error', 'Token No Valido');
                 } else {
                  //modifica a suario confirmado
                   $usuario->confirmado = "1";
                   $usuario->token = null;
                   $usuario->guardar();
                   Usuario::setAlerta('exito', 'cuenta comprobada Correctamente');
             }
         //obtener alertas
             $alertas = Usuario::getAlertas();
         //renderizar la vista
         $router->render('auth/confirmar-cuenta', [
             'alertas' => $alertas
         ]);
    }       
}