<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Province;

class ProvinceController
{
    public function index()
    {
        $provinces = Province::select('province_id', 'province_name')->get();
        return response()->json($provinces, 200);
    }

    public function byDepartment($id)
    {
        $provinces = Province::where('department_id', $id)->get();

        if ($provinces->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron provincias para este departamento.',
                'status' => 404
            ], 404);
        }

        return response()->json($provinces, 200);
    }
}
