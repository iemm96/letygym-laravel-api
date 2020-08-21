<?php

namespace App\Http\Controllers;

use App\Transacciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionesController extends Controller
{
    const MODEL = 'App\Transacciones';

    public function __construct() {
        setlocale(LC_ALL, 'es_ES');

    }

    use RestActions;

    public function getRecords($turno = null,$fecha = null,$tipo = null) {

        if($turno == 'null') {
            $turno = null;
        }

        if($fecha == 'null') {
            $fecha = null;
        }


        //Si no se específica la fecha se obtiene la fecha actual
        if(!$fecha) {
            $dtNow = Carbon::now();
            $fecha = $dtNow->format('Y-m-d');
        }

        $baseQuery = DB::table('transacciones');

        if($fecha) {
            $baseQuery->whereDate('fechaHora','=',$fecha);
        }

        if($tipo) {

            if($tipo == 1) {
                $tipo = 'Ingreso';
            }elseif($tipo == 2) {
                $tipo = 'Egreso';
            }

            $baseQuery->where('tipo','=',$tipo);
        }

        if($turno) {

            if($turno == 1) {
                $turno = 'Matutino';
            }elseif($turno == 2) {
                $turno = 'Vespertino';
            }

            $baseQuery->where('turno','=',$turno);
        }

        $transacciones = $baseQuery->get();

        //Se iteran los egresos para formatear la fecha a humano
        foreach ($transacciones as &$transaccion) {
            $fecha = Carbon::parse($transaccion->fechaHora);

            //Se parsea fecha y hora a humano
            $fecha->format("F");
            $transaccion->fechaHora = $fecha->formatLocalized('%d/%B/%Y %H:%M') . ' Hrs.';
        }

        return $this->respond('done',$transacciones);
    }

    public function getTotal($fecha = null) {
        $model = self::MODEL;

        //Si no se específica la fecha se obtiene la fecha actual
        if(!$fecha) {
            $dtNow = Carbon::now();
            $fecha = $dtNow->format('Y-m-d');
        }

        //Se obtienen los egresos
        if(!$transacciones = $model::whereDate('fechaHora','=',$fecha)->get()) {
            return $this->respond('not_found');
        }

        $total = 0; //Variable para guardar el total de los egresos del día

        //Se iteran los egresos para formatear la fecha a humano
        foreach ($transacciones as &$ingreso) {
            //Se calcula el total de egresos
            $total += $ingreso->cantidad;
        }

        return $this->respond('done',$total);
    }
}
