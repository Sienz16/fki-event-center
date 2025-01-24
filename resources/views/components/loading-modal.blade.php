<div x-show="loadingModalOpen" 
     x-cloak 
     class="fixed inset-0 z-50 flex items-center justify-center overflow-auto">
    
    <!-- Backdrop -->
    <div x-show="loadingModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm">
    </div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-lg mx-auto bg-white shadow-2xl rounded-3xl p-8"
         x-show="loadingModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8">

        <div class="text-center">
            <!-- Loading Spinner -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 mb-6">
                <div class="relative w-12 h-12">
                    <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-{{ $color ?? 'purple' }}-600 border-t-transparent animate-spin"></div>
                </div>
            </div>

            <!-- Title and Message -->
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $title ?? 'Processing' }}</h3>
            <p class="text-gray-600">{{ $message ?? 'Please wait...' }}</p>
        </div>
    </div>
</div>

<style>
@keyframes morph {
    0% {
        border-radius: 60% 40% 30% 70%/60% 30% 70% 40%;
    }
    50% {
        border-radius: 30% 60% 70% 40%/50% 60% 30% 60%;
    }
    100% {
        border-radius: 60% 40% 30% 70%/60% 30% 70% 40%;
    }
}

.animate-morph {
    animation: morph 8s ease-in-out infinite;
}
</style>