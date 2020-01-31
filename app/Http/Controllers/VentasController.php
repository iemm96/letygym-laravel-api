<?php

namespace App\Http\Controllers;

use App\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasController extends Controller
{
    const MODEL = 'App\Ventas';
    const TABLE = 'ventas AS v';

    use RestActions;

    public function deleteRecord($id) {
        $m = self::MODEL;

        if(is_null($result = $m::find($id))){
            return $this->respond('not_found');
        }

        $producto = 'App\Productos';
        //Get product
        $resultProducto = $producto::find($result->id_producto);

        //calculate new quantitiy
        $resultProducto->cantidad = $resultProducto->cantidad + $result->cantidad;

        //Save updated quantity
        $resultProducto->save();

        //Destroy record
        $m::destroy($id);

        return $this->respond('removed');
    }

    public function getRecords() {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setLocale('es');

        $result = DB::table(self::TABLE)
            ->join('productos AS p','v.id_producto', '=','p.id')
            ->select(
                'v.id',
                'p.producto',
                'v.cantidad',
                'v.total',
                'v.fechaHora'
            )
            ->get()->toArray();

        if($result) {
            //iterate results to convert datetime to human and format total
            foreach ($result as &$item) {

                $fechaHora = Carbon::parse($item->fechaHora);

                $item->fechaHora = $fechaHora->format('m/d/Y H:i');
                $item->total = '$' . $item->total;
            }
        }

        return $this->respond('done', $result);
    }

    public function storeRecord(Request $request){
        $m = self::MODEL;

        $idProducto = $request->get('id_producto');
        $cantidad = $request->get('cantidad');

        $arrayRecord = array(
            'cantidad' => $cantidad,
            'id_producto' => $idProducto,
            'total' => $request->get('total')
        );

        $productos = 'App\Productos';
        //Get product
        $result = $productos::find($idProducto);

        if(is_null($result)){
            return $this->respond('not_found');
        }

        $nowDateTime = Carbon::now('America/Mexico_City');

        //Get current datetime and store in $arrayRecord
        $arrayRecord['fechaHora'] = $nowDateTime->toDateTimeString();

        //calculate new quantitiy
        $result->cantidad = $result->cantidad - $cantidad;

        if($result->cantidad < 0) {
            return $this->respond('cantidad de producto insuficiente');
        }

        //Update product quantity
        $result->save();

        return $this->respond('created', $m::create($arrayRecord));
    }

}
