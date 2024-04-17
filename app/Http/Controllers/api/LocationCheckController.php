<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LocationCheckController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90', // latitude values range between -90 and 90
            'longitude' => 'required|numeric|between:-180,180', // longitude values range between -180 and 180
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the input latitude and longitude
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Count how many times a line from the input point to infinity crosses the polygon boundaries
        $count = $this->rayCasting($latitude, $longitude);

        // If count is odd, the point is inside the polygon, otherwise, it's outside
        $isInside = ($count % 2 == 1);

        // Prepare response data
        $data = [
            'status' => 200,
            'message' => "Point is " . ($isInside ? "inside" : "outside") . " the polygon. Lat: $latitude, Lon: $longitude"
        ];

        return response()->json($data, 200);
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
}
