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

    <div class="max-w-screen-xl mx-auto p-6">
        <div class="space-y-4">
            <a href="{{ route('dashboard.index') }}"
                class="font-semibold text-m text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fa-solid fa-house"></i>
            </a>

            @if ($breadcrumb->isNotEmpty())
                <span class="mx-2 dark:text-white">/</span>
                @foreach ($breadcrumb as $crumb)
                    <a href="{{ $crumb['url'] }}"
                        class="font-semibold text-m text-gray-800 underline dark:text-gray-200 leading-tight">
                        {{ $crumb['name'] }}
                    </a>
                    @if (!$loop->last)
                        <span class="mx-2 dark:text-white">/</span>
                    @endif
                @endforeach
            @endif

            <form action="{{ route('files.updateContent', $file->uuid) }}" method="POST">
                @csrf
                <textarea id="editor" name="content">{{ $content }}</textarea>

                <div class="mt-4 flex gap-x-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                    <button type="button" onclick="runCode()"
                        class="bg-green-500 text-white px-4 py-2 rounded">Preview</button>
                </div>
            </form>

            <div class="mt-4 border p-2">
                <iframe id="previewFrame" class="w-full h-[400px] border"></iframe>
            </div>
        </div>
    </div>

    <!-- Load CodeMirror + Extensions -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/dracula.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>

    <!-- Mode yang diperlukan oleh htmlmixed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/php/php.min.js"></script>

    <!-- Addons yang dibutuhkan -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/edit/closebrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/hint/show-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/hint/javascript-hint.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/fold/xml-fold.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/addon/edit/matchtags.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var mimeType = "{{ $file->mime_type }}";
            var modeMap = {
                'text/html': 'htmlmixed',
                'text/css': 'css',
                'application/javascript': 'javascript',
                'text/javascript': 'javascript',
                'application/x-httpd-php': 'php',
                'text/x-php': 'php'
            };

            var mode = modeMap[mimeType] || 'text';

            var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
                lineNumbers: true,
                mode: "htmlmixed",
                theme: "dracula",
                tabSize: 4,
                indentWithTabs: true,
                autoCloseBrackets: true,
                autoCloseTags: true,
                matchBrackets: true,
                matchTags: {
                    bothTags: true
                }, // Pastikan ini benar
                extraKeys: {
                    "Ctrl-Space": "autocomplete"
                },
                lineWrapping: true,
            });

            editor.setSize("100%", "400px");

            // Simpan editor ke global variable
            window.editor = editor;
        });

        function runCode() {
            var code = window.editor.getValue();
            var previewFrame = document.getElementById('previewFrame');
            var previewDocument = previewFrame.contentDocument || previewFrame.contentWindow.document;

            // Ambil path folder dari file yang sedang diedit
            var basePath = "{{ asset('storage/' . dirname($file->path)) }}";

            // Buat tag <base> agar semua path relatif sesuai
            var baseTag = `<base href="${basePath}/">`;

            // Tambahkan semua file CSS di folder yang sama
            var cssLinks = `
            @foreach (Storage::files('public/' . dirname($file->path)) as $cssFile)
                @if (Str::endsWith($cssFile, '.css'))
                    <link rel="stylesheet" type="text/css" href="{{ asset(str_replace('public/', 'storage/', $cssFile)) }}">
                @endif
            @endforeach
            `;

            // Tambahkan semua file JS di folder yang sama
            var jsScripts = `
            @foreach (Storage::files('public/' . dirname($file->path)) as $jsFile)
                @if (Str::endsWith($jsFile, '.js'))
                    <script src="{{ asset(str_replace('public/', 'storage/', $jsFile)) }}"><\/script>
                @endif
            @endforeach
            `;

            // Gabungkan semua elemen dan masukkan ke iframe
            previewDocument.open();
            previewDocument.write(`<!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                ${baseTag}
                ${cssLinks}
            </head>
            <body>
                ${code}
                ${jsScripts}
            </body>
            </html>`);
            previewDocument.close();
        }
    </script>
</x-app-layout>
