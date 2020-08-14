<?php

namespace App\Http\Controllers;

use App\AppStatus;
use Illuminate\Http\Request;

class AppStatusController extends Controller
{

    const MODEL = 'App\AppStatus';
    use RestActions;

    public function getTurnoActual() {
        $model = self::MODEL;
        $model = $model::find(1);
        if(is_null($model)){
            return $this->respond('not_found');
        }

        return $this->respond('done', array('turno' => $model->turnoActual));
    }

    public function updateTurnoActual($turno) {
        $m = self::MODEL;
        $model = $m::find(1);
        if(is_null($model)){
            return $this->respond('not_found');
        }
        $model->update(['turnoActual' => $turno]);
        return $this->respond('done', $model);
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
     * @param  \App\AppStatus  $appStatus
     * @return \Illuminate\Http\Response
     */
    public function show(AppStatus $appStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppStatus  $appStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(AppStatus $appStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppStatus  $appStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppStatus $appStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AppStatus  $appStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppStatus $appStatus)
    {
        //
    }
}
