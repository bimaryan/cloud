<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use Spatie\ImageOptimizer\OptimizerChainFactory;
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
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp3,wav,mp4,avi,mov,mkv,pdf,doc,docx,xls,xlsx,pptx|max:102400',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/' . Auth::id(), 's3'); // Simpan di Cloudflare R2

        // Optimasi hanya jika penyimpanan lokal (bukan R2)
        if (Storage::disk('local')->exists($path)) {
            $filePath = storage_path("app/$path");

            if (str_contains($file->getMimeType(), 'image') || str_contains($file->getMimeType(), 'video')) {
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($filePath);
            }
        }

        File::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'folder_id' => $request->folder_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diunggah ke Cloudflare R2!');
    }

    public function update(Request $request, $id)
    {
        $file = File::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $file->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'File name updated successfully.');
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
