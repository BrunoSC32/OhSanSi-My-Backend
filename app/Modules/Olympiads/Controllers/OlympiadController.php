<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Olympiad;
use App\Modules\Olympiads\Models\OlympiadAreaLevel;
use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Enrollments\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OlympiadController
{
    public function getMaxCategories(Request $request)
    {
        $today = Carbon::now();
        $format = $today->format('Y-m-d');
        $olympiad = Olympiad::where('start_date', '<=', $format)
            ->where('end_date', '>=', $format)
            ->first();

        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una olimpiada activa en esa fecha.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'date' => $format,
            'olympiad_id' => $olympiad->olympiad_id,
            'max_categories_per_olympist' => $olympiad->max_categories_per_olympist
        ]);
    }

    public function getAreasWithLevels($id)
    {
        try {
            $olympiad = Olympiad::findOrFail($id);
            $areasWithLevels = OlympiadAreaLevel::with([
                'area:area_id,area_name',
                'level:level_id,level_name'
            ])
            ->where('olympiad_id', $id)
            ->get()
            ->groupBy('area_id');
            $response = [
                'year' => $olympiad->year,
                'areas' => $areasWithLevels->map(function ($items, $areaId) {
                    return [
                        'area_id' => (int) $areaId,
                        'area_name' => $items->first()->area->area_name,
                        'levels' => $items->map(function ($item) {
                            return [
                                'level_id' => $item->level_id,
                                'level_name' => trim($item->level->level_name),
                            ];
                        })->unique('level_id')->values()
                    ];
                })->values()
            ];
            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getStatistics($olympiad_id)
    {
        $total_areas = OlympiadAreaLevel::where('olympiad_id', $olympiad_id)
            ->distinct('area_id')
            ->count('area_id');
        $total_levels = OlympiadAreaLevel::where('olympiad_id', $olympiad_id)
            ->distinct('level_id')
            ->count('level_id');
        $payed_lists = EnrollmentList::where('olympiad_id', $olympiad_id)
            ->where('status', 'PAGADO')
            ->pluck('list_id');
        $total_enrolled = Enrollment::whereIn('id_lista', $payed_lists)
            ->distinct('olympist_detail_id')
            ->count('olympist_detail_id');

        return [
            'total_areas' => $total_areas,
            'total_niveles' => $total_levels,
            'total_inscritos' => $total_enrolled,
        ];
    }

    public function getMaxCategoriesById($id)
    {
        $olympiad = Olympiad::find($id);
        if (!$olympiad) {
            return response()->json([
                'message' => 'Olimpiada no encontrada',
                'status' => 404
            ], 404);
        }
        return response()->json([
            'message' => 'Máximo de categorías obtenido correctamente',
            'max_categories' => $olympiad->max_categories_per_olympist
        ], 200);
    }

}
