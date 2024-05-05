<?php

declare(strict_types=1);

require 'flight/Flight.php';
require 'config.php';
// require 'flight/autoload.php';


//conexion con la base de datos

$pdo = new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);

Flight::before('start', function(){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Request-With');
});


Flight::route('OPTIONS /*', function(){
    // Establecer los encabezados CORS para permitir las solicitudes desde cualquier origen
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    // Responder con un estado HTTP 200 OK
    http_response_code(200);
    // Terminar la solicitud sin enviar contenido
    exit();
});

    

// http:localhost/asistencia-docente-back/
Flight::route('/', function () {
    echo 'Hola Mundo!';
});

// http://localhost/asistencia-docente-back/usuarios
Flight::route('GET /usuarios', function () {
    try{
        global $pdo;
        $consulta = $pdo -> prepare("SELECT * FROM usuario");
        $consulta -> execute();
        $usuarios = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        Flight::json($usuarios);
    }catch(PDOException $e){
        Flight::halt(500, "Error al obtener usuarios: " . $e->getMessage());
    }
});

// http://localhost/asistencia-docente-back/login
Flight::route('POST /login', function () {
    try{
        global $pdo;
        $correo = Flight::request()->data->correo;
        $contrasenia = Flight::request()->data->contrasenia;

        $consulta = $pdo -> prepare("SELECT * FROM usuario WHERE correo = :correo AND contrasenia = :contrasenia");
        $consulta->bindParam(':correo',$correo);
        $consulta->bindParam(':contrasenia',$contrasenia);
        $consulta -> execute();
        $usuarios = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        Flight::json($usuarios);
    }catch(PDOException $e){
        Flight::halt(500, "Error al obtener usuarios: " . $e->getMessage());
    }
});

Flight::route('GET /hora-servidor', function(){
    $horaServidor = date("H:i:s");
    Flight::json(['hora'=> $horaServidor]);
});


Flight::start();
