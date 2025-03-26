<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    public function store(Request $request)
    {
        $folder = Folder::find($request->folder_id);
        if ($folder) {
            $folder->parent_id = $request->parent_id;
            $folder->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
}
