<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Tampilkan daftar file dalam folder tertentu.
     */
    public function index($folderId = null)
    {
        $files = File::where('user_id', Auth::id())
            ->where('folder_id', $folderId)
            ->get();

        return view('files.index', compact('files', 'folderId'));
    }

    /**
     * Upload file ke dalam folder.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp3,wav,mp4,avi,mov,mkv|max:10240',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/' . Auth::id(), 'public');

        File::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'folder_id' => $request->folder_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diunggah!');
    }

    /**
     * Unduh file.
     */
    public function show($id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->download(storage_path("app/public/{$file->path}"), $file->name);
    }

    /**
     * Hapus file.
     */
    public function destroy($id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Pastikan file ada sebelum menghapusnya
        if (Storage::exists("public/{$file->path}")) {
            Storage::delete("public/{$file->path}");
        }

        // Hapus dari database
        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus!');
    }
}
