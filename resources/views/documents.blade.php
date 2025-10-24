<x-layouts.app :title="__('Documents')">
    <div class="min-h-screen space-y-10 px-6 py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="rounded-lg bg-green-100 border border-green-200 p-4 text-green-800 text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Request Form --}}
            <div class="bg-white text-black rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-6 border-b pb-3">📄 Request a Document</h2>
                <form action="{{ route('documents.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Document Type --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full bg-white rounded-xl border border-gray-200 px-4 py-3 shadow-sm transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none focus:shadow-md placeholder:text-gray-400" required>
                            <option value="">Select a document</option>
                            <option value="Alumni ID">Alumni ID</option>
                            <option value="DOR">DOR (Document of Records)</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Good Moral">Certificate of Good Moral</option>
                            <option value="Enrollment Verification">Enrollment/Graduation Verification</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Purpose --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Purpose (optional)</label>
                        <input type="text" name="purpose" class="w-full bg-white rounded-xl border border-gray-200 px-4 py-3 shadow-sm transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none focus:shadow-md placeholder:text-gray-400" placeholder="e.g., Job application, Scholarship" value="{{ old('purpose') }}">
                        @error('purpose')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Message (optional)</label>
                        <textarea name="message" rows="4" class="w-full bg-white rounded-xl border border-gray-200 px-4 py-3 shadow-sm transition-all focus:border-green-500 focus:ring-4 focus:ring-green-100 focus:outline-none focus:shadow-md placeholder:text-gray-400" placeholder="Additional details">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
                            ✅ Submit Request
                        </button>
                    </div>
                </form>
            </div>

            {{-- My Requests --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-6 border-b pb-3">📑 My Requests</h2>
                @if(isset($requests) && $requests->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-100 text-left text-black">
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Purpose</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Requested</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-black">
                                @foreach($requests as $req)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium">{{ $req->type }}</td>
                                        <td class="py-3 px-4">{{ $req->purpose ?? '—' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                                @class([
                                                    'bg-yellow-100 text-yellow-800' => $req->status === 'Pending',
                                                    'bg-blue-100 text-blue-800' => $req->status === 'Processing',
                                                    'bg-green-100 text-green-800' => in_array($req->status, ['Approved','Completed']),
                                                    'bg-red-100 text-red-800' => $req->status === 'Rejected',
                                                ])
                                            ">
                                                {{ $req->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-gray-500">{{ $req->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600 italic">No requests yet. Use the form above to create one.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
