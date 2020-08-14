<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingresos extends Model
{
    protected $table = 'ingresos';
    protected $guarded =['id'];
    public static $rules = array(

    );
}
