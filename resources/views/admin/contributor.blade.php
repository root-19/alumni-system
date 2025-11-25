<x-layouts.app title="Donations Management">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-10">
                <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Donations Management</h1>
                        <p class="text-slate-500 mt-1">View and manage all donation contributions from alumni</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                <!-- Total Donations -->
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Total</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Donations</p>
                    <p class="text-2xl font-bold text-slate-800">₱{{ number_format($totalDonations, 2) }}</p>
                </div>

                <!-- Confirmed Donations -->
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-emerald-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">{{ $confirmedCount }}</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Confirmed</p>
                    <p class="text-2xl font-bold text-slate-800">₱{{ number_format($confirmedDonations, 2) }}</p>
                </div>

                <!-- Pending Donations -->
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-amber-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">{{ $pendingCount }}</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-slate-800">₱{{ number_format($pendingDonations, 2) }}</p>
                </div>

                <!-- Total Contributors -->
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-violet-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg shadow-violet-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-violet-600 bg-violet-50 px-2 py-1 rounded-full">Alumni</span>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Contributors</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $donationCount }}</p>
                </div>
            </div>

            <!-- Search and Filter -->
            <form method="GET" action="{{ url()->current() }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-8 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h3 class="font-semibold text-slate-700">Filter Donations</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="search" class="block text-sm font-medium text-slate-600 mb-2">Search Donor</label>
                            <div class="relative">
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate   -y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-600 mb-2">Status</label>
                            <select name="status" id="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">All Statuses</option>
                                <option value="Confirmed" {{ request('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-600 mb-2">Amount Range</label>
                            <select name="amount" id="amount" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">All Amounts</option>
                                <option value="0-1000" {{ request('amount') === '0-1000' ? 'selected' : '' }}>₱0 - ₱1,000</option>
                                <option value="1000-5000" {{ request('amount') === '1000-5000' ? 'selected' : '' }}>₱1,000 - ₱5,000</option>
                                <option value="5000-10000" {{ request('amount') === '5000-10000' ? 'selected' : '' }}>₱5,000 - ₱10,000</option>
                                <option value="10000+" {{ request('amount') === '10000+' ? 'selected' : '' }}>₱10,000+</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-5 flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-2.5 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filter
                        </button>
                        <a href="{{ url()->current() }}" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl hover:bg-slate-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Donations Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">All Donations</h3>
                            <p class="text-sm text-slate-500 mt-0.5">Complete list of all donation contributions</p>
                        </div>
                        @if($donations->count() > 0)
                            <span class="text-sm font-medium text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
                                {{ $donations->count() }} record{{ $donations->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($donations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Donor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Receipt</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($donations as $donation)
                                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0">
                                                    @if($donation->user->profile_image_path)
                                                        <img class="h-11 w-11 rounded-full object-cover ring-2 ring-white shadow-md" src="{{ asset('storage/' . $donation->user->profile_image_path) }}" alt="{{ $donation->user->name }}">
                                                    @else
                                                        <div class="h-11 w-11 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center ring-2 ring-white shadow-md">
                                                            <span class="text-sm font-semibold text-white">{{ $donation->user->initials() }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $donation->user->name }}</p>
                                                    <p class="text-sm text-slate-500">{{ $donation->user->email }}</p>
                                                    @if($donation->user->year_graduated)
                                                        <p class="text-xs text-slate-400 mt-0.5">Class of {{ $donation->user->year_graduated }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-lg font-bold text-slate-800">₱{{ number_format($donation->amount, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($donation->status === 'Confirmed')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Confirmed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($donation->receipt_image)
                                                <a href="{{ asset('storage/' . $donation->receipt_image) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-sm italic">No receipt</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-slate-700">{{ $donation->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-slate-400">{{ $donation->created_at->format('g:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if($donation->status === 'Pending')
                                                    <form method="POST" action="{{ route('admin.donations.status', $donation->id) }}" class="confirm-form" data-id="{{ $donation->id }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="Confirmed">
                                                        <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors" title="Confirm Donation">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Confirm
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <button onclick="printDonation({{ $donation->id }}, '{{ addslashes($donation->user->name) }}', '{{ $donation->user->email }}', '{{ number_format($donation->amount, 2) }}', '{{ $donation->status }}', '{{ $donation->created_at->format('M d, Y g:i A') }}')" class="inline-flex items-center gap-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors" title="Print Receipt">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    Print
                                                </button>

                                                <form method="POST" action="{{ route('admin.donations.destroy', $donation->id) }}" class="delete-form" data-id="{{ $donation->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors" title="Delete Donation">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="mx-auto w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700">No donations yet</h3>
                        <p class="text-slate-500 mt-1">Get started by encouraging alumni to make contributions.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3 animate-pulse">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm Donation Form
            document.querySelectorAll('.confirm-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formEl = this;
                    
                    Swal.fire({
                        title: 'Confirm Donation?',
                        text: 'Are you sure you want to confirm this donation?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, confirm it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formEl.submit();
                        }
                    });
                });
            });

            // Delete Donation Form
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formEl = this;
                    
                    Swal.fire({
                        title: 'Delete Donation?',
                        text: 'This action cannot be undone. Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formEl.submit();
                        }
                    });
                });
            });
        });

        // Print Donation Receipt
        function printDonation(id, name, email, amount, status, date) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Donation Receipt #${id}</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; background: #f8fafc; }
                        .receipt { max-width: 500px; margin: 0 auto; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
                        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px dashed #e2e8f0; }
                        .logo { font-size: 28px; font-weight: bold; color: #10b981; margin-bottom: 5px; }
                        .subtitle { color: #64748b; font-size: 14px; }
                        .receipt-title { text-align: center; font-size: 20px; font-weight: 600; color: #1e293b; margin-bottom: 25px; }
                        .row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
                        .row:last-child { border-bottom: none; }
                        .label { color: #64748b; font-size: 14px; }
                        .value { color: #1e293b; font-weight: 600; font-size: 14px; }
                        .amount-row { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border-radius: 12px; margin: 20px 0; text-align: center; }
                        .amount-label { font-size: 12px; opacity: 0.9; margin-bottom: 5px; }
                        .amount-value { font-size: 32px; font-weight: bold; }
                        .status { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; }
                        .status.confirmed { background: #d1fae5; color: #065f46; }
                        .status.pending { background: #fef3c7; color: #92400e; }
                        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px dashed #e2e8f0; color: #94a3b8; font-size: 12px; }
                        @media print { body { background: white; padding: 0; } .receipt { box-shadow: none; } }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        <div class="header">
                            <div class="logo">Alumni Association</div>
                            <div class="subtitle">Donation Receipt</div>
                        </div>
                        <div class="receipt-title">Receipt #${id}</div>
                        <div class="row">
                            <span class="label">Donor Name</span>
                            <span class="value">${name}</span>
                        </div>
                        <div class="row">
                            <span class="label">Email</span>
                            <span class="value">${email}</span>
                        </div>
                        <div class="row">
                            <span class="label">Date</span>
                            <span class="value">${date}</span>
                        </div>
                        <div class="row">
                            <span class="label">Status</span>
                            <span class="status ${status.toLowerCase()}">${status}</span>
                        </div>
                        <div class="amount-row">
                            <div class="amount-label">Donation Amount</div>
                            <div class="amount-value">₱${amount}</div>
                        </div>
                        <div class="footer">
                            <p>Thank you for your generous contribution!</p>
                            <p style="margin-top: 5px;">This receipt was generated on ${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                    <script>window.onload = function() { window.print(); }<\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</x-layouts.app>
