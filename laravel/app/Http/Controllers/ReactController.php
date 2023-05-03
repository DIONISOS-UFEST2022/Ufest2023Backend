<?php

namespace App\Http\Controllers;

use App\Models\TestTable;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    public function index() {
        $users = TestTable::all();
        return response()->json($users);
     }
}