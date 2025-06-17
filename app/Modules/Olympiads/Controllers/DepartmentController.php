<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Department;

class DepartmentController
{
    public function index()
    {
        $departaments = Department::all();
        return response()->json($departaments, 200);
    }
}
