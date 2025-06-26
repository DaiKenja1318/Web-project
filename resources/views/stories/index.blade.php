<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Tiêu đề đã được làm đẹp hơn --}}
            <span class="font-bold text-indigo-600 dark:text-indigo-400">Stories</span> Feed
        </h2>
    </x-slot>

    {{-- Thêm màu nền nhẹ cho toàn trang để làm nổi bật các card trắng --}}
    <div class="bg-gray-100 dark:bg-gray-900">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                {{-- Di chuyển nút Create ra ngoài card chính cho dễ thấy --}}
                @auth
                <div class="mb-6 text-right">
                    <a href="{{ route('stories.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Create New Story
                    </a>
                </div>
                @endauth

                <div id="stories-list" class="space-y-8">
                    @forelse($stories as $story)
                        @if ($story->user)
                            {{-- === BẮT ĐẦU CARD CHO MỖI STORY === --}}
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl transform hover:scale-105 transition-transform duration-300" id="story-{{ $story->id }}">
                                @if($story->image)
                                    @php
                                        // ... (PHP code không đổi)
                                        $pathInfo = pathinfo($story->image);
                                        $directory = $pathInfo['dirname'];
                                        $filename = $pathInfo['basename'];
                                        $largeUrl = Storage::url($story->image);
                                        $mediumUrl = Storage::url($directory . '/medium_' . $filename);
                                        $thumbUrl = Storage::url($directory . '/thumb_' . $filename);
                                    @endphp
                                    <a href="{{ route('stories.show', $story) }}">
                                        <img src="{{ $mediumUrl }}"
                                             srcset="{{ $thumbUrl }} 400w, {{ $mediumUrl }} 800w, {{ $largeUrl }} 1200w"
                                             sizes="(max-width: 800px) 100vw, 800px"
                                             alt="{{ $story->title }}"
                                             class="w-full h-auto object-cover"
                                             loading="lazy">
                                    </a>
                                @endif
                                
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        {{-- Tên user màu mè hơn --}}
                                        <div class="font-bold text-lg text-indigo-700 dark:text-indigo-400">{{ $story->user->name }}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-sm ml-3">{{ $story->created_at->diffForHumans() }}</div>
                                    </div>
                                    
                                    <a href="{{ route('stories.show', $story) }}">
                                        {{-- Tiêu đề màu mè và có hiệu ứng hover --}}
                                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 mb-3">{{ $story->title }}</h3>
                                    </a>
                                    
                                    {{-- Nội dung với màu chữ dễ đọc hơn --}}
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{!! \Illuminate\Support\Str::limit(nl2br(e($story->content)), 200) !!}</p>
                                </div>
                            </div>
                             {{-- === KẾT THÚC CARD === --}}
                        @endif
                    @empty
                        {{-- Giao diện khi không có story nào, trông đẹp hơn --}}
                        <div class="text-center text-gray-500 dark:text-gray-400 py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                             <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No stories yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new story.</p>
                            <div class="mt-6">
                                <a href="{{ route('stories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    + Create first story
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $stories->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                window.Echo.channel('stories')
                    .listen('StoryCreated', (e) => {
                        const storiesList = document.getElementById('stories-list');
                        if (!storiesList) return;

                        // Xóa message "No stories" nếu có
                        const emptyState = storiesList.querySelector('.text-center');
                        if(emptyState) emptyState.remove();
                        
                        let imageTag = '';
                        if (e.imageUrlMedium) {
                            imageTag = `
                                <a href="/stories/${e.story.id}">
                                    <img src="${e.imageUrlMedium}"
                                         srcset="${e.imageUrlThumb} 400w, ${e.imageUrlMedium} 800w, ${e.imageUrlLarge} 1200w"
                                         sizes="(max-width: 800px) 100vw, 800px"
                                         alt="${e.story.title}"
                                         class="w-full h-auto object-cover"
                                         loading="lazy">
                                </a>
                            `;
                        }
                        
                        const content = e.story.content ? e.story.content.substring(0, 200).replace(/\n/g, '<br>') : '';
                        
                        // === ĐỒNG BỘ HTML CỦA JAVASCRIPT VỚI GIAO DIỆN MỚI ===
                        const newStoryHtml = `
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl transform hover:scale-105 transition-transform duration-300" id="story-${e.story.id}">
                                ${imageTag}
                                <div class="p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="font-bold text-lg text-indigo-700 dark:text-indigo-400">${e.story.user.name}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-sm ml-3">Just now</div>
                                    </div>
                                    <a href="/stories/${e.story.id}">
                                        <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 mb-3">${e.story.title}</h3>
                                    </a>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${content}</p>
                                </div>
                            </div>
                        `;
                        storiesList.insertAdjacentHTML('afterbegin', newStoryHtml);
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>