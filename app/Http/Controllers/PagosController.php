<?php

namespace App\Http\Controllers;

use App\Pagos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagosController extends Controller
{
    const MODEL = 'App\Pagos';
    const TABLE = 'pagos as p';
    use RestActions;

    public function getRecords() {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $result = DB::table(self::TABLE)
            ->join('socios AS s','p.id_socio', '=','s.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                'p.id',
                'p.concepto',
                'p.monto',
                'p.fechaHora'
            )
            ->get()->toArray();

        if($result) {
            //iterate results to convert datetime to human
            foreach ($result as &$item) {

                $fechaHora = Carbon::parse($item->fechaHora);

                $item->fechaHora = $fechaHora->format('d/m/Y H:i:s');
            }
        }

        return $this->respond('done', $result);
    }

    public function storeRecord(Request $request){
        $m = self::MODEL;

        $arrayRecord = array(
            'id_socio' => $request->get('id_socio'),
            'concepto' => $request->get('concepto'),
            'monto' => $request->get('monto')
        );

        $nowDateTime = Carbon::now('America/Mexico_City');

        //Get current datetime and store in $arrayRecord
        $arrayRecord['fechaHora'] = $nowDateTime->toDateTimeString();

        return $this->respond('created', $m::create($arrayRecord));
    }

}
