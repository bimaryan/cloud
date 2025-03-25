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

    public function update(Request $request, $id)
    {
        $file = Folder::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $file->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'File name updated successfully.');
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
