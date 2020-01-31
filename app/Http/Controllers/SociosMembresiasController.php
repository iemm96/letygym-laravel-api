<?php

namespace App\Http\Controllers;

use App\SociosMembresias;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SociosMembresiasController extends Controller
{
    const MODEL = 'App\SociosMembresias';
    const TABLE = 'socios_membresias AS sm';
    use RestActions;

    public function getRecords() {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $result = DB::table(self::TABLE)
            ->join('socios AS s','sm.id_socio', '=','s.id')
            ->join('membresias AS m','sm.id_membresia', '=','m.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                'sm.id',
                'm.membresia',
                'sm.bActiva',
                'sm.fecha_fin'
            )
            ->where('s.bVisitante','=','0')
            ->get()->toArray();

        if($result) {
            //iterate results to convert datetime to human
            foreach ($result as &$item) {

                $fechaHora = Carbon::parse($item->fecha_fin);

                $item->fecha_fin = $fechaHora->format('d/m/Y');

                if($item->bActiva) {
                    $item->bActiva = 'Activa';
                }else{
                    $item->bActiva = 'Caducada';
                }
            }
        }
        return $this->respond('done', $result);
    }

    public function getRenovaciones() {

        $result = DB::table(self::TABLE)
            ->join('socios AS s','sm.id_socio', '=','s.id')
            ->join('membresias AS m','sm.id_membresia', '=','m.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                's.nombre',
                'sm.id',
                'm.membresia',
                'sm.bActiva',
                'sm.fecha_fin'
            )
            ->where('sm.bIntentaRenovar','=','1')
            ->get()->toArray();

        if(isset($result[0])) {
            return $this->respond('done', $result[0]);
        }else{
            return $this->respond('done', $result);

        }
    }

    public function getSocioMembresiaById($id) {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $m = self::MODEL;
        $modelAsistencia = 'App\Asistencia';

        $model = $m::find($id);

        if(is_null($model)){
            return $this->respond('not_found');
        }

        //Get current datetime
        $date = Carbon::now('America/Mexico_City');

        $datosAsistencia = array(
            'id_socio' => $model->id_socio,
            'fechaHora' => Carbon::parse($date)->format('Y-m-d H:i:s')
        );


        //create datetime
        $dateEnd = Carbon::createFromFormat('Y-m-d', $model->fecha_fin);

        //Validate Membership
        if($date->greaterThan($dateEnd)) {
            $model->bActiva = 0;
            $model->save();
        }

        if($model->bActiva == '0') {
            $model->bIntentaRenovar = '1';
            $model->save();
        }

        if($model->bActiva == '1') {
            $modelAsistencia::create($datosAsistencia);
        }

        return $this->respond('done', $model);
    }

    public function getSociosyVisitantes() {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $result = DB::table(self::TABLE)
            ->join('socios AS s','sm.id_socio', '=','s.id')
            ->join('membresias AS m','sm.id_membresia', '=','m.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                'sm.id',
                's.id AS id_socio',
                'm.membresia',
                'sm.bActiva',
                's.bVisitante',
                'sm.fecha_fin'
            )
            ->get()->toArray();

        if($result) {
            //iterate results to convert datetime to human
            foreach ($result as &$item) {

                $fechaHora = Carbon::parse($item->fecha_fin);

                $item->fecha_fin = $fechaHora->format('m/d/Y');
            }
        }
        return $this->respond('done', $result);
    }

    public function updateRecord(Request $request, $id)
    {
        $m = self::MODEL;
        $modelMembresia = 'App\Membresias';
        $modelPagos = 'App\Pagos';

        $this->validate($request, $m::$rules);
        $model = $m::find($id);
        if(is_null($model)){
            return $this->respond('not_found');
        }

        if (is_null($membresia = $modelMembresia::find($request->get('id_membresia')))) {
            return $this->respond('not_found');
        }

        //Get current datetime
        $date = Carbon::now('America/Mexico_City');
        $startDate = Carbon::parse($date)->format('Y-m-d');
        $nowDateTime = Carbon::parse($date)->format('Y-m-d H:i');

        //Add time period based on $idMembresia
        switch ($membresia->id) {
            case 1: {
                $endDate = Carbon::parse($date->addWeek())->format('Y-m-d');
                break;
            }
            case 2: {
                $endDate = Carbon::parse($date->addWeeks(3))->format('Y-m-d');
                break;
            }
            case 3: {
                $endDate = Carbon::parse($date->addMonth())->format('Y-m-d');
                break;
            }
            case 4: {
                $endDate = Carbon::parse($date->addYear())->format('Y-m-d');
                break;
            }
        }

        $datosMembresia = array(
            'id_membresia' => $membresia->id,
            'fecha_inicio' => $startDate,
            'bActiva' => '1',
            'bIntentaRenovar' => '0'
        );

        if(isset($endDate)) {
            $datosMembresia['fecha_fin'] = $endDate;
        }

        //Update Membership
        $model->update($datosMembresia);

        $datosPago = array(
            'id_socio' => $model->id_socio,
            'concepto' => 'Pago Membresía "' . $membresia->membresia . '"',
            'monto' => $request->get('pago'),
            'fechaHora' => $nowDateTime
        );

        //Create Pago Record
        $modelPagos::create($datosPago);

        return $this->respond('done', $model);
    }

}
