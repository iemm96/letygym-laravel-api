<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SociosMembresias extends Model
{
    protected $table = 'socios_membresias';
    protected $guarded =['id'];
    public static $rules = array(
    );
}
