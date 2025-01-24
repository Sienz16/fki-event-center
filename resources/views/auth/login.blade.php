<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FKI Event Center</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Open+Sans:wght@400&display=swap');
        h1 {
            font-family: 'Poppins', sans-serif;
        }
        blockquote {
            font-family: 'Open Sans', sans-serif;
            font-size: 1rem;
            color: #6B7280; /* Tailwind's Gray-600 */
            font-style: italic;
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex justify-center items-center">
        <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            
            <!-- Image Section on the Left -->
            <div class="flex-1 bg-purple-100 text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
            
            <!-- Login Form Section on the Right -->
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-3xl xl:text-4xl font-extrabold">
                        Login
                    </h1>
                    <blockquote class="text-center">
                        "Welcome back! Log in to continue."
                    </blockquote>
                    <div class="w-full flex-1">
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <div class="my-12 border-b text-center">
                            <div
                                class="leading-none px-2 inline-block text-sm text-gray-600 tracking-wide font-medium bg-white transform translate-y-1/2">
                                Enter your details below
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="mx-auto max-w-xs">
                            @csrf

                            <!-- Matric No -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white valid:bg-purple-100"
                                    id="matric_no" type="text" name="matric_no" :value="old('matric_no')" required autofocus placeholder="Matric No" />
                                <x-input-error :messages="$errors->get('matric_no')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Role Selection -->
                            <div class="mb-5">
                                <select id="role" name="role" class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="event_organizer" {{ old('role') == 'event_organizer' ? 'selected' : '' }}>Event Organizer</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Remember Me -->
                            <div class="block mb-5">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox" class="rounded dark:bg-purple-900 border-purple-300 dark:border-purple-700 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-purple-600 dark:focus:ring-offset-purple-800" name="remember">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                                </label>
                            </div>

                            <button
                                class="mt-5 tracking-wide font-semibold bg-purple-500 text-gray-100 w-full py-4 rounded-lg hover:bg-purple-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="8.5" cy="7" r="4" />
                                    <path d="M20 8v6M23 11h-6" />
                                </svg>
                                <span class="ml-3">
                                    Log in
                                </span>
                            </button>

                            {{-- <div class="flex items-center justify-end mt-4">
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div> --}}

                            <p class="mt-6 text-sm text-gray-600 text-center">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-purple-500 hover:text-purple-700">
                                    Register Here
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
