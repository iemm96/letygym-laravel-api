<?php

namespace App\Http\Controllers;

use App\Ventas;
use App\Transacciones;
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

                $fecha = Carbon::parse($item->fechaHora);

                //Se parsea fecha y hora a humano
                $fecha->format("F");
                $item->fechaHora = $fecha->formatLocalized('%d/%B/%Y %H:%M') . ' Hrs.';

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
            return response()->json(['error' => 'No hay productos suficientes para completar la venta, intente agregar más productos desde el administrador de productos'], 200);
        }

        //Update product quantity
        $result->save();

        //save transaction as earning
        Transacciones::create([
            'tipo' => 1,
            'turno' => $request->get('turno'),
            'concepto' => "Venta de {$request->get('cantidad')} {$result->producto}",
            'cantidad' => $request->get('total'),
            'fechaHora' => $nowDateTime->toDateTimeString()
        ]);

        return $this->respond('created', $m::create($arrayRecord));
    }

}
