<?php
// No olvidar el namespace

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;
use App\Utils\rtaJsend;

class AllCapsMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $existingContent =(string) $response->getBody();
        // Fecha del dia de hoy
        // Forma de obtener la hora GMT -3 actual
        // $fechaActual = gmdate('d/m/Y H:i:s', time() + $zone);
        $zone=3600 * -3;//ARGENTINA
        $fechaActual = gmdate('d/m/Y', time() + $zone);

        // Entro a la parte data de la respuesta JSEND que yo formatie
        $data = json_decode($existingContent,true)['data'];
        // Inicializo un array
        $auxArray = array();
        // Recorro los turnos, y si la fecha e
        foreach ($data['Turnos'] as $key => $turno) {
            if($turno['fecha'] > (string)$fechaActual){
                for ($i=0; $i < 2; $i++) { 
                    $turno = array_change_key_case($turno, CASE_UPPER);
                    $turno = array_flip($turno);
                }
            }
            array_push($auxArray,$turno);
        }
        
        $response = new Response();
        $rta = RtaJsend::JsendResponse('success',array('Turnos'=>$auxArray));  
        
        $response->getBody()->write($rta);
    
        return $response;
    }
}