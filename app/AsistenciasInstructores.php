<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsistenciasInstructores extends Model
{
    protected $table = 'asistencias_instructores';
    protected $guarded =['id'];
    public static $rules = array(

    );
}
