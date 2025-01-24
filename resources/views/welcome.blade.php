<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FKI Event Center</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="antialiased bg-white">
    <!-- Navbar -->
    <header class="p-6 bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between px-6 lg:px-16">
            <div class="text-xl font-bold text-purple-600">
                FKI Event Center
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" 
                   class="text-purple-600 hover:text-purple-700 transition-colors duration-300">
                    Log In
                </a>
                <a href="{{ route('register') }}" 
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    Sign Up
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <!-- Custom Grid Background with Animation -->
        <div class="absolute inset-0 h-full w-full bg-[#FAF5FF] bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent"></div>
        </div>

        <div class="relative container mx-auto flex flex-col-reverse lg:flex-row items-center justify-between py-16 px-6 lg:px-16">
            <!-- Left Content -->
            <div class="lg:w-1/2 mt-12 lg:mt-0 lg:pr-12">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl leading-tight">
                    <span class="animate__animated animate__fadeInDown">
                        Manage Your Events 
                    </span>
                    <span class="text-purple-600 animate__animated animate__fadeInDown animate__delay-1s">
                        Effortlessly
                    </span>
                    <span class="animate__animated animate__fadeInDown animate__delay-2s">
                        with FKI Event Center
                    </span>
                </h1>
                <p class="mt-8 text-lg text-gray-600 leading-relaxed animate__animated animate__fadeIn animate__delay-3s">
                    Our platform helps you organize and manage all aspects of your events—from attendee management to certification distribution. Everything you need in one place to ensure your event runs smoothly.
                </p>
                <div class="mt-10 flex items-center space-x-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        Get Started
                    </a>
                    <a href="#features" 
                       class="px-8 py-4 text-purple-600 font-semibold hover:bg-purple-50 rounded-lg transition-all duration-300 scroll-smooth"
                       onclick="document.getElementById('features').scrollIntoView({ behavior: 'smooth' })">
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Right Content (Mockup) -->
            <div class="lg:w-1/2 relative">
                <div class="relative animate-float">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                    <img src="/images/WelcomeCartoon.png" 
                         alt="Person using laptop illustration" 
                         class="relative w-full h-auto rounded-lg shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-6 lg:px-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12 animate__animated animate__fadeIn">
                Why Choose FKI Event Center?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card p-6 bg-purple-50 rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy Management</h3>
                    <p class="text-gray-600">Streamline your event organization with our intuitive management tools.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card p-6 bg-purple-50 rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Attendee Tracking</h3>
                    <p class="text-gray-600">Keep track of your attendees and manage registrations effortlessly.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card p-6 bg-purple-50 rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Digital Certificates</h3>
                    <p class="text-gray-600">Generate and distribute digital certificates automatically.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-purple-600 to-purple-900 text-white">
        <div class="container mx-auto px-6 lg:px-16 text-center">
            <h2 class="text-3xl font-bold mb-8">Experience Better Event Management Today</h2>
            <p class="text-lg text-purple-100 mb-8 max-w-2xl mx-auto">
                Join thousands of event organizers who trust FKI Event Center for their event management needs.
            </p>
            <a href="{{ route('login') }}" 
               class="inline-block px-8 py-4 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                Sign In Now
            </a>
        </div>
    </section> --}}

    <!-- Add floating animation -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Custom animation for text gradient */
        .text-gradient {
            background: linear-gradient(to right, #9333ea, #d946ef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textGradient 5s ease-in-out infinite;
        }

        @keyframes textGradient {
            0% { background-position: 0% center; }
            50% { background-position: 100% center; }
            100% { background-position: 0% center; }
        }

        /* Custom typing animation */
        .typing-effect {
            border-right: 3px solid;
            animation: typing 3.5s steps(40, end), blink .75s step-end infinite;
            white-space: nowrap;
            overflow: hidden;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink {
            from, to { border-color: transparent }
            50% { border-color: #9333ea; }
        }

        /* For smoother animations */
        .animate__animated {
            --animate-duration: 1.2s;
        }
    </style>

    <!-- Optional: Add this for scroll reveal animations -->
    <script src="https://unpkg.com/scrollreveal"></script>
    <script>
        ScrollReveal().reveal('.feature-card', {
            delay: 200,
            distance: '50px',
            duration: 1000,
            origin: 'bottom',
            interval: 200,
            opacity: 0,
            scale: 0.8,
            viewFactor: 0.2,  // 20% of the element must be visible to trigger animation
            cleanup: true     // Remove animation after it runs
        });

        // Separate reveal for the section title
        ScrollReveal().reveal('#features h2', {
            delay: 100,
            distance: '20px',
            duration: 1000,
            origin: 'bottom',
            opacity: 0,
            viewFactor: 0.2
        });
    </script>

    <!-- Footer -->
    @include("layouts.footer")
</body>
</html>
