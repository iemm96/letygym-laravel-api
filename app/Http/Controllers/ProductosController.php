<?php

namespace App\Http\Controllers;

use App\Productos;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    const MODEL = 'App\Productos';
    use RestActions;
}
