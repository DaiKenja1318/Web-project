<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stories Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @auth
                    <div class="mb-4">
                        <a href="{{ route('stories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Create New Story
                        </a>
                    </div>
                    @endauth

                    <div id="stories-list" class="space-y-6">
                        @forelse($stories as $story)
                            @if ($story->user)
                                <div class="p-6 border-b border-gray-200" id="story-{{ $story->id }}">
                                    <div class="flex items-center mb-4">
                                        <div class="font-bold text-lg">{{ $story->user->name }}</div>
                                        <div class="text-gray-500 text-sm ml-2">{{ $story->created_at->diffForHumans() }}</div>
                                    </div>
                                    <a href="{{ route('stories.show', $story) }}">
                                        @if($story->image)
                                            @php
                                                $pathInfo = pathinfo($story->image);
                                                $directory = $pathInfo['dirname'];
                                                $filename = $pathInfo['basename'];

                                                $largeUrl = Storage::url($story->image);
                                                $mediumUrl = Storage::url($directory . '/medium_' . $filename);
                                                $thumbUrl = Storage::url($directory . '/thumb_' . $filename);
                                            @endphp
                                            {{-- === BẮT ĐẦU SỬA Ở ĐÂY: THAY max-w-xl BẰNG max-w-2xl === --}}
                                            <img src="{{ $mediumUrl }}"
                                                 srcset="{{ $thumbUrl }} 400w, {{ $mediumUrl }} 800w, {{ $largeUrl }} 1200w"
                                                 sizes="(max-width: 800px) 100vw, 800px"
                                                 alt="{{ $story->title }}"
                                                 class="max-w-2xl mx-auto h-auto object-cover rounded-lg mb-4" {{-- <-- ĐÃ SỬA --}}
                                                 loading="lazy">
                                            {{-- === KẾT THÚC SỬA Ở ĐÂY === --}}
                                        @endif
                                        <h3 class="text-2xl font-bold mb-2">{{ $story->title }}</h3>
                                    </a>
                                    <p class="text-gray-700">{!! \Illuminate\Support\Str::limit(nl2br(e($story->content)), 200) !!}</p>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-gray-500 py-10">
                                <p>No stories have been posted yet.</p>
                                <p>Be the first to <a href="{{ route('stories.create') }}" class="text-blue-500 hover:underline">create one</a>!</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $stories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Phần Javascript giữ nguyên, không thay đổi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                window.Echo.channel('stories')
                    .listen('StoryCreated', (e) => {
                        // ...
                        const storiesList = document.getElementById('stories-list');
                        if (!storiesList) return;
                        
                        let imageTag = '';
                        if (e.imageUrlMedium) {
                             // ÁP DỤNG THAY ĐỔI VÀO CẢ JAVASCRIPT
                            imageTag = `
                                <img src="${e.imageUrlMedium}"
                                     srcset="${e.imageUrlThumb} 400w, ${e.imageUrlMedium} 800w, ${e.imageUrlLarge} 1200w"
                                     sizes="(max-width: 800px) 100vw, 800px"
                                     alt="${e.story.title}"
                                     class="max-w-2xl mx-auto h-auto object-cover rounded-lg mb-4"
                                     loading="lazy">
                            `;
                        }
                        
                        // ...
                        const content = e.story.content ? e.story.content.substring(0, 200) : '';

                        const newStoryHtml = `
                            <div class="p-6 border-b border-gray-200" id="story-${e.story.id}">
                                <div class="flex items-center mb-4">
                                    <div class="font-bold text-lg">${e.story.user.name}</div>
                                    <div class="text-gray-500 text-sm ml-2">Just now</div>
                                </div>
                                 <a href="/stories/${e.story.id}">
                                    ${imageTag}
                                    <h3 class="text-2xl font-bold mb-2">${e.story.title}</h3>
                                </a>
                                <p class="text-gray-700">${content}</p>
                            </div>
                        `;
                        storiesList.insertAdjacentHTML('afterbegin', newStoryHtml);
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>