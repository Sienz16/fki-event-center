{{-- <!doctype html>
<html class="h-full bg-purple-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Post</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    [x-cloak] {
        display: none !important;
    }
  </style>
</head>
<body class="h-full">
<div class="min-h-full" x-data="imageUpload">
    @include('organizer.layouts.organizer_nav')  <!-- Including the navigation bar -->

    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create New Post</h1>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <!-- Title and Description -->
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Forum Post</h2>
                    <p class="text-gray-600">Share your thoughts with the community</p>
                </div>

                <form action="{{ route('organizer.community.store') }}" method="POST" enctype="multipart/form-data" 
                      class="max-w-4xl mx-auto space-y-8"
                      x-data="imageUpload">
                    @csrf

                    <!-- Image Upload Section -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Post Image</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg transition-colors duration-200"
                             :class="{ 'border-purple-400 bg-purple-50': isHovered }"
                             @dragover.prevent="isHovered = true"
                             @dragleave.prevent="isHovered = false"
                             @drop.prevent="handleDrop($event)">

                            <div class="space-y-2 text-center">
                                <!-- Upload UI -->
                                <div x-show="!hasImage" class="flex flex-col items-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="img" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload an image</span>
                                            <input id="img" name="img" type="file" class="sr-only" accept="image/*"
                                                   @change="handleFileSelect($event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                </div>

                                <!-- Image Preview -->
                                <div x-show="hasImage" class="relative group">
                                    <img id="preview" 
                                         class="max-h-64 rounded-lg object-contain mx-auto" 
                                         alt="Post image preview" />
                                    
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            @click="removeImage()"
                                            class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('img')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="relative">
                        <label for="desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="desc" 
                                 name="desc" 
                                 rows="6" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                 placeholder="Share your thoughts..."></textarea>
                        @error('desc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Create Post
                            <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Add this script before closing body tag -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageUpload', () => ({
        hasImage: false,
        isHovered: false,

        handleFileSelect(event) {
            const file = event.target.files[0];
            this.displayPreview(file);
        },

        handleDrop(event) {
            this.isHovered = false;
            const file = event.dataTransfer.files[0];
            
            if (file && file.type.startsWith('image/')) {
                // Set the file to the input element
                const input = document.getElementById('img');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                
                this.displayPreview(file);
            }
        },

        displayPreview(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('preview').src = e.target.result;
                    this.hasImage = true;
                };
                reader.readAsDataURL(file);
            }
        },

        removeImage() {
            this.hasImage = false;
            document.getElementById('preview').src = '';
            document.getElementById('img').value = '';
        }
    }));
});
</script>
</body>
@include('layouts.footer')  <!-- Including the footer -->
</html> --}}

<x-organizer-layout>
    <x-slot:title>
        Create New Post
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create New Post</h1>
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8" x-data="imageUpload">
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-4 lg:px-6">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <!-- Title and Description -->
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Forum Post</h2>
                    <p class="text-gray-600">Share your thoughts with the community</p>
                </div>

                <form action="{{ route('organizer.community.store') }}" method="POST" enctype="multipart/form-data" 
                      class="max-w-4xl mx-auto space-y-8"
                      x-data="imageUpload">
                    @csrf

                    <!-- Image Upload Section -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Post Image</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg transition-colors duration-200"
                             :class="{ 'border-purple-400 bg-purple-50': isHovered }"
                             @dragover.prevent="isHovered = true"
                             @dragleave.prevent="isHovered = false"
                             @drop.prevent="handleDrop($event)">

                            <div class="space-y-2 text-center">
                                <!-- Upload UI -->
                                <div x-show="!hasImage" class="flex flex-col items-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="img" class="relative cursor-pointer rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload an image</span>
                                            <input id="img" name="img" type="file" class="sr-only" accept="image/*"
                                                   @change="handleFileSelect($event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 10MB</p>
                                </div>

                                <!-- Image Preview -->
                                <div x-show="hasImage" class="relative group">
                                    <img id="preview" 
                                         class="max-h-64 rounded-lg object-contain mx-auto" 
                                         alt="Post image preview" />
                                    
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            @click="removeImage()"
                                            class="absolute top-2 right-2 p-1.5 rounded-full bg-red-100 text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('img')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="relative">
                        <label for="desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="desc" 
                                 name="desc" 
                                 rows="6" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                 placeholder="Share your thoughts..."></textarea>
                        @error('desc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Create Post
                            <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUpload', () => ({
                hasImage: false,
                isHovered: false,

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    this.displayPreview(file);
                },

                handleDrop(event) {
                    this.isHovered = false;
                    const file = event.dataTransfer.files[0];
                    
                    if (file && file.type.startsWith('image/')) {
                        const input = document.getElementById('img');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        
                        this.displayPreview(file);
                    }
                },

                displayPreview(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            document.getElementById('preview').src = e.target.result;
                            this.hasImage = true;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeImage() {
                    this.hasImage = false;
                    document.getElementById('preview').src = '';
                    document.getElementById('img').value = '';
                }
            }));
        });
    </script>

    <x-toast />
</x-organizer-layout>