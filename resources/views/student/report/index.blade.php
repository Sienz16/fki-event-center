<x-student-layout>
    <x-slot:title>
        Student Report
    </x-slot>

    <x-slot:header>
        Student Report
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 min-h-[calc(100vh-16rem)]">
        <!-- Report Summary Section with Improved Aesthetics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card Template with Icons and Hover Effect -->
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Total Events Joined</h2>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $totalEventsJoined }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Certifications Received</h2>
                <p class="text-4xl font-bold text-green-500 mt-2">{{ $certificationsReceived }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Total Volunteer Requests</h2>
                <p class="text-4xl font-bold text-purple-500 mt-2">{{ $totalVolunteerRequests }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Total Forum Views</h2>
                <p class="text-4xl font-bold text-indigo-500 mt-2">{{ $totalForumViews }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Total Forum Likes</h2>
                <p class="text-4xl font-bold text-red-500 mt-2">{{ $totalForumLikes }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform transition hover:scale-105 duration-300">
                <h2 class="text-lg font-semibold text-gray-600">Average Event Rating Given</h2>
                <p class="text-4xl font-bold text-yellow-500 mt-2">{{ number_format($averageEventRatingGiven, 2) }}</p>
            </div>
        </div>

        <!-- Redesigned Charts Section -->
        <div class="bg-white p-6 shadow-lg rounded-lg mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Event Participation Timeline</h2>
            <canvas id="eventParticipationChart" class="rounded-lg shadow-md"></canvas>
        </div>

        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Certifications Received Over Time</h2>
            <canvas id="certificationsChart" class="rounded-lg shadow-md"></canvas>
        </div>
    </div>

    <script>
        // Define months in order for consistent plotting
        const orderedMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // Convert PHP data into JavaScript arrays for plotting
        const registrationsData = orderedMonths.map((month, index) => {
            const monthData = @json($eventRegistrations).find(data => data.month === index + 1);
            return monthData ? monthData.registered_count : 0;
        });

        const attendanceData = orderedMonths.map((month, index) => {
            const monthData = @json($eventAttendance).find(data => data.month === index + 1);
            return monthData ? monthData.attended_count : 0;
        });

        // Event Participation Timeline Chart with sequential point-to-point and fill animation
        var ctxParticipation = document.getElementById('eventParticipationChart').getContext('2d');
        var eventParticipationChart = new Chart(ctxParticipation, {
            type: 'line',
            data: {
                labels: orderedMonths,
                datasets: [
                    {
                        label: 'Registered Events',
                        data: registrationsData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.3)',
                        pointBackgroundColor: '#36A2EB',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Attended Events',
                        data: attendanceData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.3)',
                        pointBackgroundColor: '#4BC0C0',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad',
                    onProgress(animation) {
                        const currentProgress = animation.currentStep / animation.numSteps;
                        if (currentProgress < 0.7) {
                            eventParticipationChart.data.datasets.forEach((dataset) => {
                                dataset.backgroundColor = 'rgba(0, 0, 0, 0)';
                            });
                        } else {
                            eventParticipationChart.data.datasets[0].backgroundColor = 'rgba(54, 162, 235, 0.3)';
                            eventParticipationChart.data.datasets[1].backgroundColor = 'rgba(75, 192, 192, 0.3)';
                        }
                    },
                    onComplete() {
                        eventParticipationChart.data.datasets[0].backgroundColor = 'rgba(54, 162, 235, 0.3)';
                        eventParticipationChart.data.datasets[1].backgroundColor = 'rgba(75, 192, 192, 0.3)';
                    }
                },
                plugins: {
                    legend: { display: true, position: 'top', labels: { color: '#333' } }
                },
                scales: {
                    x: { ticks: { color: '#888' }, grid: { display: false } },
                    y: { ticks: { color: '#888' }, beginAtZero: true, grid: { color: 'rgba(200, 200, 200, 0.2)' } }
                }
            }
        });
    
        // Certifications Received Over Time Chart
        var ctxCertifications = document.getElementById('certificationsChart').getContext('2d');
        var certificationsChart = new Chart(ctxCertifications, {
            type: 'line',
            data: {
                labels: [
                    @foreach($certificationsReceivedOverTime as $certification)
                        '{{ date("F", mktime(0, 0, 0, $certification->month, 1)) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Certifications',
                    data: [
                        @foreach($certificationsReceivedOverTime as $certification)
                            {{ $certification->certifications }},
                        @endforeach
                    ],
                    borderColor: 'rgba(255, 99, 132, 0.8)',
                    backgroundColor: 'rgba(255, 99, 132, 0.3)',
                    pointBackgroundColor: '#ff6384',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                animation: {
                    duration: 2000,
                    easing: 'easeInOutSine',
                    x: {
                        from: 0
                    }
                },
                plugins: {
                    legend: { display: true, position: 'top', labels: { color: '#333' } }
                },
                scales: {
                    x: { ticks: { color: '#888' }, grid: { display: false } },
                    y: { ticks: { color: '#888' }, beginAtZero: true, grid: { color: 'rgba(200, 200, 200, 0.2)' } }
                }
            }
        });
    </script>
</x-student-layout>