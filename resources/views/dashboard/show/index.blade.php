<x-app-layout>
    @if (session('success') || session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "{{ session('success') ? 'success' : 'error' }}",
                    text: {!! json_encode(session('success') ?? session('error')) !!}
                });
            });
        </script>
    @endif

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ url()->previous() ?? route('dashboard.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fa-solid fa-arrow-left mr-2"></i> {{ $folder->name }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-screen-xl mx-auto p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-start">
                <div class="bg-white shadow rounded-lg p-2 md:block hidden">
                    <button onclick="toggleView('grid')" id="gridBtn"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-border-all"></i>
                    </button>
                    <button onclick="toggleView('column')" id="columnBtn"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>
                <div class="bg-white shadow rounded-lg p-2">
                    <button onclick="document.getElementById('addFileModal').classList.remove('hidden')"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-plus"></i> New Files
                    </button>
                    <button onclick="document.getElementById('addFolderModal').classList.remove('hidden')"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-plus"></i> New Folders
                    </button>
                </div>
            </div>

            <!-- Grid Folder dan File -->
            <div id="itemContainer" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                @foreach ($subfolders as $subfolder)
                    <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <i class="fa-regular fa-folder mr-2"></i>
                            <a href="{{ route('dashboard.show', $subfolder->uuid) }}"
                                class="font-medium text-blue-600 hover:underline">
                                {{ $subfolder->name }}
                            </a>
                        </div>
                        <form action="{{ route('folders.destroy', $subfolder->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus folder ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endforeach

                @foreach ($files as $file)
                    <div class="bg-white p-4 rounded-lg shadow">
                        @php
                            $mime = mime_content_type(storage_path('app/public/' . $file->path));
                        @endphp

                        @if (Str::startsWith($mime, 'image/'))
                            <img src="{{ asset('storage/' . $file->path) }}" alt="{{ $file->name }}"
                                class="w-full object-cover rounded-lg">
                        @elseif (Str::startsWith($mime, 'audio/'))
                            <audio controls class="w-full">
                                <source src="{{ asset('storage/' . $file->path) }}" type="{{ $mime }}">
                                Your browser does not support the audio element.
                            </audio>
                        @elseif (Str::startsWith($mime, 'video/'))
                            <video controls class="w-full">
                                <source src="{{ asset('storage/' . $file->path) }}" type="{{ $mime }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <i class="fa-regular fa-file mr-2"></i>
                        @endif
                        <div class="flex justify-between items-center space-x-2">
                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                class="text-blue-600 hover:underline mt-2">
                                {{ $file->name }}
                            </a>
                            <form action="{{ route('files.destroy', $file->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Tambah Folder -->
    <div id="addFolderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">New Folders</h3>
            <form action="{{ route('folders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                <input type="text" name="name" class="w-full border rounded-lg p-2 mb-2"
                    placeholder="Nama Folder">
                @error('name')
                    <p class="text-sm text-red-500 mb-2">{{ $message }}</p>
                @enderror
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('addFolderModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah File -->
    <div id="addFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">New Files</h3>
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                <input type="text" name="name" class="w-full border rounded-lg p-2 mb-4" placeholder="Files Name">
                <input type="file" name="file" class="w-full border rounded-lg p-2 mb-4">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('addFileModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleView(view) {
            let container = document.getElementById('itemContainer');
            if (view === 'grid') {
                container.classList.remove('flex', 'flex-col');
                container.classList.add('grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-2');
            } else {
                container.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-3');
                container.classList.add('flex', 'flex-col', 'gap-2');
            }
        }
    </script>
</x-app-layout>
