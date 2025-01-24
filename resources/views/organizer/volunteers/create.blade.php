<!doctype html>
<html class="h-full bg-purple-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Volunteer Request</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
<div class="min-h-full">
    @include('organizer.layouts.organizer_nav')  <!-- Including the navigation bar -->

    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create Volunteer Request</h1>
      </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                <form action="{{ route('organizer.volunteers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="event_id" class="block text-sm font-medium text-gray-700">Event</label>
                        <select id="event_id" name="event_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">Select Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>                    

                    <div class="mb-4">
                        <label for="volunteer_capacity" class="block text-sm font-medium text-gray-700">Volunteer Capacity</label>
                        <input type="number" id="volunteer_capacity" name="volunteer_capacity" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required min="1">
                        @error('volunteer_capacity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white rounded-lg px-4 py-2 border border-purple-700 hover:bg-purple-700">
                            Save Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@include('layouts.footer')  <!-- Including the footer -->
</body>
</html>
