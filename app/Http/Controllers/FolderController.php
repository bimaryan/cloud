<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Simpan folder baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Folder berhasil dibuat!');
    }

    /**
     * Hapus folder dan isinya.
     */
    public function destroy($id)
    {
        $folder = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $folder->delete();
        return redirect()->back()->with('success', 'Folder berhasil dihapus!');
    }
}
