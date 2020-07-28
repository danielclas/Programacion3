<?php
// Aca incluyo mis controladores
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuariosController;
use App\Controllers\MascotasController;
use App\Controllers\TurnosController;
use App\Middleware\TokenValidateMiddleware;
use App\Middleware\AllCapsMiddleware;

return function($app){

    $app->post('/registro', UsuariosController::class.':registro');
    $app->post('/login', UsuariosController::class.':login');
    $app->post('/tipo_mascota', MascotasController::class.':cargarTipo')->add(new TokenValidateMiddleware());
    $app->post('/mascota', MascotasController::class.':cargarMascota')->add(new TokenValidateMiddleware());
    $app->group('/turnos', function(RouteCollectorProxy $group){
        $group->get('/{id_usuario}', TurnosController::class.':getTurnos')
        ->add(new AllCapsMiddleware());
        $group->post('[/mascota]', TurnosController::class.':cargarTurno');
        $group->get('/mascota/{id_mascota}', TurnosController::class.'getTurnosMascota')
        ->add(new AllCapsMiddleware());
    })->add(new TokenValidateMiddleware());
};