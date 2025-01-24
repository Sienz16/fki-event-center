<!-- Toast Error -->
<div 
    x-cloak
    x-data="{ showError: {{ session('error') ? 'true' : 'false' }} }" 
     x-show="showError"
     x-init="setTimeout(() => showError = false, 3000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-2"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-2"
     class="fixed bottom-4 right-4 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700"
     role="alert" tabindex="-1" aria-labelledby="toast-error-label">
    <div class="flex p-4">
        <div class="shrink-0">
            <svg class="shrink-0 w-4 h-4 text-red-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l-2.647-2.646z"></path>
            </svg>
        </div>
        <div class="ms-3">
            <p id="toast-error-label" class="text-sm text-gray-700 dark:text-neutral-400">
                {{ session('error') }}
            </p>
        </div>
    </div>
</div>
<!-- End Toast Error -->