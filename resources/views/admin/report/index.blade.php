<x-admin-layout>
    <x-slot:title>
        Admin Report
    </x-slot>

    <x-slot:header>
        Admin Report
    </x-slot>

    <div class="min-h-[calc(100vh-65px)] pb-8">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                
            <!-- Report Summary Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                    <h2 class="text-lg font-semibold text-gray-700">Total Events Created</h2>
                    <p class="text-4xl font-bold text-blue-600">{{ $totalEvents }}</p>
                </div>                    
                <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                    <h2 class="text-lg font-semibold text-gray-700">Total Booked Venues</h2>
                    <p class="text-4xl font-bold text-green-600">{{ $bookedVenues }}</p>
                </div>                    
                <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition-transform">
                    <h2 class="text-lg font-semibold text-gray-700">News</h2>
                    <p class="text-4xl font-bold text-yellow-600">{{ $totalNews }}</p>
                </div>
            </div>

            <!-- All Events Section -->
            <div class="border border-gray-300 p-6 bg-white shadow-lg rounded-lg">
                <div class="mt-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Events Report Analysis</h2>
                    <hr class="border-t-2 border-gray-300 mt-2">
                </div>

                <livewire:admin.event-report />
            </div>

            <div class="bg-white p-6 shadow-lg rounded-lg mt-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800">Events Created per Month</h2>
                        <span id="totalEvents" class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            Total: {{ $eventsPerMonth->sum('total_events') }}
                        </span>
                    </div>
                    <select id="yearSelector" 
                            class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart Container -->
                    <div class="lg:col-span-2">
                        <div class="h-[500px] bg-gray-50 rounded-xl p-4">
                            <canvas id="eventsPerMonthChart" class="h-full w-full"></canvas>
                        </div>
                    </div>
                    
                    <!-- Monthly Stats -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Monthly Breakdown</h3>
                        <div class="space-y-4 max-h-[440px] overflow-y-auto pr-2">
                            @foreach($eventsPerMonth as $event)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" 
                                            style="background-color: {{ 'hsl(' . (($loop->index * 30) % 360) . ', 70%, 50%)' }}">
                                        </div>
                                        <span class="text-gray-700 font-medium">{{ $event->month_name }}</span>
                                    </div>
                                    <div class="flex items-center bg-gray-100 px-3 py-1 rounded-full">
                                        <span class="text-lg font-semibold text-gray-800">{{ $event->total_events }}</span>
                                        <span class="text-xs text-gray-500 ml-1">events</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 shadow-lg rounded-lg mt-6">
                <h2 class="text-xl font-bold mb-4">New Users per Year</h2>
                <div class="h-[400px]">
                    <canvas id="newUserChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        let eventsChart = null; // Variable to store the chart instance

        function updateEventsChart(data) {
            if (eventsChart) {
                eventsChart.destroy();
            }

            // Create array for all 12 months
            const allMonths = Array.from({length: 12}, (_, i) => {
                return {
                    month: i + 1,
                    month_name: new Date(2000, i, 1).toLocaleString('default', { month: 'long' }),
                    total_events: 0
                };
            });

            // Update the counts for months that have data
            data.events.forEach(event => {
                const monthIndex = event.month - 1;
                allMonths[monthIndex].total_events = event.total_events;
            });

            var ctxEvents = document.getElementById('eventsPerMonthChart');
            if (ctxEvents) {
                eventsChart = new Chart(ctxEvents, {
                    type: 'bar',
                    data: {
                        labels: allMonths.map(month => month.month_name),
                        datasets: [{
                            label: 'Events',
                            data: allMonths.map(month => month.total_events),
                            backgroundColor: allMonths.map((_, index) => 
                                `hsl(${(index * 30) % 360}, 70%, 50%)`
                            ),
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.5,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        return `${context.raw} event${context.raw !== 1 ? 's' : ''} created`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12
                                    },
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Update total events count
            document.getElementById('totalEvents').textContent = `Total: ${data.total}`;

            // Update monthly breakdown with all months
            const breakdownContainer = document.querySelector('.space-y-4');
            breakdownContainer.innerHTML = allMonths.map((month, index) => `
                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full" 
                            style="background-color: hsl(${(index * 30) % 360}, 70%, 50%)">
                        </div>
                        <span class="text-gray-700 font-medium">${month.month_name}</span>
                    </div>
                    <div class="flex items-center bg-gray-100 px-3 py-1 rounded-full">
                        <span class="text-lg font-semibold text-gray-800">${month.total_events}</span>
                        <span class="text-xs text-gray-500 ml-1">events</span>
                    </div>
                </div>
            `).join('');
        }

        // Updated year selector handler with better error handling
        document.getElementById('yearSelector').addEventListener('change', function() {
            const year = this.value;
            const loadingText = document.getElementById('totalEvents');
            
            // Show loading state
            loadingText.textContent = 'Loading...';
            
            fetch(`{{ route('admin.report.events-by-year', '') }}/${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.events) {
                        updateEventsChart(data);
                    } else {
                        throw new Error('Invalid data format received');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingText.textContent = 'Error loading data';
                    // Optionally show an error message to the user
                    alert('Failed to load data for the selected year. Please try again.');
                });
        });

        // Initial chart setup
        window.onload = function() {
            // Initialize the events chart
            const initialData = {
                events: @json($eventsPerMonth),
                colors: @json($chartColors),
                total: {{ $eventsPerMonth->sum('total_events') }}
            };
            updateEventsChart(initialData);

            // New User Count Chart (Updated for yearly data)
            var ctxNewUser = document.getElementById('newUserChart');
            if (ctxNewUser) {
                var ctx = ctxNewUser.getContext('2d');
                var gradientStroke = ctx.createLinearGradient(0, 0, 0, 400);
                gradientStroke.addColorStop(0, 'rgba(157, 0, 255, 0.8)');
                gradientStroke.addColorStop(1, 'rgba(157, 0, 255, 0.1)');

                new Chart(ctxNewUser, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach($newUserCount as $user)
                                '{{ $user->year }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: 'New Users',
                            data: [
                                @foreach($newUserCount as $user)
                                    {{ $user->total_users }},
                                @endforeach
                            ],
                            borderColor: 'rgba(157, 0, 255, 1)',
                            backgroundColor: gradientStroke,
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            pointBackgroundColor: '#9d00ff',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#9d00ff'
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#888' }
                            },
                            y: {
                                grid: { color: 'rgba(200, 200, 200, 0.2)' },
                                ticks: { 
                                    beginAtZero: true, 
                                    color: '#888',
                                    stepSize: 1
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            }
        };
    </script>   
</x-admin-layout>