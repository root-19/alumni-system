<x-layouts.app :title="__('Document Requests')">
    <div class="p-6 space-y-6">
        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white text-black rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">All Requests</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pr-4">User</th>
                            <th class="py-2 pr-4">Type</th>
                            <th class="py-2 pr-4">Purpose</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Requested</th>
                            <th class="py-2 pr-4">Admin Note</th>
                            <th class="py-2 pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($requests as $req)
                            <tr>
                                <td class="py-2 pr-4">{{ $req->user->name ?? 'User #'.$req->user_id }}</td>
                                <td class="py-2 pr-4">{{ $req->type }}</td>
                                <td class="py-2 pr-4">{{ $req->purpose ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $req->status }}</td>
                                <td class="py-2 pr-4">{{ $req->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2 pr-4">{{ $req->admin_note ?? '—' }}</td>
                                <td class="py-2 pr-4">
                                    <form action="{{ route('admin.document-requests.update', $req) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded border-gray-300">
                                            @foreach(['Pending','Processing','Approved','Rejected','Completed'] as $status)
                                                <option value="{{ $status }}" @selected($req->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <input name="admin_note" type="text" class="rounded border-gray-300" placeholder="Note" value="{{ old('admin_note', $req->admin_note) }}">
                                        <button class="px-3 py-1 rounded bg-green-600 text-white">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
