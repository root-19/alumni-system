<x-layouts.app :title="__('donations')">
    <div class="min-h-screen space-y-10 px-6 py-10">

        {{-- QR Code / Bank Details --}}
        <div class="bg-white shadow rounded-xl p-6 text-center">
            <h2 class="text-xl font-semibold mb-4">Donate via QR Code</h2>
            <img src="{{ asset('images/qrcode.png') }}" alt="QR Code" class="mx-auto w-48 h-48">
            <p class="mt-3 text-gray-600">Scan QR to donate via GCash/Maya or use Bank Transfer.</p>
        </div>

        {{-- Donation Form --}}
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Submit Your Donation</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700">Amount (₱)</label>
                    <input type="number" name="amount" step="0.01" min="1"
                           class="w-full border rounded p-2 text-black focus:ring focus:ring-blue-300" required>
                </div>

                <div>
                    <label class="block text-gray-700">Upload Receipt</label>
                    <input type="file" name="receipt_image"
                           class="w-full border text-black rounded p-2" accept="image/*" required>
                </div>

                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Submit Donation
                </button>
            </form>
        </div>
                 {{-- <p class="uppercase text-black font-bold tracking-wider text-sm">News</p>
        <section class="rounded-2xl shadow overflow-hidden">
            @php($heroImage = $featuredNews?->image_path ?? $featuredAlumni?->image_path)
            <div class="relative">
                @if($heroImage)
                    <img src="{{ asset('storage/'.$heroImage) }}" alt="Hero" class="w-full h-72 object-cover">
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
                                <div>
                                    <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}" class="w-full h-44 md:h-full object-cover">
                                </div>
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
                            <img src="{{ asset('storage/'.$post->image_path) }}" class="w-full h-48 object-cover rounded-xl" alt="">
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
    </div> --}}
</x-layouts.app>

