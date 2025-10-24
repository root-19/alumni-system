<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-10 min-h-screen">

        <!-- Stat Cards -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Accounts -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-green-100 rounded-xl">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 7h-6m6 6H7m6 6H7m12-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Accounts</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $userCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Messages / Reports (chat count) -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-yellow-100 rounded-xl">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Messages</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $messageCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Giving Back (Total Donations) -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-100 rounded-xl">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3 0 1.656 1.343 3 3 3s3-1.344 3-3c0-1.657-1.343-3-3-3zm0-6a9 9 0 00-9 9c0 7 9 13 9 13s9-6 9-13a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Giving Back</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($totalDonationAmount ?? 0,2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Analytics -->
        <div class="bg-white rounded-2xl shadow p-6 text-black">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Event Analytics</h2>
                <a href="{{ route('events') }}" class="text-sm text-blue-600 hover:underline">View all events</a>
            </div>

            <!-- Chart Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Event Performance</h3>
                    <div class="flex items-center gap-4">
                        <!-- Event Selection Dropdown -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Event:</label>
                            <select id="eventSelect" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">All Events</option>
                                @foreach($eventsList as $event)
                                    <option value="{{ $event->id }}">{{ Str::limit($event->content, 50) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Chart Legend -->
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                Reviews
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                Attendance
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="analyticsChart"></canvas>
                </div>
                </div>

            <!-- Reviews Table -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Event Reviews</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($review->alumniPost->content ?? 'N/A', 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}" class="w-8 h-8 rounded-full mr-3" alt="avatar">
                                            <div class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs">{{ Str::limit($review->comment, 100) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">No Reviews Yet</h3>
                                        <p class="text-xs text-gray-400">Reviews will appear here once users start rating events.</p>
                                    </td>
                                </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
                        </div>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart data
            const chartData = {!! json_encode($chartData) !!};
            const eventsData = {!! json_encode($eventsData) !!};
            
            // Initialize chart
            const ctx = document.getElementById('analyticsChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Reviews',
                        data: chartData.reviews,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Attendance',
                        data: chartData.attendance,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: function(context) {
                                return context.dataset.borderColor;
                            }
                        }
                    }
                }
            });

            // Event selection handler
            document.getElementById('eventSelect').addEventListener('change', function() {
                const selectedEventId = this.value;
                
                if (selectedEventId === 'all') {
                    // Show all events data
                    chart.data.datasets[0].data = chartData.reviews;
                    chart.data.datasets[1].data = chartData.attendance;
                } else {
                    // Show specific event data
                    const eventData = eventsData[selectedEventId];
                    if (eventData) {
                        chart.data.datasets[0].data = eventData.reviews;
                        chart.data.datasets[1].data = eventData.attendance;
                    }
                }
                
                chart.update();
            });
        </script>

                        </div>

        <!-- Top Contributors Section -->
        <div class="bg-white rounded-2xl shadow p-6 text-black">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Top Contributors</h2>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>

            <!-- Contributors Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($topContributors as $index => $contributor)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <!-- Rank Badge -->
                        <div class="flex items-center justify-between mb-3">
                            @if($index < 3)
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : '' }}
                                    {{ $index === 1 ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $index === 2 ? 'bg-orange-100 text-orange-600' : '' }}">
                                    @if($index === 0)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @elseif($index === 1)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                                    @endif
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                            @endif
                            
                            <!-- Contribution Score -->
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-900">{{ $contributor->contribution_score ?? 0 }}</div>
                                <div class="text-xs text-gray-500">points</div>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="text-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($contributor->name ?? 'User') }}&background=random" class="w-16 h-16 rounded-full mx-auto mb-3" alt="avatar">
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $contributor->name ?? 'Anonymous' }}</h4>
                            <p class="text-xs text-gray-500 mb-3">{{ $contributor->email ?? 'No email' }}</p>
                            
                            <!-- Activity Stats -->
                            <div class="flex justify-between text-xs text-gray-600">
                                <div class="text-center">
                                    <div class="font-semibold">{{ $contributor->review_count ?? 0 }}</div>
                                    <div>Reviews</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold">{{ $contributor->attendance_count ?? 0 }}</div>
                                    <div>Events</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold">{{ $contributor->donation_count ?? 0 }}</div>
                                    <div>Donations</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        <h3 class="text-lg font-medium text-gray-500 mb-2">No Contributors Yet</h3>
                        <p class="text-sm text-gray-400">Contributors will appear here once users start participating.</p>
                        </div>
                @endforelse
                        </div>

            <!-- View All Link -->
            @if($topContributors->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium">
                        View All Contributors →
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>