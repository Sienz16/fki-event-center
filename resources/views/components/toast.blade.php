<div class="fixed bottom-4 right-4 z-50" x-data="{
    playSound() {
        const notificationSound = new Audio('https://res.cloudinary.com/ds8pgw1pf/video/upload/v1728571480/penguinui/component-assets/sounds/ding.mp3');
        notificationSound.play().catch((error) => {
            console.error('Error playing sound:', error);
        });
    }
}" x-init="
    @if(session('success') || session('error'))
        playSound()
    @endif
">
    @if(session('success'))
        <div id="successToast" class="flex items-center w-full max-w-sm p-4 mb-4 backdrop-blur-xl bg-white/70 rounded-2xl shadow-lg transform transition-all duration-300 ease-out border border-white/20" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 relative">
                <div class="absolute inset-0 rounded-xl bg-emerald-500/40 animate-ping-strong"></div>
                <div class="absolute inset-0 rounded-xl bg-emerald-500/10 backdrop-blur-sm"></div>
                <svg class="w-6 h-6 text-emerald-500 relative z-10 animate-slight-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4 mr-6">
                <h3 class="text-sm font-medium text-gray-900/90 mb-1">Success</h3>
                <p class="text-sm text-gray-600/90">{{ session('success') }}</p>
            </div>
            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 transition-colors duration-200 ease-in-out hover:bg-black/5" onclick="dismissToast(this.parentElement)">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12m0-12L1 13"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="errorToast" class="flex items-center w-full max-w-sm p-4 mb-4 backdrop-blur-xl bg-white/70 rounded-2xl shadow-lg transform transition-all duration-300 ease-out border border-white/20" role="alert">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-12 h-12 relative">
                <div class="absolute inset-0 rounded-xl bg-rose-500/40 animate-ping-strong"></div>
                <div class="absolute inset-0 rounded-xl bg-rose-500/10 backdrop-blur-sm"></div>
                <svg class="w-6 h-6 text-rose-500 relative z-10 animate-slight-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            <div class="ml-4 mr-6">
                <h3 class="text-sm font-medium text-gray-900/90 mb-1">Error</h3>
                <p class="text-sm text-gray-600/90">{{ session('error') }}</p>
            </div>
            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 transition-colors duration-200 ease-in-out hover:bg-black/5" onclick="dismissToast(this.parentElement)">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 12 12m0-12L1 13"/>
                </svg>
            </button>
        </div>
    @endif
</div>

<style>
    @keyframes ping-strong {
        0% {
            transform: scale(0.8);
            opacity: 0.8;
        }
        75%, 100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }
    .animate-ping-strong {
        animation: ping-strong 1.2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes slight-bounce {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    .animate-slight-bounce {
        animation: slight-bounce 2s ease-in-out infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('[role="alert"]');
        toasts.forEach(toast => {
            toast.style.opacity = '0';
            toast.style.transform = 'scale(0.95) translateY(10px)';
            
            requestAnimationFrame(() => {
                toast.style.transition = 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)';
                toast.style.opacity = '1';
                toast.style.transform = 'scale(1) translateY(0)';
            });

            setTimeout(() => {
                dismissToast(toast);
            }, 4000);
        });
    });

    function dismissToast(toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'scale(0.95) translateY(10px)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
</script>