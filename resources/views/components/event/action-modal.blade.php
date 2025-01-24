<div x-show="actionModalOpen" 
     x-cloak 
     @click.away="actionModalOpen = false" 
     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="actionModalOpen"
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
         x-show="actionModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8">
        
        <button @click="actionModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="px-6 pt-8 pb-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $iconBg ?? 'bg-purple-100' }} mb-6">
                {{ $icon }}
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $title }}</h3>
            <p class="text-gray-500 mb-8">{{ $message }}</p>

            <div class="flex justify-center space-x-4">
                <button wire:click="updateEventStatus"
                        class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white {{ $confirmButtonClass ?? 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 w-32">
                    {{ $confirmButton }}
                </button>
                
                <button @click="actionModalOpen = false" 
                        class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200 w-32">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 