<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Olympiad;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OlympiadManagmentController
{

    public function index()
    {
        $olympiads = Olympiad::all();
        return response()->json($olympiads, 200);
    }
    public function now()
    {
        $today = Carbon::now();
        $olympiads = Olympiad::where('start_date', '>', $today)->get();
        return response()->json($olympiads, 200);
    }

    public function show($year)
    {
        $olympiad = Olympiad::where('year', $year)->get();

        if ($olympiad->isEmpty()) {
            return response()->json([
                'message' => "No se encontró una Olimpiada para la gestión $year."
            ], 404);
        }

        return response()->json($olympiad, 200);
    }
}
