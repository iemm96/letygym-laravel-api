<?php

namespace App\Http\Controllers;

use App\AppStatus;
use Illuminate\Http\Request;

class AppStatusController extends Controller
{
    const MODEL = 'App\AppStatus';
    use RestActions;

    public function getTurnoActual() {
        $model = self::MODEL;
        $model = $model::find(1);
        if(is_null($model)){
            return $this->respond('not_found');
        }

        return $this->respond('done', array('turno' => $model->turnoActual));
    }

    public function updateTurnoActual($turno) {
        $m = self::MODEL;
        $model = $m::find(1);
        if(is_null($model)){
            return $this->respond('not_found');
        }
        $model->update(['turnoActual' => $turno]);
        return $this->respond('done', $model);
    }

}
