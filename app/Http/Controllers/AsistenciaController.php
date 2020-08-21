<?php

namespace App\Http\Controllers;

use App\Asistencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    const MODEL = 'App\Asistencia';
    const TABLE = 'asistencias as a';
    use RestActions;

    public function getRecords()
    {
        $m = self::MODEL;

        $result = DB::table(self::TABLE)
            ->join('socios AS s','a.id_socio', '=','s.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                'a.id',
                's.nombre',
                'a.fechaHora'
            )
            ->orderBy('fechaHora', 'desc')
            ->get()
            ->toArray();

        if($result) {
            //iterate results to convert datetime to human
            foreach ($result as &$item) {
                $item->fechaHora = Carbon::parse($item->fechaHora)->format('d/m/Y H:i');
            }
        }

        return $this->respond('done', $result);
    }

}
