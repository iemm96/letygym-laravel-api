<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transacciones extends Model
{
    protected $table = 'transacciones';
    protected $guarded =['id'];
    public static $rules = array(

    );
}
