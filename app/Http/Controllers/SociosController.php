<?php

namespace App\Http\Controllers;

use App\Socios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SociosController extends Controller
{
    const MODEL = 'App\Socios';
    use RestActions;

    public function storeRecord(Request $request){
        $m = self::MODEL;
        $this->validate($request, $m::$rules);

        $datosSocio = array(
            'nombre' => $request->get('nombre'),
            'apellidoPaterno' => $request->get('apellidoPaterno'),
            'apellidoMaterno' => $request->get('apellidoMaterno'),
        );

        $idSocio = $m::create($datosSocio)->id;

        $m = 'App\SociosMembresias';

        $datosSocioMembresia = array(
            'id_socio' => $idSocio,
            'id_membresia' => $request->get('id_membresia'),
            'bActiva' => $request->get('bActiva'),
            'fecha_inicio' => $request->get('fecha_inicio'),
            'fecha_fin' => $request->get('fecha_fin')
        );

        //Format Time
        $aTimeStart = explode('T',$datosSocioMembresia['fecha_inicio']);
        $datosSocioMembresia['fecha_inicio'] = $aTimeStart[0];

        $aTimeEnd = explode('T',$datosSocioMembresia['fecha_fin']);
        $datosSocioMembresia['fecha_fin'] = $aTimeEnd[0];

        return $this->respond('created', $m::create($datosSocioMembresia));
    }
}
