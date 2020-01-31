<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socios extends Model
{
    protected $table = 'socios';
    protected $guarded =['id'];
    public static $rules = array(
        'nombre' => 'required',
    );
}
