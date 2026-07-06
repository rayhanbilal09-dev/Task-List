<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-gray-800 dark:text-gray-200">
            Upload Foto Task
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $task->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                </div>

                @if($task->photo)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Foto Saat Ini:</p>
                        <img src="{{ asset('storage/' . $task->photo) }}" alt="Task Photo" class="max-w-md rounded-lg">
                    </div>
                @endif

                <form action="{{ route('tasks.store-photo', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pilih Foto</label>
                        <input
                            type="file"
                            name="photo"
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                dark:file:bg-blue-900 dark:file:text-blue-200">
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG, GIF (Maksimal 2MB)</p>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Upload Foto</button>
                    </div>

                    @if($errors->any())
                        <div class="text-sm text-red-600">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
