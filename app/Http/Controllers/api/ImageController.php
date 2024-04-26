<?php

namespace App\Http\Controllers\api;

use App\Models\TreeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tree' => 'required|integer|exists:tree,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            #return response()->json("Later", 418);

            $photos = TreeImage::where('id_tree', $request->id_tree)->where('is_deleted', 0)->get();

            if ($photos->isEmpty()) {
                $data = [
                    'status' => 204,
                    'tree_images' => "Not images associated with this tree"
                ];
                return response()->json($data, 404);
            } else {
                $photoData = [];
                foreach ($photos as $photo) {
                    $photoData[] = [
                        'id_tree' => $photo->id_tree,
                        'image_index' => $photo->image_index,
                        'id_user' => $photo->id_user,
                        'upload_date' => $photo->upload_date,
                        'photoUrl' => asset('/storage/images/tree/' . $photo->id_tree . '/img-' . $photo->image_index . '.' . $photo->file_type)
                    ];
                }
                $data = [
                    'status' => 200,
                    'tree_images' => $photoData
                ];
                return response()->json($data, 200);
            }
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,jpeg,png|max:512',  //512
            'id_tree' => 'required|integer|exists:tree,id',
            'id_user' => 'required|string|exists:user,id|max:36'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $image = $request->image;
                $treeID = $request->id_tree;
                $index = (TreeImage::where('id_tree', $treeID)->orderBy('image_index', 'desc')->value('image_index') ?? 0) + 1;
                $imageName = 'img-' . $index . '.' . $image->extension();
                $image->storeAs('images/tree/' . $treeID, $imageName, 'public');

                $treeImage = TreeImage::create([
                    'id_tree' => $treeID,
                    'image_index' => $index,
                    'file_type' => $image->extension(),
                    'id_user' => $request->id_user,
                    'upload_date' => Carbon::now()
                ]);
            } catch (\Throwable $e) {
                $data = [
                    'status' => 500,
                    'message' => "Image Upload Error",
                    'error' => $e
                ];
                return response()->json($data, 500);
            }
            if ($treeImage) {
                $data = [
                    'status' => 200,
                    'message' => "Image uploaded Successfully!",
                    'image' => $imageName
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 500,
                    'message' => "Error Creating Image"
                ];
                return response()->json($data, 500);
            }
        }
    }
}
