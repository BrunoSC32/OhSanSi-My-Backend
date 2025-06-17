<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Olympiad;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OlympiadRegistrationController
{
    public function index()
    {
        $olympiads = Olympiad::all();
        return response()->json($olympiads, 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer',
                'cost' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'max_categories_per_olympist' => 'required|integer',
                'olympiad_name' => 'required|string|max:255',
            ]);

            $validated['created_in'] = \Carbon\Carbon::now('UTC');

            $olympiad = Olympiad::create($validated);

            return response()->json([
                'message' => 'Olimpiada creada exitosamente',
                'olympiad' => $olympiad
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la olimpiada',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
