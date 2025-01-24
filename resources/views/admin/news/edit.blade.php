{{-- <!doctype html>
<html class="h-full bg-purple-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
    <div class="min-h-full">
        @include('admin.layouts.admin_nav') <!-- Including the navigation bar -->

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit News</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
                <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                    <!-- Title and Description -->
                    <div class="mb-10 text-center">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Edit News</h2>
                        <p class="text-gray-600">Update the details below to edit your news article</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.news.update', $news->news_id) }}" method="POST" class="max-w-4xl mx-auto space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- News Title -->
                        <div class="relative">
                            <label for="news_title" class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
                            <input type="text" name="news_title" id="news_title" value="{{ $news->news_title }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                        </div>

                        <!-- News Details -->
                        <div class="relative">
                            <label for="news_details" class="block text-sm font-medium text-gray-700 mb-1">Details:</label>
                            <textarea name="news_details" id="news_details" rows="6" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">{{ $news->news_details }}</textarea>
                        </div>

                        <!-- News Tag -->
                        <div class="relative">
                            <label for="news_tag" class="block text-sm font-medium text-gray-700 mb-1">Tag:</label>
                            <select name="news_tag" id="news_tag" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                                <option value="Update" {{ $news->news_tag == 'Update' ? 'selected' : '' }}>Update</option>
                                <option value="Maintenance" {{ $news->news_tag == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Bugs" {{ $news->news_tag == 'Bugs' ? 'selected' : '' }}>Bugs</option>
                            </select>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.news.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Update News
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    @include('layouts.footer') <!-- Including the footer -->
</body>
</html> --}}

<x-admin-layout>
    <x-slot:title>
        Edit News
    </x-slot>

    <x-slot:header>
        Edit News
    </x-slot>

    <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
        <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
            <!-- Title and Description -->
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Edit News</h2>
                <p class="text-gray-600">Update the details below to edit your news article</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.news.update', $news->news_id) }}" method="POST" class="max-w-4xl mx-auto space-y-8">
                @csrf
                @method('PUT')

                <!-- News Title -->
                <div class="relative">
                    <label for="news_title" class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
                    <input type="text" name="news_title" id="news_title" value="{{ $news->news_title }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                </div>

                <!-- News Details -->
                <div class="relative">
                    <label for="news_details" class="block text-sm font-medium text-gray-700 mb-1">Details:</label>
                    <textarea name="news_details" id="news_details" rows="6" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">{{ $news->news_details }}</textarea>
                </div>

                <!-- News Tag -->
                <div class="relative">
                    <label for="news_tag" class="block text-sm font-medium text-gray-700 mb-1">Tag:</label>
                    <select name="news_tag" id="news_tag" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm">
                        <option value="Update" {{ $news->news_tag == 'Update' ? 'selected' : '' }}>Update</option>
                        <option value="Maintenance" {{ $news->news_tag == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Bugs" {{ $news->news_tag == 'Bugs' ? 'selected' : '' }}>Bugs</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.news.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Update News
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>