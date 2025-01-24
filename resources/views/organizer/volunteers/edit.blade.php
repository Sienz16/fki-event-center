<!doctype html>
<html class="h-full bg-purple-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Volunteer Request</title>
  @vite('resources/css/app.css')
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full">
<div class="min-h-full">
    @include('organizer.layouts.organizer_nav')  <!-- Including the navigation bar -->

    <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Volunteer Request</h1>
      </div>
    </header>

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="border border-gray-300 p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                <!-- Update Form -->
                <form action="{{ route('organizer.volunteers.update', $volunteer->volunteer_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Display the event name, but make it non-editable with a gray background -->
                    <div class="mb-4">
                        <label for="event_name" class="block text-sm font-medium text-gray-700">Event</label>
                        <input type="text" id="event_name" name="event_name" class="mt-1 block w-full border-purple-300 rounded-lg shadow-sm bg-purple-200 text-gray-600" 
                            value="{{ $volunteer->event->event_name }}" disabled> <!-- Non-editable event name with gray background -->

                        <!-- Hidden field for the event ID -->
                        <input type="hidden" id="event_id" name="event_id" value="{{ $volunteer->event->event_id }}">
                        @error('event_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="mb-4">
                        <label for="volunteer_capacity" class="block text-sm font-medium text-gray-700">Volunteer Capacity</label>
                        <input type="number" id="volunteer_capacity" name="volunteer_capacity" value="{{ $volunteer->volunteer_capacity }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required min="1">
                        @error('volunteer_capacity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">{{ $volunteer->notes }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <!-- Button Section -->
                    <div class="flex justify-end space-x-4">
                        <button type="submit" class="bg-purple-600 text-white rounded-lg px-4 py-2 border border-purple-700 hover:bg-purple-700">
                            Update
                        </button>
                </form>       
                    <!-- Delete Form -->
                    <form action="{{ route('organizer.volunteers.destroy', $volunteer->volunteer_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white rounded-lg px-4 py-2 border border-red-700 hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>         
        </div>
    </main>
</div>
@include('layouts.footer')  <!-- Including the footer -->
</body>
</html>