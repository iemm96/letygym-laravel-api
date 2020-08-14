<?php

namespace App\Http\Controllers;

use App\ControlTurnos;
use Illuminate\Http\Request;

class ControlTurnosController extends Controller
{
    const MODEL = 'App\ControlTurnos';
    use RestActions;

    public function getTurnoActual() {
            $model = self::MODEL;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ControlTurnos  $controlTurnos
     * @return \Illuminate\Http\Response
     */
    public function show(ControlTurnos $controlTurnos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ControlTurnos  $controlTurnos
     * @return \Illuminate\Http\Response
     */
    public function edit(ControlTurnos $controlTurnos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ControlTurnos  $controlTurnos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ControlTurnos $controlTurnos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ControlTurnos  $controlTurnos
     * @return \Illuminate\Http\Response
     */
    public function destroy(ControlTurnos $controlTurnos)
    {
        //
    }
}
