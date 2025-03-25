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
            <div class="bg-white shadow rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold mb-2">Storage Usage</h3>
                <div class="relative w-full bg-gray-200 rounded-lg h-6">
                    @php
                        $totalStorage = 5 * 1024 * 1024 * 1024;
                        $usedStorage = \App\Models\File::sum('size');
                        $usagePercentage = ($usedStorage / $totalStorage) * 100;
                    @endphp
                    <div class="absolute top-0 left-0 h-6 bg-blue-600 rounded-lg"
                        style="width: {{ $usagePercentage }}%;">
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    {{ number_format($usedStorage / (1024 * 1024), 2) }} MB dari
                    {{ number_format($totalStorage / (1024 * 1024), 2) }} MB digunakan.
                </p>
            </div>

            <div class="flex justify-between items-start">
                <div class="bg-white shadow rounded-lg p-2 md:flex hidden space-x-2">
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
                <div>
                    @foreach ($subfolders as $subfolder)
                        <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <i class="fa-regular fa-folder mr-2"></i>
                                <a href="{{ route('dashboard.show', $subfolder->uuid) }}"
                                    class="font-medium text-blue-600 hover:underline">
                                    {{ $subfolder->name }}
                                </a>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="editFolderName('{{ $subfolder->id }}', '{{ $subfolder->name }}')"
                                    class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <form action="{{ route('folders.destroy', $subfolder->id) }}" method="POST">
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
                        <div class="flex justify-between items-center space-x-2 mt-2">
                            <div>
                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank"
                                    class="text-blue-600 hover:underline mt-2">
                                    {{ $file->name }}
                                </a>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="editFileName('{{ $file->id }}', '{{ $file->name }}')"
                                    class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <form action="{{ route('files.destroy', $file->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Edit File -->
    <div id="editFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Edit File Name</h3>
            <form id="editFileForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editFileName" class="w-full border rounded-lg p-2 mb-2"
                    placeholder="New File Name">
                <input type="hidden" name="file_id" id="editFileId">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('editFileModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Folders -->
    <div id="editFolderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Folders Name</h3>
            <form id="editFolderForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editFolderName" class="w-full border rounded-lg p-2 mb-2"
                    placeholder="New Folder Name">
                <input type="hidden" name="folder_id" id="editFolderId">
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        onclick="document.getElementById('editFolderModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
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
                {{-- <input type="text" name="name" class="w-full border rounded-lg p-2 mb-4" placeholder="Files Name"> --}}
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

    <script>
        function toggleView(view) {
            let container = document.getElementById('itemContainer');
            if (view === 'grid') {
                container.classList.remove('flex', 'flex-col');
                container.classList.add('grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4');
            } else {
                container.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-4');
                container.classList.add('flex', 'flex-col', 'gap-4');
            }
        }

        function editFileName(id, name) {
            document.getElementById('editFileName').value = name;
            document.getElementById('editFileId').value = id;
            document.getElementById('editFileForm').action = `/files/${id}`;
            document.getElementById('editFileModal').classList.remove('hidden');
        }

        function editFolderName(id, name) {
            document.getElementById('editFolderName').value = name;
            document.getElementById('editFolderId').value = id;
            document.getElementById('editFolderForm').action = `/folders/${id}`;
            document.getElementById('editFolderModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
