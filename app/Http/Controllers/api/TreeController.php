<?php

namespace App\Http\Controllers\api;

use App\Models\Tree;
use App\Models\User;
use App\Models\ChangeLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TreeController extends Controller
{
    public function index(Request $request)
    {
        try {
            Tree::all();
        } catch (\Throwable $e) {
            $data = [
                'status' => 500,
                'trees' => "Database Error"
            ];
            return response()->json($data, 500);
        };
        if ($request->has('id')) {
            $tree = Tree::find($request->id);
            if ($tree) {
                $data = [
                    'status' => 200,
                    'tree' => $tree
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'tree' => "Tree not found"
                ];
                return response()->json($data, 404);
            }
        } else {
            $trees = Tree::where('is_deleted', 0)->get();
            if ($trees->count() > 0) {
                $data = [
                    'status' => 200,
                    'trees' => $trees
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'message' => "No trees found"
                ];
                return response()->json($data, 404);
            }
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|string|exists:user,id|max:36',
            'species' => 'required|string|exists:species,species|max:255',
            'latitude' => 'required|numeric|between:-90,90', // latitude values range between -90 and 90
            'longitude' => 'required|numeric|between:-180,180', // longitude values range between -180 and 180
            'health_status' => 'nullable|string|exists:health_status,health_status|max:24',
            'circumference' => 'nullable|numeric|between:0,9999.9',
            'height' => 'nullable|integer|min:0',
            'planted' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $count = $this->rayCasting($latitude, $longitude); // Ray Casting Algorithm
            $isInside = ($count % 2 == 1); // If count is odd, then tree is in area
            $isInside = true; // For testing purposes
            if ($isInside === false) {
                $data = [
                    'status' => 403,
                    'message' => "Location not in the area"
                ];
                return response()->json($data, 403);
            } else {
                try {
                    $user = User::find($request->id_user);
                    if ($user->id_user_type == 1) {
                        $tree = [
                            'id_user' => $request->id_user,
                            'species' => $request->species,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                            'health_status' => $request->health_status,
                            'circumference' => $request->circumference,
                            'height' => $request->height,
                            'planted' => $request->planted,
                            'is_deleted' => 0
                        ];

                        $changeLog = ChangeLog::create([
                            'id_user' => $request->id_user,
                            'date' => Carbon::now(),
                            'new_values' => json_encode($tree),
                            'table_name' => 'tree',
                            'operation' => 'INSERT'
                        ]);
                        if ($changeLog) {
                            $data = [
                                'status' => 200,
                                'message' => "Tree added to change log",
                                'tree' => $tree,
                                'change_log' => $changeLog
                            ];
                            return response()->json($data, 200);
                        } else {
                            $data = [
                                'status' => 500,
                                'message' => "Error Adding Tree"
                            ];
                            return response()->json($data, 500);
                        }
                    } else {
                        $tree = Tree::create([
                            'creation_date' => Carbon::now(),
                            'id_user' => $request->id_user,
                            'species' => $request->species,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                            'health_status' => $request->health_status,
                            'circumference' => $request->circumference,
                            'height' => $request->height,
                            'planted' => $request->planted,
                            'is_deleted' => 0
                        ]);
                        if ($tree) {
                            $data = [
                                'status' => 200,
                                'message' => "Tree Created Successfully",
                                'tree' => $tree
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
                } catch (\Throwable $e) {
                    $data = [
                        'status' => 500,
                        'message' => "Tree Creation Error",
                        'error' => $e->getMessage()
                    ];
                    return response()->json($data, 500);
                }
            }
        }
    }

    // Ray casting algorithm implementation
    private function rayCasting($lat, $lon)
    {
        // Fetch polygon points from database
        $points = DB::table('area_points')->orderBy('id')->get();

        // Initialize count of intersections
        $count = 0;

        // Iterate through each pair of adjacent points
        $prevLat = $points[count($points) - 1]->latitude;
        $prevLon = $points[count($points) - 1]->longitude;
        foreach ($points as $point) {
            $currLat = $point->latitude;
            $currLon = $point->longitude;

            // Check if the line segment intersects with the horizontal ray
            if (($prevLat < $lat && $currLat >= $lat) || ($currLat < $lat && $prevLat >= $lat)) {
                // Check if the point is to the left of the line
                if ($prevLon + ($lat - $prevLat) / ($currLat - $prevLat) * ($currLon - $prevLon) < $lon) {
                    // Increment count if the intersection is to the left
                    $count++;
                }
            }

            // Move to the next pair of points
            $prevLat = $currLat;
            $prevLon = $currLon;
        }

        return $count;
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|exists:tree,id',
            'id_user' => 'required|string|exists:user,id|max:36'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $tree = Tree::find($request->id);
                if ($tree) {
                    $tree->is_deleted = 1;
                    $tree->save();
                    $data = [
                        'status' => 200,
                        'message' => "Tree Deleted Successfully"
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'status' => 404,
                        'message' => "Tree not found"
                    ];
                    return response()->json($data, 404);
                }
            } catch (\Throwable $e) {
                $data = [
                    'status' => 500,
                    'message' => "Tree Deletion Error",
                    'error' => $e->getMessage()
                ];
                return response()->json($data, 500);
            }
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|exists:tree,id',
            'id_user' => 'required|string|exists:user,id|max:36',
            'species' => 'nullable|string|exists:species,species|max:255',
            'latitude' => 'nullable|numeric|between:-90,90', // latitude values range between -90 and 90
            'longitude' => 'nullable|numeric|between:-180,180', // longitude values range between -180 and 180
            'health_status' => 'nullable|string|exists:health_status,health_status|max:24',
            'circumference' => 'nullable|numeric|between:0,9999.9',
            'height' => 'nullable|integer|min:0',
            'planted' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $tree = Tree::find($request->id);
                if ($tree) {
                    $orginalValues = $tree->toArray();
                    $oldValues["id"] = $orginalValues["id"];
                    $newValues["id"] = $orginalValues["id"];
                    $fillableFields = ['species', 'latitude', 'longitude', 'health_status', 'circumference', 'height', 'planted'];
                    foreach ($fillableFields as $field) {
                        if ($request->has($field)) {
                            $oldValues[$field] = $orginalValues[$field];
                            $newValues[$field] = $request->input($field);
                        }
                    }
                    $changeLog = ChangeLog::create([
                        'id_user' => $request->id_user,
                        'date' => Carbon::now(),
                        'old_values' => json_encode($oldValues),
                        'new_values' => json_encode($newValues),
                        'table_name' => 'tree',
                        'operation' => 'UPDATE'
                    ]);
                    if ($changeLog) {
                        $data = [
                            'status' => 200,
                            'message' => "Tree added to change log",
                            'tree' => $tree,
                            'change_log' => $changeLog
                        ];
                        return response()->json($data, 200);
                    } else {
                        $data = [
                            'status' => 500,
                            'message' => "Error Changing Tree"
                        ];
                        return response()->json($data, 500);
                    }
                } else {
                    $data = [
                        'status' => 404,
                        'message' => "Tree not found"
                    ];
                    return response()->json($data, 404);
                }
            } catch (\Throwable $e) {
                $data = [
                    'status' => 500,
                    'message' => "Tree Change Error",
                    'error' => $e->getMessage()
                ];
                return response()->json($data, 500);
            }
        }
    }
}
