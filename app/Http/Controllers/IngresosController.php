<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class IngresosController extends Controller
{
    const MODEL = 'App\Ingresos';

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

        //Se obtienen los ingresos
        if(!$ingresos = $model::where('turno','=',$turno)
                                ->whereDate('fechaHora','=',$fecha)
                                ->get()) {
            return $this->respond('not_found');
        }

        $total = 0; //Variable para guardar el total de los ingresos del día

        //Se iteran los ingresos para formatear la fecha a humano
        foreach ($ingresos as &$ingreso) {
            $fecha = Carbon::parse($ingreso->fechaHora);

            //Se parsea fecha y hora a humano
            $fecha->format("F");
            $ingreso->fechaHora = $fecha->formatLocalized('%d/%B/%Y %H:%M') . ' Hrs.';

            //Se calcula el total de ingresos
            $total += $ingreso->cantidad;
        }

        return $this->respond('done',array('dataIngresos' => $ingresos, 'totalIngresos' => $total));
    }

    public function getTotal($fecha = null) {
        $model = self::MODEL;

        //Si no se específica la fecha se obtiene la fecha actual
        if(!$fecha) {
            $dtNow = Carbon::now();
            $fecha = $dtNow->format('Y-m-d');
        }

        //Se obtienen los ingresos
        if(!$ingresos = $model::whereDate('fechaHora','=',$fecha)->get()) {
            return $this->respond('not_found');
        }

        $total = 0; //Variable para guardar el total de los ingresos del día

        //Se iteran los ingresos para formatear la fecha a humano
        foreach ($ingresos as &$ingreso) {
            //Se calcula el total de ingresos
            $total += $ingreso->cantidad;
        }

        return $this->respond('done',$total);
    }

}
