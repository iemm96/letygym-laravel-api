<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructores extends Model
{
    protected $table = 'instructores';
    protected $guarded =['id'];
    public static $rules = array(

    );
}
