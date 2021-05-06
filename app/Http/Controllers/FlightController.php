<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FlightController extends Controller
{
    public function make(Request $request, Flight $flight)
    {
//        $allows = Gate::allows('okok', $flight);
//        $allows = $request->user()->can('okok', $flight);
//        dd($allows);

        $flight->update(['user_id' => 1]);
    }
}
