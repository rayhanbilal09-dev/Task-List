<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-gray-800 dark:text-gray-200">
            Daftar Task
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 rounded bg-green-100 dark:bg-green-900 p-3 text-green-700 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded bg-red-100 dark:bg-red-900 p-3 text-red-700 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Task</h3>
                    @if(auth()->check() && auth()->user()->name === 'Admin')
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">+ Tambah Task</a>
                    @endif
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">No</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Judul</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deskripsi</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Foto</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tasks as $task)
                                <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $task->user?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $task->title }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($task->description, 120) }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($task->photo)
                                                <button onclick="showPhoto('{{ asset('storage/' . $task->photo) }}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    📷 Lihat Foto
                                                </button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($task->status)
                                                @if($task->confirmed)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-900 dark:bg-green-900 dark:text-green-200">✅ Terkonfirmasi</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">✅ Selesai</span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">⏳ Belum Selesai</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-sm rounded-md hover:bg-gray-200">Edit</a>

                                                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-indigo-100 dark:bg-indigo-800 text-sm rounded-md hover:bg-indigo-200">Ubah Status</button>
                                                    </form>

                                                    @if(auth()->check() && auth()->user()->name === 'Admin' && $task->status && !$task->confirmed)
                                                        <form action="{{ route('tasks.confirm', $task) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-emerald-100 dark:bg-emerald-800 text-sm rounded-md hover:bg-emerald-200">Konfirmasi</button>
                                                        </form>
                                                    @endif

                                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus task ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-800 text-sm rounded-md hover:bg-red-200 text-red-700 dark:text-red-200">Hapus</button>
                                                    </form>
                                                </div>
                                                @if(auth()->check() && auth()->user()->name === 'Rayhan')
                                                    <a href="{{ route('tasks.upload-photo', $task) }}" class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-800 text-sm rounded-md hover:bg-purple-200 text-purple-700 dark:text-purple-200">📷 Upload Foto</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">Belum ada task.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <!-- Photo Modal -->
    <div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Foto Task</h3>
                <button onclick="closePhoto()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">✕</button>
            </div>
            <img id="photoImage" src="" alt="Task Photo" class="w-full h-auto rounded-lg">
        </div>
    </div>

    <script>
        function showPhoto(photoUrl) {
            document.getElementById('photoImage').src = photoUrl;
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhoto() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhoto();
            }
        });
    </script>

</x-app-layout>