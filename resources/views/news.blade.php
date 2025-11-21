<x-layouts.app :title="__('News and updates')">
    <div class="min-h-screen space-y-10">
        <!-- Hero with overlay: uses latest news/alumni image; text shows News and Updates and latest news title if present -->
        <p class="uppercase text-black font-bold tracking-wider text-sm">News and Updates</p>
        <section class="rounded-2xl shadow overflow-hidden">
            @php($heroImage = $featuredNews?->image_path ?? $featuredAlumni?->image_path)
            @php($heroImageUrl = $heroImage ? \App\Helpers\ImageHelper::getImageUrl($heroImage) : null)
            <div class="relative">
                @if($heroImageUrl)
                    <img src="{{ $heroImageUrl }}" alt="Hero" class="w-full h-72 object-cover" onerror="this.onerror=null; this.src='{{ asset('storage/'.$heroImage) }}';">
                @endif
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 flex items-end p-6">
                    <div class="text-white max-w-3xl">
                        <p class="uppercase tracking-wider text-sm">News and Updates</p>
                        @if($featuredNews)
                            <h1 class="text-3xl md:text-4xl font-semibold leading-tight">{{ $featuredNews->title }}</h1>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- News List (dynamic only) -->
        @if($news->count())
        <section class="space-y-4">
            <div class="grid gap-6">
                @foreach($news as $item)
                    <article class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="grid md:grid-cols-3">
                            @if($item->image_path)
                                @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($item->image_path))
                                @if($imageUrl)
                                <div>
                                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-44 md:h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('storage/'.$item->image_path) }}';">
                                </div>
                                @endif
                            @endif
                            <div class="md:col-span-2 p-6 space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->title }}</h3>
                                <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($item->content, 220) }}</p>
                                <span class="text-xs text-gray-500">{{ $item->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Alumni Posts Grid (dynamic only) -->
        @if($alumniPosts->count())
        <section>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($alumniPosts as $post)
                    <article class="bg-white rounded-2xl shadow p-4">
                        @if($post->image_path)
                            @php($imageUrl = \App\Helpers\ImageHelper::getImageUrl($post->image_path))
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="w-full h-48 object-cover rounded-xl" alt="" onerror="this.onerror=null; this.src='{{ asset('storage/'.$post->image_path) }}';">
                            @endif
                        @endif
                        <p class="text-xs md:text-sm font-semibold text-gray-800 text-center uppercase tracking-wide mt-3">
                            {{ strtoupper(\Illuminate\Support\Str::limit(strip_tags($post->content), 60)) }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</x-layouts.app>