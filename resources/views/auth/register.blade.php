<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FKI Event Center</title>
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
            <div class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-3xl xl:text-4xl font-extrabold">
                        Register Now!
                    </h1>
                    <blockquote class="text-center">
                        "The best way to predict the future is to create it."
                    </blockquote>
                    <div class="w-full flex-1">
                        <div class="my-12 border-b text-center">
                            <div
                                class="leading-none px-2 inline-block text-sm text-gray-600 tracking-wide font-medium bg-white transform translate-y-1/2">
                                Fill in your details here
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="mx-auto max-w-xs">
                            @csrf

                            <!-- Name -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Matric No -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="matric_no" type="text" name="matric_no" :value="old('matric_no')" required placeholder="Matric No" />
                                <x-input-error :messages="$errors->get('matric_no')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div class="mb-5">
                                <select id="role" name="role" class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white" required>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="event_organizer" {{ old('role') == 'event_organizer' ? 'selected' : '' }}>Event Organizer</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="password" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-5">
                                <input
                                    class="w-full px-8 py-4 rounded-lg font-medium bg-purple-100 border border-purple-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                    id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <button
                                class="mt-5 tracking-wide font-semibold bg-purple-500 text-gray-100 w-full py-4 rounded-lg hover:bg-purple-700 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="8.5" cy="7" r="4" />
                                    <path d="M20 8v6M23 11h-6" />
                                </svg>
                                <span class="ml-3">
                                    Register
                                </span>
                            </button>

                            <p class="mt-6 text-sm text-gray-600 text-center">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-purple-500 hover:text-purple-700">
                                    Sign in Here
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="flex-1 bg-purple-100 text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
