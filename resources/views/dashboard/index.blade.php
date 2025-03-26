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
            <div class="bg-white dark:bg-gray-800 dark:text-white shadow rounded-lg p-4 mb-4">
                <h3 class="text-lg font-semibold mb-2">Storage Usage</h3>
                <div class="relative w-full bg-gray-200 rounded-lg h-6">
                    @php
                        $totalStorage = 5 * 1024 * 1024 * 1024;
                        $usedStorage = \App\Models\File::sum('size');
                        $usagePercentage = ($usedStorage / $totalStorage) * 100;
                    @endphp
                    <div class="absolute top-0 left-0 h-6 bg-blue-600 rounded-lg" style="width: {{ $usagePercentage }}%;">
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    {{ number_format($usedStorage / (1024 * 1024), 2) }} MB dari
                    {{ number_format($totalStorage / (1024 * 1024), 2) }} MB digunakan.
                </p>
            </div>

            <div class="flex justify-between items-center">
                <!-- Tombol Grid/List -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-2 md:flex hidden space-x-2">
                    <button onclick="toggleView('grid')" id="gridBtn"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-border-all"></i>
                    </button>
                    <button onclick="toggleView('list')" id="listBtn"
                        class="px-2 py-1 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-800 transition">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>

                <!-- Tombol Tambah -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-2">
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
                        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 rounded-lg shadow">
                            @if (isset($item->path))
                                {{-- Jika item adalah file --}}
                                @if (str_starts_with($item->mime_type, 'image'))
                                    {{-- <i class="fa-regular fa-file text-gray-600"></i> --}}
                                    <img src="{{ asset('storage/' . $item->path) }}"
                                        class="w-full object-cover rounded-lg mb-2" />
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ Str::limit($item->name, 20) }}
                                        </a>
                                        <div class="flex space-x-2">
                                            <button onclick="editFileName('{{ $item->id }}', '{{ $item->name }}')"
                                                class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <form action="{{ route('files.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif (str_starts_with($item->mime_type, 'audio'))
                                    <audio controls class="w-full mb-2">
                                        <source src="{{ asset('storage/' . $item->path) }}"
                                            type="{{ $item->mime_type }}">
                                    </audio>
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ Str::limit($item->name, 20) }}
                                        </a>
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="editFileName('{{ $item->id }}', '{{ $item->name }}')"
                                                class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <form action="{{ route('files.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif (str_starts_with($item->mime_type, 'video'))
                                    <video controls class="w-full rounded-lg mb-2">
                                        <source src="{{ asset('storage/' . $item->path) }}"
                                            type="{{ $item->mime_type }}">
                                    </video>
                                    <div class="flex justify-between items-start mt-2">
                                        <a href="{{ asset('storage/' . $item->path) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            {{ Str::limit($item->name, 20) }}
                                        </a>
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="editFileName('{{ $item->id }}', '{{ $item->name }}')"
                                                class="text-yellow-500 hover:text-yellow-700">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            <form action="{{ route('files.destroy', $item->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="flex justify-between">
                                    <div>
                                        @if ($item instanceof \App\Models\Folder)
                                            @if ($item->files->count() > 0 || $item->subfolders->count() > 0)
                                                <i class="fa-solid fa-folder mr-2"></i>
                                            @else
                                                <i class="fa-regular fa-folder mr-2"></i>
                                            @endif
                                        @endif
                                        <a href="{{ route('dashboard.show', $item->uuid) }}"
                                            class="text-blue-600 hover:underline">
                                            {{ Str::limit($item->name, 20) }}
                                        </a>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editFolderName('{{ $item->id }}', '{{ $item->name }}')"
                                            class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
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
        <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">New Folder</h3>
            <form action="{{ route('folders.store') }}" method="POST">
                @csrf
                <input type="text" name="name"
                    class="w-full border rounded-lg p-2 mb-2 dark:bg-gray-700 dark:text-white"
                    placeholder="Folder Name">
                @error('name')
                    <p class="text-sm text-red-500 mb-2">{{ $message }}</p>
                @enderror
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('addFolderModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah File -->
    <div id="addFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Upload File</h3>
            <form id="uploadForm" action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="fileInput"
                    class="w-full border rounded-lg p-2 mb-2 dark:bg-gray-700 dark:text-white">
                @error('file')
                    <p class="text-sm text-red-500 mb-2">{{ $message }}</p>
                @enderror

                <!-- Progress Bar -->
                <div id="progressContainer" class="hidden mt-2">
                    <div class="relative w-full bg-gray-200 rounded-lg h-4 dark:bg-gray-700">
                        <div id="progressBar" class="absolute top-0 left-0 h-4 bg-blue-600 rounded-lg w-0"></div>
                    </div>
                    <p id="progressText" class="text-sm text-gray-600 dark:text-gray-300 mt-1 text-center">0%</p>
                    <p id="timeRemaining" class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1"></p>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="document.getElementById('addFileModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-lg">Cancel</button>
                    <button type="submit" id="uploadButton"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit File -->
    <div id="editFileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Edit File Name</h3>
            <form id="editFileForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editFileName"
                    class="w-full border rounded-lg p-2 mb-2 dark:bg-gray-700 dark:text-white"
                    placeholder="New File Name">
                <input type="hidden" name="file_id" id="editFileId">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('editFileModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Folders -->
    <div id="editFolderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Edit Folders Name</h3>
            <form id="editFolderForm" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editFolderName"
                    class="w-full border rounded-lg p-2 mb-2 dark:bg-gray-700 dark:text-white"
                    placeholder="New Folder Name">
                <input type="hidden" name="folder_id" id="editFolderId">
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        onclick="document.getElementById('editFolderModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-lg">Cancel</button>
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

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let fileInput = document.getElementById('fileInput');
            if (fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'info',
                    text: 'Pilih file terlebih dahulu.'
                });
                return;
            }

            let formData = new FormData(this);
            let xhr = new XMLHttpRequest();
            let progressBar = document.getElementById('progressBar');
            let progressText = document.getElementById('progressText');
            let timeRemaining = document.getElementById('timeRemaining');
            let progressContainer = document.getElementById('progressContainer');
            let uploadButton = document.getElementById('uploadButton');

            progressContainer.classList.remove('hidden');
            uploadButton.disabled = true;
            uploadButton.innerText = "Uploading...";

            let startTime = new Date().getTime();

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    let percent = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percent + "%";
                    progressText.innerText = percent + "%";

                    // Estimasi waktu sisa
                    let elapsedTime = (new Date().getTime() - startTime) / 1000;
                    let uploadSpeed = event.loaded / elapsedTime;
                    let remainingTime = (event.total - event.loaded) / uploadSpeed;

                    if (remainingTime > 1) {
                        timeRemaining.innerText = "Sisa waktu: " + Math.round(remainingTime) + " detik";
                    } else {
                        timeRemaining.innerText = "Menyelesaikan...";
                    }
                }
            };

            xhr.onload = function() {
                if (xhr.status == 200) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: 'Upload berhasil!',
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: 'Terjadi kesalahan saat upload.',
                    });
                }
                uploadButton.disabled = false;
                uploadButton.innerText = "Upload";
            };

            xhr.onerror = function() {
                Swal.fire({
                    icon: 'error',
                    text: 'Gagal mengunggah file.'
                });
                uploadButton.disabled = false;
                uploadButton.innerText = "Upload";
            };

            xhr.open('POST', this.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
            xhr.send(formData);
        });
    </script>
</x-app-layout>
