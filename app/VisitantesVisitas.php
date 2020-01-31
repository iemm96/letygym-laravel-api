<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitantesVisitas extends Model
{
    protected $table = 'visitantes_visitas';
    protected $guarded =['id'];
    public static $rules = array(
    );
}
