<?php

namespace App\Http\Controllers;

use App\AsistenciasInstructores;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciasInstructoresController extends Controller
{
    const MODEL = 'App\AsistenciasInstructores';
    const TABLE = 'asistencias_instructores as a';
    use RestActions;

    public function getRecords()
    {
        $result = DB::table(self::TABLE)
            ->join('instructores AS i','a.id_instructor', '=','i.id')
            ->select(
                DB::raw("CONCAT(i.nombre,' ',i.apellidoPaterno,' ',i.apellidoMaterno) AS nombreCompleto"),
                'i.nombre',
                'a.fechaHora',
                'a.turno'
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
