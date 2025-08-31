<x-layouts.app title="Donations Management">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Donations Management</h1>
                <p class="mt-2 text-gray-600">View and manage all donation contributions from alumni</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Donations -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Donations</dt>
                                    <dd class="text-lg font-medium text-gray-900">₱{{ number_format($totalDonations, 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmed Donations -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Confirmed</dt>
                                    <dd class="text-lg font-medium text-gray-900">₱{{ number_format($confirmedDonations, 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Donations -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                    <dd class="text-lg font-medium text-gray-900">₱{{ number_format($pendingDonations, 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Count -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Contributors</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $donationCount }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Confirmed Donations:</span>
                                <span class="text-sm font-medium text-green-600">{{ $confirmedCount }} (₱{{ number_format($confirmedDonations, 2) }})</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Donations:</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $pendingCount }} (₱{{ number_format($pendingDonations, 2) }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                Export to Excel
                            </button>
                            <button class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                                Send Reminders
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Donor</label>
                            <input type="text" id="search" placeholder="Search by name or email..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                            <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount-filter" class="block text-sm font-medium text-gray-700 mb-2">Amount Range</label>
                            <select id="amount-filter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Amounts</option>
                                <option value="0-1000">₱0 - ₱1,000</option>
                                <option value="1000-5000">₱1,000 - ₱5,000</option>
                                <option value="5000-10000">₱5,000 - ₱10,000</option>
                                <option value="10000+">₱10,000+</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Donations Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">All Donations</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete list of all donation contributions</p>
                </div>
                
                @if($donations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Donor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Receipt
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($donations as $donation)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($donation->user->profile_image_path)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $donation->user->profile_image_path) }}" alt="{{ $donation->user->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">{{ $donation->user->initials() }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $donation->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $donation->user->email }}</div>
                                                    @if($donation->user->year_graduated)
                                                        <div class="text-xs text-gray-400">Class of {{ $donation->user->year_graduated }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₱{{ number_format($donation->amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($donation->status === 'Confirmed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Confirmed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($donation->receipt_image)
                                                <a href="{{ asset('storage/' . $donation->receipt_image) }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Receipt
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-sm">No receipt</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $donation->created_at->format('M d, Y') }}
                                            <div class="text-xs text-gray-400">{{ $donation->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($donation->status === 'Pending')
                                                    <button onclick="confirmDonation({{ $donation->id }})" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                        Confirm
                                                    </button>
                                                @endif
                                                <button onclick="editDonation({{ $donation->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Edit
                                                </button>
                                                <button onclick="deleteDonation({{ $donation->id }})" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No donations yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by encouraging alumni to make contributions.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination Info -->
            @if($donations->count() > 0)
                <div class="mt-6 text-center text-sm text-gray-500">
                    Showing {{ $donations->count() }} donation{{ $donations->count() !== 1 ? 's' : '' }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for interactive features -->
    <script>
        function confirmDonation(donationId) {
            if (confirm('Are you sure you want to confirm this donation?')) {
                fetch(`/admin/donations/${donationId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: 'Confirmed'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated data
                        window.location.reload();
                    } else {
                        alert('Error updating donation status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating donation status');
                });
            }
        }

        function editDonation(donationId) {
            // TODO: Implement edit functionality
            alert('Edit functionality coming soon!');
        }

        function deleteDonation(donationId) {
            if (confirm('Are you sure you want to delete this donation? This action cannot be undone.')) {
                fetch(`/admin/donations/${donationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated data
                        window.location.reload();
                    } else {
                        alert('Error deleting donation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting donation');
                });
            }
        }

        // Add success message display
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                // Create a simple toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50';
                toast.textContent = '{{ session('success') }}';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        @endif

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const statusFilter = document.getElementById('status-filter');
            const amountFilter = document.getElementById('amount-filter');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterDonations() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const amountValue = amountFilter.value;

                tableRows.forEach(row => {
                    let showRow = true;

                    // Search filter
                    if (searchTerm) {
                        const donorName = row.querySelector('td:first-child .text-sm.font-medium').textContent.toLowerCase();
                        const donorEmail = row.querySelector('td:first-child .text-sm.text-gray-500').textContent.toLowerCase();
                        if (!donorName.includes(searchTerm) && !donorEmail.includes(searchTerm)) {
                            showRow = false;
                        }
                    }

                    // Status filter
                    if (statusValue && showRow) {
                        const status = row.querySelector('td:nth-child(3) span').textContent.trim();
                        if (status !== statusValue) {
                            showRow = false;
                        }
                    }

                    // Amount filter
                    if (amountValue && showRow) {
                        const amountText = row.querySelector('td:nth-child(2) .text-sm.font-medium').textContent;
                        const amount = parseFloat(amountText.replace('₱', '').replace(',', ''));
                        
                        switch(amountValue) {
                            case '0-1000':
                                showRow = amount >= 0 && amount <= 1000;
                                break;
                            case '1000-5000':
                                showRow = amount > 1000 && amount <= 5000;
                                break;
                            case '5000-10000':
                                showRow = amount > 5000 && amount <= 10000;
                                break;
                            case '10000+':
                                showRow = amount > 10000;
                                break;
                        }
                    }

                    row.style.display = showRow ? '' : 'none';
                });

                // Update count display
                const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                const countDisplay = document.querySelector('.mt-6.text-center.text-sm.text-gray-500');
                if (countDisplay) {
                    countDisplay.textContent = `Showing ${visibleRows.length} donation${visibleRows.length !== 1 ? 's' : ''}`;
                }
            }

            // Add event listeners
            searchInput.addEventListener('input', filterDonations);
            statusFilter.addEventListener('change', filterDonations);
            amountFilter.addEventListener('change', filterDonations);
        });
    </script>
</x-layouts.app>
