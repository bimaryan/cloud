<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $folders = Folder::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->get();

        $files = File::where('user_id', Auth::id())
            ->whereNull('folder_id')
            ->orderByDesc('created_at')
            ->get();

        // Gabungkan hasil query dan urutkan lagi jika perlu
        $items = $folders->merge($files)->sortByDesc('created_at');

        return view('dashboard.index', compact('items'));
    }

    public function show($uuid)
    {
        $folder = Folder::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        $subfolders = Folder::where('parent_id', $folder->id)->where('user_id', Auth::id())->get();
        $files = File::where('folder_id', $folder->id)->where('user_id', Auth::id())->get();

        $items = $subfolders->merge($files)->sortByDesc('created_at');

        return view('dashboard.show.index', compact('items', 'folder'));
    }
}
