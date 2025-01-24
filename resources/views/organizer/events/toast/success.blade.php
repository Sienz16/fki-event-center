<!-- Toast Success -->
<div 
    x-cloak
    x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }" 
     x-show="showSuccess"
     x-init="setTimeout(() => showSuccess = false, 3000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-2"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-2"
     class="fixed bottom-4 right-4 max-w-sm bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700"
     role="alert" tabindex="-1" aria-labelledby="toast-success-label">
    <div class="flex p-4">
        <div class="shrink-0">
            <svg class="shrink-0 w-4 h-4 text-teal-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
            </svg>
        </div>
        <div class="ms-3">
            <p id="toast-success-label" class="text-sm text-gray-700 dark:text-neutral-400">
                {{ session('success') }}
            </p>
        </div>
    </div>
</div>
<!-- End Toast Success -->
