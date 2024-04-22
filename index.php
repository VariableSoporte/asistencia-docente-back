<?php

declare(strict_types=1);

require 'flight/Flight.php';
require 'config.php';
// require 'flight/autoload.php';


//conexion con la base de datos

$pdo = new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);

// http:localhost/asistencia-docente-back/
Flight::route('/', function () {
    echo 'Hola Mundo!';
});

// http:localhost/asistencia-docente-back/usuarios
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


Flight::start();
