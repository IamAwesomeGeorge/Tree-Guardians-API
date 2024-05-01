<?php

namespace App\Http\Controllers\api;

use App\Models\Species;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SpeciesController extends Controller
{
    public function index() {
        try {
            $species = Species::all();
        }
        catch (\Throwable $e) {
            $data = [
                'status' => 500,
                'species' => "Database Error"
            ];
            return response()->json($data, 500);
        };
        

        if($species->count() > 0) {
            $data = [
                'status' => 200,
                'species' => $species
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'status' => 404,
                'species' => "Not Found"
            ];
            return response()->json($data, 404);
        }
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'species' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $newSpecies = strtoupper($request->species);
            $newSpeciesExists = Species::where('species', $newSpecies)->first();

            if ($newSpeciesExists) {
                return response()->json(['message' => 'Species already in database'], 409);
            }
            else {
                $species = Species::create([
                    'species' => $newSpecies
                ]);

                if ($species) {
                    $data = [
                        'status' => 200,
                        'message' => "Species Added Successfully"
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'status' => 500,
                        'message' => "Error Adding Species"
                    ];
                    return response()->json($data, 500);
                }
            }
        }
    }
}
