<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuarioController;
use App\Controllers\TurnoController;
use App\Controllers\MascotaController;

return function ($app){
    
    $app->post('/registro', UsuarioController::class . ':signUp');
    $app->post('/login', UsuarioController::class . ':logIn');

    $app->group('/turnos', function(RouteCollectorProxy $group){
        $group->post('/mascota', TurnoController::class . ':setAppointment');
        $group->get('/{id_usuario}', TurnoController::class . ':getAppointments');
    });    

    $app->group('/mascota', function(RouteCollectorProxy $group){
        $group->post('/', MascotaController::class . ':registerMascota');
        $group->get('/{id_mascota}', MascotaController::class . ':getMascotaHistory');
    }); 
};