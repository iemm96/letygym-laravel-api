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

    public function getRecords() {
        $m = self::MODEL;
        if(!$result = $m::where('bVisitante','=','0')->get()) {
            return $this->respond('not_found');
        }

        return $this->respond('done',$result);
    }
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

    public function updateRecord(Request $request, $id)
    {
        $m = self::MODEL;
        $model = $m::find($id);

        if(is_null($model)){
            return $this->respond('not_found');
        }

        $datosSocio = array(
            'nombre' => $request->get('nombre') ? $request->get('nombre') : $model->nombre,
            'apellidoPaterno' => $request->get('apellidoPaterno') ? $request->get('apellidoPaterno') : $model->apellidoPaterno,
            'apellidoMaterno' => $request->get('apellidoMaterno') ? $request->get('apellidoMaterno') : $model->apellidoMaterno,
        );

        $model->update($datosSocio);

        $m = 'App\SociosMembresias';
        $membresia = $m::where('id_socio',$id)->first();

        $datosSocioMembresia = array(
            'id' => $membresia->id,
            'id_socio' => $membresia->id_socio,
            'id_membresia' => $request->get('id_membresia') ? $request->get('id_membresia') : $membresia->id_membresia,
            'bActiva' => $request->get('bActiva') ? $request->get('bActiva') : $membresia->bActiva,
            'fecha_inicio' => $request->get('fecha_inicio') ? $request->get('fecha_inicio') : $membresia->fecha_inicio,
            'fecha_fin' => $request->get('fecha_fin') ? $request->get('fecha_fin') : $membresia->fecha_fin
        );

        //Format Time
        $aTimeStart = explode('T',$datosSocioMembresia['fecha_inicio']);

        if($aTimeStart) {
            $datosSocioMembresia['fecha_inicio'] = $aTimeStart[0];
        }

        $aTimeEnd = explode('T',$datosSocioMembresia['fecha_fin']);

        if($aTimeEnd) {
            $datosSocioMembresia['fecha_fin'] = $aTimeEnd[0];
        }

        $membresia->update($datosSocioMembresia);

        return $this->respond('done', $model);
    }

}
