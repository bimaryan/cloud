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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-screen-xl mx-auto p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <!-- Tombol Grid/List -->
                <div class="bg-white shadow rounded-lg p-2 md:flex hidden space-x-2">
                    <button onclick="toggleView('grid')" id="gridBtn"
                        class="px-3 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-border-all"></i>
                    </button>
                    <button onclick="toggleView('list')" id="listBtn"
                        class="px-3 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>

                <!-- Tombol Tambah -->
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

            <!-- Grid Folder dan File-->
            <div id="itemContainer" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach ($items as $item)
                    <div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            @if (isset($item->path))
                                {{-- Jika item adalah file --}}
                                @if (str_starts_with($item->mime_type, 'image'))
                                    {{-- <i class="fa-regular fa-file text-gray-600"></i> --}}
                                    <img src="{{ asset('storage/' . $item->path) }}"
                                        class="w-full object-cover rounded-lg mb-2" />
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $item->name }}
                                        </a>
                                        <form
                                            action="{{ isset($item->path) ? route('files.destroy', $item->id) : route('folders.destroy', $item->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif (str_starts_with($item->mime_type, 'audio'))
                                    <audio controls class="w-full mb-2">
                                        <source src="{{ asset('storage/' . $item->path) }}"
                                            type="{{ $item->mime_type }}">
                                    </audio>
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $item->name }}
                                        </a>
                                        <form
                                            action="{{ isset($item->path) ? route('files.destroy', $item->id) : route('folders.destroy', $item->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif (str_starts_with($item->mime_type, 'video'))
                                    <video controls class="w-full rounded-lg mb-2">
                                        <source src="{{ asset('storage/' . $item->path) }}"
                                            type="{{ $item->mime_type }}">
                                    </video>
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ $item->name }}
                                        </a>
                                        <form
                                            action="{{ isset($item->path) ? route('files.destroy', $item->id) : route('folders.destroy', $item->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <div class="flex justify-between">
                                    <div>
                                        <i class="fa-regular fa-folder text-yellow-500"></i>
                                        <a href="{{ route('dashboard.show', $item->uuid) }}"
                                            class="text-blue-600 hover:underline">
                                            {{ $item->name }}
                                        </a>
                                    </div>
                                    <div>
                                        <form
                                            action="{{ isset($item->path) ? route('files.destroy', $item->id) : route('folders.destroy', $item->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Tambah Folder -->
    <div id="addFolderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">New Folder</h3>
            <form action="{{ route('folders.store') }}" method="POST">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2 mb-2"
                    placeholder="Folder Name">
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
            <h3 class="text-lg font-semibold mb-4">Upload File</h3>
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2 mb-4" placeholder="File Name">
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
                container.classList.add('grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4');
            } else {
                container.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-4');
                container.classList.add('flex', 'flex-col', 'gap-4');
            }
        }
    </script>
</x-app-layout>
