<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mascota;
use App\Utils\RtaJsend;

class MascotasController {

    public function registrarMascota(Request $request, Response $response, $args){
        $mascota = new Mascota();

        $mascota->nombre = $_POST['nombre'];
        $mascota->edad = $_POST['edad'];
        $mascota->cliente_id = $_POST['id_cliente'];

        $rta = RtaJsend::JsendResponse('Registro Mascota',($mascota->save()) ? 'Ok' : 'Fallo');
        $response->getBody()->write($rta);
        return $response;
    }

    public function verHistorialMascota(Request $request, Response $response, $args){
        $mascota = new Mascota();

        $zone=3600 * -3;
        $fechaActual = gmdate('d/m/Y', time() + $zone);

        $historialTurnosSQL = Mascota::select('mascotas.nombre','mascotas.edad','turnos.fecha')
        ->join('turnos','turnos.mascota_id','mascotas.id')
        ->where('turnos.fecha','<=',$fechaActual)
        ->get();

        $rta = RtaJsend::JsendResponse('success',array('Historial de turnos previos al dia de hoy'=>$historialTurnosSQL));
        $response->getBody()->write($rta);
        return $response;
    }
}