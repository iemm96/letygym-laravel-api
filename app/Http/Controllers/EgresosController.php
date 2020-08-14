<?php

namespace App\Http\Controllers;

use App\Egresos;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EgresosController extends Controller
{
    const MODEL = 'App\Egresos';

    public function __construct() {
        setlocale(LC_ALL, 'es_ES');

    }

    use RestActions;

    public function getRecords($turno,$fecha = null) {

        $model = self::MODEL;

        if($turno == 1) {
            $turno = 'Matutino';
        }elseif($turno == 2){
            $turno = 'Vespertino';
        }else{
            return $this->respond('conflict', array('Error, turno no válido'));
        }


        //Si no se específica la fecha se obtiene la fecha actual
        if(!$fecha) {
            $dtNow = Carbon::now();
            $fecha = $dtNow->format('Y-m-d');
        }

        //Se obtienen los egresos
        if(!$egresos = $model::where('turno','=',$turno)
            ->whereDate('fechaHora','=',$fecha)
            ->get()) {
            return $this->respond('not_found');
        }

        //Se iteran los egresos para formatear la fecha a humano
        foreach ($egresos as &$ingreso) {
            $fecha = Carbon::parse($ingreso->fechaHora);

            //Se parsea fecha y hora a humano
            $fecha->format("F");
            $ingreso->fechaHora = $fecha->formatLocalized('%d/%B/%Y %H:%M') . ' Hrs.';
        }

        return $this->respond('done',$egresos);
    }

    public function getTotal($fecha = null) {
        $model = self::MODEL;

        //Si no se específica la fecha se obtiene la fecha actual
        if(!$fecha) {
            $dtNow = Carbon::now();
            $fecha = $dtNow->format('Y-m-d');
        }

        //Se obtienen los egresos
        if(!$egresos = $model::whereDate('fechaHora','=',$fecha)->get()) {
            return $this->respond('not_found');
        }

        $total = 0; //Variable para guardar el total de los egresos del día

        //Se iteran los egresos para formatear la fecha a humano
        foreach ($egresos as &$ingreso) {
            //Se calcula el total de egresos
            $total += $ingreso->cantidad;
        }

        return $this->respond('done',$total);
    }
}
