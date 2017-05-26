<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
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

    public function cities(Request $request)
    {
        $repository = app('App\Map');
        $districts = $repository->district('city');
        return compact('districts');
    }
}
