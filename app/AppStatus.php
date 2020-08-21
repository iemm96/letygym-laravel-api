<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppStatus extends Model
{
    protected $table = 'app_status';
    protected $fillable = ['turnoActual','costo_visita'];
    public static $rules = array(

    );

}
