<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class UserImageController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|string|exists:user,id|max:36'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {

            if (Storage::exists('public/images/user/img-' . $request->id_user . '.png')) {
                $pfpURL = asset('storage/images/user/img-' . $request->id_user . '.png');
            } elseif (Storage::exists('public/images/user/img-' . $request->id_user . '.jpg')) {
                $pfpURL = asset('storage/images/user/img-' . $request->id_user . '.jpg');
            } elseif (Storage::exists('public/images/user/img-' . $request->id_user . '.jpeg')) {
                $pfpURL = asset('storage/images/user/img-' . $request->id_user . '.jpeg');
            } else {
                $pfpURL = asset('img-defaultPFP.jpg');
            }
            $data = [
                'status' => 200,
                'pfp_URL' => $pfpURL
            ];
            return response()->json($data, 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,jpeg,png|max:512',
            'id_user' => 'required|string|exists:user,id|max:36'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $image = $request->image;
                $imageName = 'img-' . $request->id_user . '.' . $image->extension();
                $image->storeAs('images/user', $imageName, 'public');
            } catch (\Throwable $e) {
                $data = [
                    'status' => 500,
                    'message' => "Image Upload Error",
                    'error' => $e
                ];
                return response()->json($data, 500);
            }
            $data = [
                'status' => 200,
                'message' => "PFP uploaded Successfully!",
                'image' => $imageName
            ];
            return response()->json($data, 200);
        }
    }
}
