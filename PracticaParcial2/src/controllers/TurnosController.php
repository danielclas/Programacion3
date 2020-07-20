<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\RtaJsend;
use App\Utils\ValidarPost;
use Config\Database;
use \Firebase\JWT\JWT;
use App\Models\Turno;
use App\Models\Mascota;
use App\Models\Usuario;

class TurnosController {

    public function registrarTurno(Request $request, Response $response, $args){
        $turno = new Turno();

        $datosARegistrar = $request->getParsedBody();

        // El turno no esta disponible hasta que se verifique que hay mas de 30 minutos en turnos para ese veterinario
        $disponibilidadTurno = false;
        if(empty($datosARegistrar)){
            $rta = RtaJsend::JsendResponse('Registro Turno ERROR','No se recibieron datos para registrar');
        } else {
            $turno->mascota_id = $datosARegistrar['id_mascota'] ?? '';
            $turno->veterinario_id = $datosARegistrar['id_veterinario'] ?? '';
            $turno->fecha = $datosARegistrar['fecha'] ?? '';
            $turno->hora = $datosARegistrar['hora'] ?? '';
    
            if($turno->mascota_id != '' && $turno->veterinario_id != '' && $turno->fecha != '' && $turno->hora != ''){
                // Horas de cierre y apertura del local
                $horaApertura = new \DateTime('09:00');
                $horaCierre = new \DateTime('17:00');
                $horaApertura = $horaApertura->format('H:i');
                $horaCierre = $horaCierre->format('H:i');

                $horarioIngresadoPorCliente = new \DateTime($turno->hora);
                $turno->hora = $horarioIngresadoPorCliente->format('H:i');
                $horasIngresadoPorCliente = $horarioIngresadoPorCliente->format("H");
                $minutosIngresadoPorCliente = $horarioIngresadoPorCliente->format("i");


                // Me fijo si esta cerrado el local a esa hora que pide el cliente
                if(($horasIngresadoPorCliente < 9) || ($horasIngresadoPorCliente >= 17)){
                    $rta = RtaJsend::JsendResponse('fail', 'Local Cerrado, horario de '. $horaApertura.' a '.$horaCierre.' hrs.');
                } else {
                    // Me traigo todos los turnos que coincidan con la fecha ingresada por el cliente, idem con el id veterinario
                    $turnosRegistradosSQL = Turno::all()->where('fecha',$turno->fecha)->where('veterinario_id',$turno->veterinario_id);
                    // echo json_encode($turnosRegistradosSQL);
                    $aux = json_decode($turnosRegistradosSQL,true);
                    // var_dump(empty($aux));
                    // Si no hay turnos registrados ese dia, doy la disponibilidad de turno ok
                    if(empty($aux)){
                        // echo 'entro al if';
                        $disponibilidadTurno = true;
                    } else {
                        // Recorro los turnos del SQL
                        // var_dump(json_decode($turnosRegistradosSQL,true));
                        foreach ($turnosRegistradosSQL as $indice => $turnoLeidoSQL) {
                            // Transformo la fecha del turno leido del SQL a objeto DateTime
                            $horaTurnoSQL = new \DateTime($turnoLeidoSQL['hora']);
                            // Aca uso % adelante de la H o i porque es tipo DateInterval, si es tipo DateTime lo uso sin el %
                            $horasDeDiferencia = $horaTurnoSQL->diff($horarioIngresadoPorCliente)->format("%H");
                            $minutosDeDiferencia = $horaTurnoSQL->diff($horarioIngresadoPorCliente)->format("%I");
                            // // Veo si hay mas de 1 de diferencia
                            // echo 'hr dif: '.$horasDeDiferencia.'<br>';
                            // echo 'min dif: '.$minutosDeDiferencia.'<br>';
                            if($horasDeDiferencia > 0 || $horasDeDiferencia < 0){
                                // Hay mas de 1 hr de diferencia, turno disponible
                                $disponibilidadTurno = true;
                            } else {
                                // Si no hay diferencia de horas tengo que ver los minutos
                                if($minutosDeDiferencia >= 30){
                                    // Si hay mas de 30 minutos el turno se vuelve disponible
                                    $disponibilidadTurno = true;
                                } else {
                                    // No hay 30 minutos de diferencia
                                    $rta = RtaJsend::JsendResponse('fail', 'Diferencia menor a 30 minutos entre turnos');
                                    $disponibilidadTurno = false;
                                }    
                            }
                        }
                    }
                } 
            }
            
            if($disponibilidadTurno == true){
                $rta = RtaJsend::JsendResponse('success',($turno->save()) ? 'Registro turno ok' : 'Fallo');
            }
        }
        $response->getBody()->write($rta);
        return $response;
    }

    public function mostrarTurnos(Request $request, Response $response, $args){
        $fechaActual = date('d/m/Y', time());
        $id_usuario = $args ?? '';

        if($id_usuario != ''){ 
            // Obtengo tipo de usuario (cliente o veterinario)
            $tipoClienteSQL = Usuario::select('tipo.descripcion')
            ->join('tipo','tipo.id','usuarios.tipo_id')
            ->where('usuarios.id',$id_usuario)
            ->get();
            $tipo = $tipoClienteSQL[0]['descripcion'];
            if($tipo == 'cliente'){
                // Selecciono las columnas q quiero mostrar al usuario
                $turnosRegistradosSQL = Mascota::select('usuarios.email','mascotas.nombre','fecha','hora')
                    // Uno una tabla con otra
                    ->join('turnos','turnos.mascota_id','mascotas.id')
                    ->join('usuarios','usuarios.id','mascotas.cliente_id')
                    // filtro por el id q ingresa en la url el usuario
                    ->where('cliente_id', $id_usuario)
                    // Agrupa para q no hayan duplicados
                    ->groupBy('fecha','hora','mascotas.nombre','usuarios.email')
                    ->get();
                // ->join('usuarios.id','=',12);
                $turnosRegistradosSQL = json_decode(str_replace('nombre','nombre_mascota',$turnosRegistradosSQL));
                // $turnosRegistradosSQL = json_decode(str_replace('email','email_usuario',$turnosRegistradosSQL));
                $rta = RtaJsend::JsendResponse('success',array('Turnos'=>$turnosRegistradosSQL));
            } else {
                'ENTRO AL ELSE';
                // Si es veterinario
                $turnosRegistradosSQL = Turno::select('mascota_id','hora','fecha')
                ->where('veterinario_id',$id_usuario)
                ->where('fecha',$fechaActual)
                ->get();
                
                $rta = RtaJsend::JsendResponse('success',array('Turnos del dia'=>(count($turnosRegistradosSQL) == 0) ? 'No hay turnos para hoy': $turnosRegistradosSQL));
            }
            $response->getBody()->write($rta);
            return $response;
        }

    }
}