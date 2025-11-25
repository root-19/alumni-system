<x-layouts.app :title="__('Event Registrations')">
	<div class="max-w-7xl mx-auto mt-10 px-4 sm:px-6 lg:px-8 space-y-6">
		<div class="flex items-center justify-between">
			<h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-700" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2a1 1 0 00-1 1v2H3a1 1 0 000 2h2v2a1 1 0 002 0V7h2a1 1 0 100-2H7V3a1 1 0 00-1-1z"/><path d="M6 13a1 1 0 00-1 1v6a2 2 0 002 2h10a2 2 0 002-2v-6a1 1 0 10-2 0v6H7v-6a1 1 0 00-1-1z"/><path d="M9 11a3 3 0 116 0 3 3 0 01-6 0z"/></svg>
				Event Registrations
			</h1>
		</div>

		<form method="GET" action="{{ route('admin.registrations.index') }}" class="bg-white p-4 rounded-xl shadow border border-gray-100 flex items-center gap-3">
			<div>
				<label for="post_id" class="text-sm text-gray-600">Filter by Event</label>
				<select id="post_id" name="post_id" class="mt-1 border border-gray-300 rounded-md px-2 py-1 text-sm text-black">
					<option value="">All Events</option>
					@foreach($events as $event)
						<option value="{{ $event->id }}" @selected(request('post_id') == $event->id)>
							{{ $event->title ?? str($event->content)->limit(60) }}
						</option>
					@endforeach
				</select>
			</div>
			<div class="pt-6">
				<button class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white text-sm inline-flex items-center gap-1">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18a1 1 0 102 0v-5h5a1 1 0 100-2h-5V6a1 1 0 10-2 0v5H5a1 1 0 100 2h5v5z"/></svg>
					Apply
				</button>
			</div>
		</form>

		<div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Title</th>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrant</th>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
						<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
						<th class="px-4 py-2"></th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@forelse($registrations as $registration)
						<tr>
							<td class="px-4 py-2 text-sm text-gray-900">
								<a href="{{ route('admin.events.show', $registration->post) }}" class="text-green-700 hover:text-green-800 font-medium">
									{{ $registration->post?->title ?? '—' }}
								</a>
							</td>
							<td class="px-4 py-2 text-sm text-gray-900">{{ $registration->user?->name ?? 'Unknown' }}</td>
							<td class="px-4 py-2 text-sm text-gray-700">{{ $registration->user?->email ?? '—' }}</td>
							<td class="px-4 py-2 text-sm">
								@if($registration->user?->is_alumni)
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
										Alumni
									</span>
								@else
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
										Student
									</span>
								@endif
							</td>
							<td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst($registration->status) }}</td>
							<td class="px-4 py-2 text-sm text-gray-500">{{ $registration->created_at?->format('M d, Y g:i A') }}</td>
							<td class="px-4 py-2 text-right">
								<form method="POST" action="{{ route('admin.events.registrants.destroy', [$registration->post, $registration]) }}">
									@csrf
									@method('DELETE')
									<button type="submit" class="px-3 py-1.5 rounded-md bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium border border-red-200 transition">Remove</button>
								</form>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">No registrations found.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
			<div class="px-4 py-3 border-t border-gray-100">
				{{ $registrations->withQueryString()->links() }}
			</div>
		</div>
	</div>
</x-layouts.app>
