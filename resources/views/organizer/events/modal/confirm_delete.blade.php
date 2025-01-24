<!-- resources/views/organizer/events/modal/confirm_delete.blade.php -->

<div x-show="deleteModalOpen" 
     x-cloak 
     @click.away="deleteModalOpen = false" 
     {{-- x-init="$watch('deleteModalOpen', value => {
         if (value) {
             new Audio('/sounds/notification.mp3').play();
         }
     })" --}}
     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto">
    
    <!-- Backdrop with separate animation -->
    <div x-show="deleteModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-lg mx-auto bg-white shadow-lg rounded-3xl"
         x-show="deleteModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8">
        
        <!-- Close button -->
        <button @click="deleteModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="px-6 pt-8 pb-6 text-center">
            <!-- Warning Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Modal Content -->
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Delete Event</h3>
            <p class="text-gray-500 mb-8">
                Are you sure you want to delete this event? This action cannot be undone and all associated data will be permanently removed.
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <form :action="deleteFormAction" method="POST" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 w-32">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
                
                <button @click="deleteModalOpen = false" 
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200 w-32">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
