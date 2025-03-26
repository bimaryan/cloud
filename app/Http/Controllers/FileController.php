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

    public function edit($uuid)
    {
        $file = File::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        $folders = Folder::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->get();

        if (!in_array($file->mime_type, ['text/html', 'text/css', 'application/javascript', 'text/javascript', 'application/x-httpd-php'])) {
            return redirect()->back()->with('error', 'File ini tidak bisa diedit.');
        }

        $filePath = storage_path("app/public/" . $file->path);
        $content = file_get_contents($filePath);

        // Perbaikan di sini
        $items = collect($folders)->push($file)->sortByDesc(fn($item) => $item->created_at->timestamp)->values();

        // Membuat Breadcrumb berdasarkan folder hierarki
        $breadcrumb = collect();
        $currentFolder = $file->folder; // Asumsi file memiliki relasi dengan folder

        while ($currentFolder) {
            $breadcrumb->prepend([
                'name' => $currentFolder->name,
                'url'  => route('dashboard.show', $currentFolder->uuid)
            ]);
            $currentFolder = $currentFolder->parent; // Ambil parent folder berikutnya
        }

        // Tambahkan File ke Breadcrumb
        $breadcrumb->push([
            'name' => $file->name,
            'url'  => route('files.edit', $file->uuid)
        ]);

        return view('dashboard.edit.index', compact('file', 'content', 'breadcrumb', 'folders'));
    }

    public function updateContent(Request $request, $uuid)
    {
        $file = File::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();

        if (!in_array($file->mime_type, ['text/html', 'text/css', 'application/javascript', 'text/javascript', 'application/x-httpd-php'])) {
            return redirect()->back()->with('error', 'File ini tidak bisa diedit.');
        }

        $filePath = storage_path("app/public/" . $file->path);
        file_put_contents($filePath, $request->content);

        return redirect()->route('files.edit', $file->uuid)->with('success', 'File berhasil diperbarui.');
    }

    /**
     * Upload file ke dalam folder.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp3,wav,mp4,avi,mov,mkv,pdf,doc,docx,xls,xlsx,pptx,php,html,js,css,jsx,tsx,txt|max:102400',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName(); // Ambil nama asli file
        $extension = $file->getClientOriginalExtension(); // Ambil ekstensi file
        $path = 'uploads/' . Auth::id();

        // Simpan file dengan nama asli agar format tidak berubah
        $storedPath = $file->storeAs($path, $originalName, 'public');

        // Ambil informasi file yang sebenarnya
        $filePath = storage_path("app/public/$storedPath");
        $mimeType = mime_content_type($filePath); // Baca MIME type asli

        // Kompresi hanya untuk gambar (hindari kompresi file selain gambar)
        if (str_starts_with($mimeType, 'image/')) {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($filePath);
        }

        // Simpan data file ke database
        File::create([
            'name' => $originalName,
            'status' => $request->status,
            'path' => $storedPath,
            'size' => filesize($filePath), // Ambil ukuran asli file
            'mime_type' => $mimeType, // Gunakan MIME asli, bukan dari Laravel
            'folder_id' => $request->folder_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'File berhasil diunggah!');
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
