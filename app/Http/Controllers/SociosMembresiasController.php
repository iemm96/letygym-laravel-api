<?php

namespace App\Http\Controllers;

use App\Asistencia;
use App\Socios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SociosMembresiasController extends Controller
{
    const MODEL = 'App\SociosMembresias';
    const TABLE = 'socios_membresias AS sm';
    use RestActions;

    public function getRecord($id) {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');
        $m = self::MODEL;

        $membresia = $m::where('id_socio',$id)->first();

        $socio = Socios::where('id',$membresia->id_socio)->first();

        if($socio and $membresia) {
            $socio->bActiva = $membresia->bActiva;
            $socio->fecha_inicio = $membresia->fecha_inicio;
            $socio->fecha_fin = $membresia->fecha_fin;
            $socio->id_membresia = $membresia->id_membresia;
        }

        return $this->respond('done', $socio);
    }

    public function getRecords() {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');
        $m = self::MODEL;
        $membresiasModel = 'App\Membresias';

        //Get Socios
        $result = DB::table('socios AS s')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                's.id AS id_socio',
                's.nombre'
            )
            ->where('s.bVisitante','=','0')
            ->get()->toArray();

        if($result) {
            //iterate results to convert datetime to human
            foreach ($result as &$item) {

                $membresia = $m::where('id_socio',$item->id_socio)->first();

                if(!$membresia) {
                    return $this->respond('conflict',array('msg' => "Error al encontrar la membresía del socio {$item->id}"));
                }

                $oDateTimeNow = new \DateTime();
                $oDateTimeEnd = new \DateTime($membresia->fecha_fin);

                if(is_null($membresia->id_membresia)) {
                    $item->membresia = 'Sin membresía';
                    $item->bActiva = 'Inactiva';
                    $item->fecha_fin  = 'N/A';

                }else{
                    $infoMembresia = $membresiasModel::find($membresia->id_membresia);
                    $item->membresia = $infoMembresia['membresia'];
                    $item->bActiva = $membresia->bActiva ? 'Activa' : 'Caducada';
                    $fechaHora = Carbon::parse($membresia->fecha_fin);

                    //Si la fecha actual es mayor a la fecha de fin se toma como membresía inactiva
                    if($oDateTimeNow > $oDateTimeEnd) {
                        $item->bActiva = 'Caducada';
                    }

                    $item->fecha_fin = $fechaHora->format('d/m/Y');
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

    public function hasMembresiaActiva($id) {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $model = Socios::find($id);

        if(is_null($model)){
            return $this->respond('not_found',array('msg' => 'socio no encontrado'));
        }

        $m = self::MODEL;
        $membresia = $m::where('id_socio','=',$model->id)->first();

        if(is_null($membresia)) {
            return $this->respond('not_found', array('msg' => 'membresía no encontrada'));
        }

        //Get current datetime
        $date = Carbon::now('America/Mexico_City');

        //create datetime
        $dateEnd = Carbon::createFromFormat('Y-m-d', $membresia->fecha_fin);

        //Validate Membership
        if($date->greaterThan($dateEnd)) {
            $membresia->bActiva = 0;
            $membresia->save();
        }

        return $this->respond('done',$membresia);
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
        $modelIngresos = 'App\Ingresos';
        $modelAppStatus = 'App\AppStatus';
        $modelSocios = 'App\Socios';

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

        $this->validate($request, $m::$rules);
        $socio = Socios::find($id);

        if(is_null($socio)){
            return $this->respond('not_found',array('msg' => 'socio no encontrado'));
        }

        if (is_null($membresia = $m::where('id_socio','=',$socio->id)->first())) {
            return $this->respond('not_found','membresía no encontrada');
        }

        //Get current datetime
        $date = Carbon::now('America/Mexico_City');
        $startDate = Carbon::parse($date)->format('Y-m-d');
        $nowDateTime = Carbon::parse($date)->format('Y-m-d H:i');

        $datosMembresia = array(
            'id_membresia' => $request->get('id_membresia'),
            'fecha_inicio' => $startDate,
            'bActiva' => '1',
            'diasProrroga' => $request->get('diasProrroga') ? $request->get('diasProrroga') : '0',
            'bIntentaRenovar' => '0'
        );

        //if fechaSigCobro exists
        if($request->get('fechaSigCobro')) {
            $datosMembresia['fecha_fin'] = $request->get('fechaSigCobro');
        }

        //Update Membership
        $membresia->update($datosMembresia);

        $datosPago = array(
            'id_socio' => $membresia->id_socio,
            'concepto' => 'Pago Membresía "' . $membresia->membresia . '"',
            'monto' => $request->get('pago'),
            'fechaHora' => $nowDateTime
        );

        //Create Pago Record
        $modelPagos::create($datosPago);

        if(!$socio = $modelSocios::find($membresia->id_socio)) {
            return $this->respond('done', $membresia);
        }

        $datosIngreso = array(
            'turno' => $turnoActual,
            'tipo_pago' => '1',
            'concepto' => "Pago Membresía {$membresia->membresia} de {$socio->nombre} {$socio->apellidoPaterno} {$socio->apellidoMaterno}",
            'cantidad' => $request->get('pago'),
            'fechaHora' => $nowDateTime
        );

        //Create Ingreso Record
        $modelIngresos::create($datosIngreso);

        //Se registra la asistencia
        Asistencia::create([
            'id_socio' => $socio->id,
            'fechaHora' => $nowDateTime,
            'turno' => $turnoActual
        ]);

        return $this->respond('done', $membresia);
    }

}
