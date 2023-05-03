<?php

namespace App\Http\Controllers;

use App\Models\IsPanitiaFormActive;
use Illuminate\Http\Request;

class IsPanitiaFormActiveController extends Controller
{
    public function index()
    {
        $active = IsPanitiaFormActive::all();

        $bool = filter_var($active->isActive, FILTER_VALIDATE_BOOLEAN);

        if (!$active) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'isActive' => $bool,
        ], 201);
    }

    public function edit(Request $request)
    {
        $active = IsPanitiaFormActive::find(1);

        $request->validate([
            'isActive' => 'boolean'
        ]);

        $bool = filter_var($request->isActive, FILTER_VALIDATE_BOOLEAN);

        $active->update(['isActive' => $bool]);

        if (!$active) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'isActive' => $active->isActive,
        ], 201);
    }
}
