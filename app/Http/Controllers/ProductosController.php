<?php

namespace App\Http\Controllers;

use App\Productos;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    const MODEL = 'App\Productos';
    use RestActions;

    public function addRecord(Request $request) {

        $model = self::MODEL;
        $modelEgresos = 'App\Egresos';
        $modelAppStatus = 'App\AppStatus';

        //Get current datetime
        $date = Carbon::now('America/Mexico_City');
        $nowDateTime = Carbon::parse($date)->format('Y-m-d H:i');

        $model::create($request->all());

        //Se obtiene el turno actual
        $resultAppStatus = $modelAppStatus::find(1);

        //Si el turno actual no es válido se regresa error
        if($resultAppStatus->turnoActual == 3) {
            $turnoActual = 2;
        }elseif ($resultAppStatus->turnoActual == 1) {
            $turnoActual = 1;
        }else{
            return $this->respond('not_valid', array('msg' => 'Error no se ha iniciado un turno'));
        }

        $datosEgreso = array(
            'turno' => $turnoActual,
            'tipo_pago' => '1',
            'concepto' => "Compra de {$request->get('producto')}",
            'cantidad' => $request->get('costo'),
            'fechaHora' => $nowDateTime
        );

        //Create Egreso Record
        $modelEgresos::create($datosEgreso);

        return $this->respond('done',$datosEgreso);
    }

    public function getRecords() {
        $m = self::MODEL;
        return $this->respond('done', $m::all());
    }
}
