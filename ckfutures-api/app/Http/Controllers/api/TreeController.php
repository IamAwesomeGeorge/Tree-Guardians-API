<?php

namespace App\Http\Controllers\api;

use App\Models\Tree;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'id_user' => 'required|integer|exists:user,id', 
            'species' => 'required|string|max:255', 
            'latitude' => 'required|numeric|between:-90,90', // latitude values range between -90 and 90
            'longitude' => 'required|numeric|between:-180,180', // longitude values range between -180 and 180
            'health_status' => 'nullable|string|max:24', 
            'circumference' => 'nullable|numeric|between:0,9999.9', 
            'planted' => 'nullable|date', 
            'height' => 'nullable|integer|min:0', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $tree = Tree::create([
                'creation_date' => Carbon::now(),
                'id_user' => $request->id_user,
                'species' => $request->species,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'health_status' => $request->health_status,
                'circumference' => $request->circumference,
                'planted' => $request->planted,
                'height' => $request->height,
                'is_deleted' => 0
            ]);

            if ($tree) {
                $data = [
                    'status' => 200,
                    'message' => "Tree Created Successfully"
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 500,
                    'message' => "Error Adding Tree"
                ];
                return response()->json($data, 500);
            }
        }
    
        
    }
}
