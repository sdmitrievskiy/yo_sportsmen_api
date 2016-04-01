<?php

namespace App\Http\Controllers;

class SportTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getSportTypesList()
    {
        $result = app('db')->select("SELECT * FROM sport_types"); 
        return response()->json($result);
    }
    //
}
