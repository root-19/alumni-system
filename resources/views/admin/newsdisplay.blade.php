<x-layouts.app title="News Display">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50 to-teal-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">News & Articles</h1>
                            <p class="text-slate-500 mt-1">All published news and announcements</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.news') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 py-2.5 rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-200 hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create News
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-emerald-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg shadow-emerald-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total News</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $news->count() }}</p>
                </div>

                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">With Images</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $news->whereNotNull('image_path')->count() }}</p>
                </div>

                <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-violet-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-lg shadow-violet-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $news->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>

            <!-- Featured News -->
            @if($news->first())
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-slate-700">Featured Article</h2>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden group hover:shadow-2xl transition-all duration-300">
                        <div class="grid md:grid-cols-2">
                            <div class="relative overflow-hidden">
                                @if($news->first()->image_path)
                                    <img src="{{ asset('storage/' . $news->first()->image_path) }}" alt="{{ $news->first()->title }}" class="w-full h-64 md:h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-64 md:h-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">FEATURED</span>
                                </div>
                            </div>
                            <div class="p-8 flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">News</span>
                                    <span class="text-xs text-slate-400">{{ $news->first()->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-2xl font-bold text-slate-800 mb-4 group-hover:text-emerald-600 transition-colors">{{ $news->first()->title }}</h3>
                                <p class="text-slate-600 leading-relaxed mb-6">{{ Str::limit($news->first()->content, 200) }}</p>
                                <div class="flex items-center gap-2 text-sm text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $news->first()->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- News Grid -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-slate-700">All News Articles</h2>
                    </div>
                    @if($news->count() > 0)
                        <span class="text-sm font-medium text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
                            {{ $news->count() }} article{{ $news->count() !== 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </div>

            @if($news->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($news->skip(1) as $item)
                        <article class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:border-emerald-200 transition-all duration-300">
                            <div class="relative overflow-hidden">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="bg-white/90 backdrop-blur-sm text-xs font-medium text-slate-600 px-2.5 py-1 rounded-full shadow-sm">
                                        {{ $item->created_at->format('M d') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">{{ $item->title }}</h3>
                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 mb-4">{{ Str::limit($item->content, 120) }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $item->created_at->diffForHumans() }}
                                    </div>
                                    @if($item->image_path)
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Has image
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Show first item if only one exists -->
                @if($news->count() === 1)
                    <div class="text-center py-8">
                        <p class="text-slate-500">This is your only news article. Create more to see them here!</p>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16">
                    <div class="text-center">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-700 mb-2">No News Yet</h3>
                        <p class="text-slate-500 mb-6 max-w-md mx-auto">Get started by creating your first news article to share with the alumni community.</p>
                        <a href="{{ route('admin.news') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-200 hover:shadow-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create First News
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Success Toast -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif
</x-layouts.app>

