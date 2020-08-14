<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egresos extends Model
{
    protected $table = 'egresos';
    protected $guarded =['id'];
    public static $rules = array(

    );
}
