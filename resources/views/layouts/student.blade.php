<x-app-layout>
    <x-slot:title>
        {{ $title ?? 'Student' }}
    </x-slot>

    <div class="min-h-full bg-purple-100">
        @include('student.layouts.student_nav')

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $header }}</h1>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>
        
        <x-toast />

        @include('layouts.footer')
    </div>
</x-app-layout>