<?php

namespace App\Http\Controllers;

use App\VisitantesVisitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitantesVisitasController extends Controller
{
    public static $rules = array(
        'nombre' => 'required',
    );

    const MODEL = 'App\VisitantesVisitas';
    const TABLE = 'visitantes_visitas AS vv';
    use RestActions;

    public function deleteRecord($id) {
        $m = self::MODEL;
        $modelSocio = 'App\Socios';

        if(is_null($result = $m::find($id))){
            return $this->respond('not_found');
        }

        //Delete Socio
        $modelSocio::destroy($result->id_socio);

        //Destroy record
        $m::destroy($id);

        return $this->respond('removed');
    }

    public function getRecords() {

        $result = DB::table(self::TABLE)
            ->join('socios AS s','vv.id_socio', '=','s.id')
            ->select(
                DB::raw("CONCAT(s.nombre,' ',s.apellidoPaterno,' ',s.apellidoMaterno) AS nombreCompleto"),
                's.nombre',
                'vv.id',
                'vv.id_socio',
                'vv.visitas'
            )
            ->where('s.bVisitante','=','1')
            ->get()->toArray();

        return $this->respond('done', $result);
    }

    public function storeRecord(Request $request){
        $m = self::MODEL;

        $modelSocio = 'App\Socios';

        $arraySocio = array(
            'nombre' => $request->get('nombre'),
            'apellidoPaterno' => $request->get('apellidoPaterno'),
            'apellidoMaterno' => $request->get('apellidoMaterno'),
            'bVisitante' => 1,
        );

        //Store Socio
        $idSocio = $modelSocio::create($arraySocio)->id;

        $arrayRecord = array(
            'id_socio' => $idSocio,
            'visitas' => 0,
        );

        return $this->respond('created', $m::create($arrayRecord));
    }


}
