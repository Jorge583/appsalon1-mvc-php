<?php
namespace Controllers;

use Model\AdminCita;
use MVC\Router;


class AdminController {
    public static function index(Router $router ) {
        session_start();
        
        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);
        if ( !checkdate( $fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
        }
        // consulta a la base de dato
        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ',  usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio ";
        $consulta .= " FROM citas ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioid=usuarios.id ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " on citasservicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasservicios.servicioId ";
        $consulta .= " WHERE fecha = '${fecha}' ";
    
        $citas = AdminCita::SQL($consulta);
        
        //debuguear($citas);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha  
        ]);
    }
}
        
