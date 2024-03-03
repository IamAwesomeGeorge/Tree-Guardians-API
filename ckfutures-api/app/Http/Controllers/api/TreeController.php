<?php

namespace App\Http\Controllers\api;

use App\Models\Tree;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TreeController extends Controller
{
    public function index() {

        try {
            $trees = Tree::all();
        }
        catch (\Throwable $e) {
            $data = [
                'status' => 500,
                'trees' => "Database Error"
            ];
            return response()->json($data, 500);
        };
        

        if($trees->count() > 0) {
            $data = [
                'status' => 200,
                'trees' => $trees
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'trees' => "Not Found"
            ];
            return response()->json($data, 404);
        }
        
    }
}
