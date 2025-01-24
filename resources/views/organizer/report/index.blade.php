<x-organizer-layout>
    <x-slot:title>
        Organizer Report
    </x-slot>

    <x-slot:header>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Organizer Report</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                <h2 class="text-lg font-semibold text-gray-700">Total Events Organized</h2>
                <p class="text-4xl font-bold text-blue-600">{{ $totalEvents }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                <h2 class="text-lg font-semibold text-gray-700">Total Pending Volunteers</h2>
                <p class="text-4xl font-bold text-yellow-600">{{ $pendingVolunteers }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                <h2 class="text-lg font-semibold text-gray-700">Total Forum Posts</h2>
                <p class="text-4xl font-bold text-purple-600">{{ $totalForumPosts }}</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                <h2 class="text-lg font-semibold text-gray-700">Average Event Rating</h2>
                <p class="text-4xl font-bold" style="@if($averageEventRating >= 4) color: green; @elseif($averageEventRating >= 3) color: orange; @else color: red; @endif">
                    {{ number_format($averageEventRating, 2) }}
                </p>
            </div>
        </div>

        <!-- Events with Feedback Section -->
        <livewire:organizer.events-with-feedback />

        <!-- Events Over Time Graph -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Events Created Over Time</h2>
                <select id="yearSelect" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <canvas id="eventsChart" class="w-full h-64"></canvas>
        </div>

        <!-- Volunteer Analysis Section -->
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Volunteer Analysis</h2>
            
            <canvas id="volunteerAnalysisChart" class="w-full h-64"></canvas>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center mt-6">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Requests</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $volunteerAnalysis->total_requests }}</p>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-700">Accepted Requests</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $volunteerAnalysis->accepted_requests }}</p>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-700">Rejected Requests</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $volunteerAnalysis->rejected_requests }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartCanvas = document.getElementById('volunteerAnalysisChart');
            const ctx = chartCanvas.getContext('2d');

            const chartData = {
                labels: ['Total Requests', 'Accepted Requests', 'Rejected Requests'],
                datasets: [{
                    label: 'Volunteer Requests',
                    data: [
                        {{ $volunteerAnalysis->total_requests }},
                        {{ $volunteerAnalysis->accepted_requests }},
                        {{ $volunteerAnalysis->rejected_requests }}
                    ],
                    backgroundColor: ['#FFEB3B', '#4CAF50', '#FF6347'],
                    hoverBackgroundColor: ['#FDD835', '#388E3C', '#FF4500']
                }]
            };

            const chartOptions = {
                indexAxis: 'y',
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Requests'
                        }
                    }
                }
            };

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        new Chart(ctx, {
                            type: 'bar',
                            data: chartData,
                            options: chartOptions
                        });
                        observer.unobserve(chartCanvas);
                    }
                });
            });

            observer.observe(chartCanvas);
        });

        // New Events Over Time Chart
        document.addEventListener('DOMContentLoaded', function () {
            let eventsChart;

            function createEventsChart(data) {
                const ctx = document.getElementById('eventsChart').getContext('2d');
                
                if (eventsChart) {
                    eventsChart.destroy();
                }

                eventsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.events.map(event => event.month_name),
                        datasets: [{
                            label: 'Events Created',
                            data: data.events.map(event => event.total_events),
                            backgroundColor: data.colors,
                            borderColor: data.colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return `${context[0].label} ${data.events[0].year}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Initial load
            fetchEventData(document.getElementById('yearSelect').value);

            // Year select handler
            document.getElementById('yearSelect').addEventListener('change', function() {
                fetchEventData(this.value);
            });

            function fetchEventData(year) {
                fetch(`/organizer/report/events-by-year/${year}`)
                    .then(response => response.json())
                    .then(data => {
                        createEventsChart(data);
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

    <x-toast />
</x-organizer-layout>
