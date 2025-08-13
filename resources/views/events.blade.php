{{-- <x-layouts.app :title="__('Events')">
    <div class="min-h-screen space-y-10">
        <p class="uppercase text-black font-bold tracking-wider text-sm">Events</p>

        @if($alumniPosts->count())
            <section>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($alumniPosts as $post)
                        <article class="bg-white rounded-2xl shadow p-4">
                            @if($post->image_path)
                                <img src="{{ asset('storage/'.$post->image_path) }}" 
                                     class="w-full h-48 object-cover rounded-xl" 
                                     alt="Event Image">
                            @endif
                            <p class="text-xs md:text-sm font-semibold text-gray-800 text-center uppercase tracking-wide mt-3">
                                {{ strtoupper(\Illuminate\Support\Str::limit(strip_tags($post->content), 60)) }}
                            </p>
                            <span class="block text-center text-xs text-gray-500 mt-1">
                                {{ $post->created_at->format('M d, Y') }}
                            </span>
                        </article>
                    @endforeach
                </div>
            </section>
        @else
            <p class="text-gray-600">No events yet.</p>
        @endif
    </div>
</x-layouts.app> --}}
