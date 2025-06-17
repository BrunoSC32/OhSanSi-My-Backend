<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Area;
use App\Modules\Olympiads\Requests\StoreAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Olympiads\Models\OlympiadAreaLevel;

class AreaController
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $areas = Area::all();
        return response()->json($areas, 200);
    }

    /**
     * Obtener áreas por ID de olimpiada
     */
    public function areasByOlympiad($id)
    {
        $areas = OlympiadAreaLevel::where('olympiad_id', $id)
            ->join('area', 'olympiad_area_level.area_id', '=', 'area.area_id')
            ->select('area.area_id', 'area.area_name')
            ->groupBy('area.area_id', 'area.area_name')
            ->get();

        if ($areas->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron áreas para la olimpiada especificada.',
                'areas' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Áreas encontradas exitosamente.',
            'areas' => $areas
        ], 200);
    }

    /**
     * Registrar una nueva área
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50|unique:area,area_name'
            ]);

            $area = Area::create([
                'area_name' => trim($validated['name'])
            ]);

            return response()->json([
                'message' => 'Area creada correctamente.',
                'level' => $area
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()->first()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Ocurrió un error inesperado.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

}
