<?php

namespace App\Http\Controllers\api;

use App\Models\ChangeLog;
use App\Models\Tree;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ChangeLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            ChangeLog::all();
        } catch (\Throwable $e) {
            $data = [
                'status' => 500,
                'changeLog' => "Database Error"
            ];
            return response()->json($data, 500);
        };
        if ($request->has('id')) {
            $changeLog = ChangeLog::find($request->id);
            if ($changeLog) {
                $data = [
                    'status' => 200,
                    'changeLog' => $changeLog
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'changeLog' => "Change ID not found"
                ];
                return response()->json($data, 404);
            }
        } else {
            $changeLog = ChangeLog::all();
            if ($changeLog->count() > 0) {
                $data = [
                    'status' => 200,
                    'changeLog' => $changeLog
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 404,
                    'changeLog' => "No changes found"
                ];
                return response()->json($data, 404);
            }
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|exists:change_log,id',
            'approved_by' => 'required|string|exists:user,id|max:36',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $approvedID = $request->approved_by;
            $user = User::find($approvedID);
            if ($user && $user->id_user_type == 1) {
                $data = [
                    'status' => 403,
                    'changeLog' => "User not authorized to make changes",
                ];
                return response()->json($data, 403);
            } else {
                try {
                    $changeLog = ChangeLog::where('id', $request->id)
                    ->where('operation', 'INSERT')
                    ->first();
                    if ($changeLog) {
                        if ($changeLog->approved == 1) {
                            $data = [
                                'status' => 403,
                                'changeLog' => "Change already approved",
                            ];
                            return response()->json($data, 403);
                        } elseif ($changeLog->table_name == 'tree') {
                            $treeInfo = json_decode($changeLog->new_values, true);
                            $treeInfo['creation_date'] = Carbon::now();
                            $tree = Tree::create($treeInfo);

                            $changeLog->approved = 1;
                            $changeLog->approved_by = $approvedID;
                            $changeLog->save();
                            $data = [
                                'status' => 200,
                                'message' => "Change approved successfully",
                                'changeLog' => $changeLog,
                                'tree' => $tree
                            ];
                            return response()->json($data, 200);
                        } else {
                            $data = [
                                'status' => 403,
                                'changeLog' => "Change cannot be approved",
                            ];
                            return response()->json($data, 403);
                        }
                    } else {
                        $data = [
                            'status' => 404,
                            'changeLog' => "Change ID not found"
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
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|exists:change_log,id',
            'approved_by' => 'required|string|exists:user,id|max:36',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $approvedID = $request->approved_by;
            $user = User::find($approvedID);
            if ($user && $user->id_user_type == 1) {
                $data = [
                    'status' => 403,
                    'changeLog' => "User not authorized to make changes",
                ];
                return response()->json($data, 403);
            } else {
                try {
                    $changeLog = ChangeLog::where('id', $request->id)
                    ->where('operation', 'UPDATE')
                    ->first();
                    if ($changeLog) {
                        if ($changeLog->approved == 1) {
                            $data = [
                                'status' => 403,
                                'changeLog' => "Change already approved",
                            ];
                            return response()->json($data, 403);
                        } elseif ($changeLog->table_name == 'tree') {
                            $newTreeInfo = json_decode($changeLog->new_values, true);
                            $treeId = $newTreeInfo['id'];
                            unset($newTreeInfo['id']);
                            $tree = Tree::where('id', $treeId)
                                ->where('is_deleted', 0)
                                ->first();
                            if ($tree) {
                                $tree->update($newTreeInfo);
                            } else {
                                $data = [
                                    'status' => 404,
                                    'changeLog' => "Tree could not be found or tree is deleted.",
                                ];
                                return response()->json($data, 403);
                            }

                            $changeLog->approved = 1;
                            $changeLog->approved_by = $approvedID;
                            $changeLog->save();
                            $data = [
                                'status' => 200,
                                'message' => "Change approved successfully",
                                'changeLog' => $changeLog,
                                'tree' => $tree
                            ];
                            return response()->json($data, 200);
                        } else {
                            $data = [
                                'status' => 403,
                                'changeLog' => "Change cannot be approved",
                            ];
                            return response()->json($data, 403);
                        }
                    } else {
                        $data = [
                            'status' => 404,
                            'changeLog' => "Change ID not found"
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
    }
}
