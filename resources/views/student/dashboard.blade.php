{{-- <!doctype html>
<html class="h-full bg-purple-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
<div class="min-h-full">
    @include('student.layouts.student_nav')  <!-- Including the navigation bar -->
  
    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
      </div>
    </header>
    
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-white py-16 sm:py-20 shadow-lg sm:rounded-lg">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    {{-- <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Your Registered Upcoming Events</h2>
                        <p class="mt-1 text-lg leading-8 text-gray-600">Don't miss the events you're registered for!</p>
                    </div> --}}

                    {{-- <!-- Check if there are registered upcoming events -->
                    @if($events->isEmpty())
                        <!-- No Upcoming Events Message -->
                        <div class="mx-auto mt-10 text-center text-gray-600">
                            <p class="text-lg">You have no upcoming events at the moment. Please check back later!</p>
                        </div>
                    @else
                        <!-- Container with grey background and rounded corners -->
                        <div class="bg-gray-100 p-6 rounded-lg mt-10">
                            <!-- Events List -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($events as $event)
                                    <div class="bg-[#F8F7F4] p-4 rounded-lg shadow-lg flex flex-col justify-between">
                                        <!-- Event Image -->
                                        @if($event->event_img)
                                            <img src="{{ asset('storage/' . $event->event_img) }}" alt="{{ $event->event_name }}" class="w-full h-80 object-cover rounded-lg mb-4">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder Image" class="w-full h-80 object-cover rounded-lg mb-4">
                                        @endif
                                        
                                        <!-- Event Information -->
                                        <div class="flex-grow">
                                            <h2 class="text-2xl font-bold text-black mb-2">{{ $event->event_name }}</h2>
                                            <p class="text-gray-600 dark:text-gray-400 mb-2">{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y, g:i a') }}</p>
                                            <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $event->event_venue }}</p>

                                            <!-- Display if the user is registered -->
                                            <p class="text-green-600 font-bold mb-4">Registered</p>
                                        </div>

                                        <!-- Last Updated Info -->
                                        <div class="text-gray-500 p-1 sm:p-1 text-sm mt-4">
                                            <p>Last updated: {{ $event->updated_at->diffForHumans() }}</p>
                                        </div>

                                        <!-- View Details Button -->
                                        <a href="{{ route('student.events.show', $event->event_id) }}" 
                                           class="mt-auto bg-blue-600 text-white rounded-lg px-4 py-2 border border-blue-700 hover:bg-blue-700 text-center">
                                           {{ __('View Details') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Horizontal Rule -->
                    <hr class="border-gray-300 mt-10 mb-20"> 

                    <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Latest News!</h2>
                        <p class="mt-1 text-lg leading-8 text-gray-600">Learn what's new in the system.</p>
                    </div>

                    <!-- News Articles Grid -->
                    <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                        @foreach($news as $item)
                            <article class="flex max-w-xl flex-col items-start justify-between">
                                <div class="flex items-center gap-x-4 text-xs">
                                    <!-- Date of the news -->
                                    <time datetime="{{ $item->date->format('Y-m-d') }}" class="text-gray-500">{{ $item->date->format('M d, Y') }}</time>
                                    
                                    <!-- Tag (Update, Maintenance, Bugs) -->
                                    @php
                                        $tagClasses = '';
                                        switch ($item->news_tag) {
                                            case 'Update':
                                                $tagClasses = 'bg-green-50 text-green-600 hover:bg-green-100';
                                                break;
                                            case 'Maintenance':
                                                $tagClasses = 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100';
                                                break;
                                            case 'Bugs':
                                                $tagClasses = 'bg-red-50 text-red-600 hover:bg-red-100';
                                                break;
                                            default:
                                                $tagClasses = 'bg-gray-50 text-gray-600 hover:bg-gray-100';
                                                break;
                                        }
                                    @endphp
                                    
                                    <a href="#" class="relative z-10 rounded-full px-3 py-1.5 font-medium {{ $tagClasses }}">
                                        {{ $item->news_tag }}
                                    </a>
                                </div>

                                <!-- News Title and Details -->
                                <div class="group relative">
                                    <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                        <a href="#">
                                            <span class="absolute inset-0"></span>
                                            {{ $item->news_title }}
                                        </a>
                                    </h3>
                                    <p class="mt-5 text-sm leading-6 text-gray-600">
                                        {!! nl2br(e(Str::limit($item->news_details, 150))) !!}
                                    </p>
                                </div>

                                <!-- Admin Info (Author) -->
                                <div class="relative mt-8 flex items-center gap-x-4">
                                    <img src="{{ $item->admin->manage_img ? asset('storage/' . $item->admin->manage_img) : 'https://via.placeholder.com/150' }}" alt="Profile Image" class="h-10 w-10 rounded-full bg-gray-50">
                                    <div class="text-sm leading-6">
                                        <p class="font-semibold text-gray-900">
                                            <a href="#">
                                                <span class="absolute inset-0"></span>
                                                {{ $item->admin->manage_name }}
                                            </a>
                                        </p>
                                        <p class="text-gray-600">{{ $item->admin->manage_position }}</p>
                                        <p class="text-gray-500">Posted {{ $item->date->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>         
      </div>
    </main>
  </div>
</body>
@include('layouts.footer')  <!-- Including the footer -->
</html> --}}

<x-student-layout>
    <x-slot:title>
        Student Dashboard
    </x-slot>

    <x-slot:header>
        Dashboard
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <div class="bg-white py-16 sm:py-20 shadow-lg sm:rounded-lg">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Latest News!</h2>
                    <p class="mt-1 text-lg leading-8 text-gray-600">Learn what's new in the system.</p>
                </div>

                <!-- News Articles Grid -->
                <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @foreach($news as $item)
                        <article class="flex max-w-xl flex-col items-start justify-between">
                            <div class="flex items-center gap-x-4 text-xs">
                                <!-- Date of the news -->
                                <time datetime="{{ $item->date->format('Y-m-d') }}" class="text-gray-500">
                                    {{ $item->date->format('M d, Y') }}
                                </time>
                                
                                <!-- Tag (Update, Maintenance, Bugs) -->
                                @php
                                    $tagClasses = '';
                                    switch ($item->news_tag) {
                                        case 'Update':
                                            $tagClasses = 'bg-green-50 text-green-600 hover:bg-green-100';
                                            break;
                                        case 'Maintenance':
                                            $tagClasses = 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100';
                                            break;
                                        case 'Bugs':
                                            $tagClasses = 'bg-red-50 text-red-600 hover:bg-red-100';
                                            break;
                                        default:
                                            $tagClasses = 'bg-gray-50 text-gray-600 hover:bg-gray-100';
                                            break;
                                    }
                                @endphp
                                
                                <a href="#" class="relative z-10 rounded-full px-3 py-1.5 font-medium {{ $tagClasses }}">
                                    {{ $item->news_tag }}
                                </a>
                            </div>

                            <!-- News Title and Details -->
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                                    <a href="#">
                                        <span class="absolute inset-0"></span>
                                        {{ $item->news_title }}
                                    </a>
                                </h3>
                                <p class="mt-5 text-sm leading-6 text-gray-600">
                                    {!! nl2br(e(Str::limit($item->news_details, 150))) !!}
                                </p>
                            </div>

                            <!-- Admin Info (Author) -->
                            <div class="relative mt-8 flex items-center gap-x-4">
                                <img src="{{ $item->admin->manage_img ? asset('storage/' . $item->admin->manage_img) : 'https://via.placeholder.com/150' }}" 
                                     alt="Profile Image" 
                                     class="h-10 w-10 rounded-full bg-gray-50">
                                <div class="text-sm leading-6">
                                    <p class="font-semibold text-gray-900">
                                        <a href="#">
                                            <span class="absolute inset-0"></span>
                                            {{ $item->admin->manage_name }}
                                        </a>
                                    </p>
                                    <p class="text-gray-600">{{ $item->admin->manage_position }}</p>
                                    <p class="text-gray-500">Posted {{ $item->date->diffForHumans() }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-student-layout>