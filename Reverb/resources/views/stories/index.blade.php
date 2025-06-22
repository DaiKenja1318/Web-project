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
                        {{-- SỬA LẠI VÒNG LẶP NÀY CHO AN TOÀN --}}
                        @forelse($stories as $story)
                            {{-- Thêm câu lệnh @if để kiểm tra user có tồn tại không --}}
                            @if ($story->user)
                                <div class="p-6 border-b border-gray-200" id="story-{{ $story->id }}">
                                    <div class="flex items-center mb-4">
                                        {{-- Tên người dùng được hiển thị an toàn --}}
                                        <div class="font-bold text-lg">{{ $story->user->name }}</div>
                                        <div class="text-gray-500 text-sm ml-2">{{ $story->created_at->diffForHumans() }}</div>
                                    </div>
                                    <a href="{{ route('stories.show', $story) }}">
                                        @if($story->image)
                                            <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="w-full h-auto object-cover rounded-lg mb-4">
                                        @endif
                                        <h3 class="text-2xl font-bold mb-2">{{ $story->title }}</h3>
                                    </a>
                                    {{-- Dùng {!! !!} để giữ lại các định dạng cơ bản nếu có, và dùng Str helper --}}
                                    <p class="text-gray-700">{!! \Illuminate\Support\Str::limit(nl2br(e($story->content)), 200) !!}</p>
                                </div>
                            @endif
                        @empty
                            {{-- Hiển thị thông báo khi không có story nào --}}
                            <div class="text-center text-gray-500 py-10">
                                <p>No stories have been posted yet.</p>
                                <p>Be the first to <a href="{{ route('stories.create') }}" class="text-blue-500 hover:underline">create one</a>!</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Hiển thị phân trang --}}
                    <div class="mt-6">
                        {{ $stories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Code Javascript an toàn hơn --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                window.Echo.channel('stories')
                    .listen('StoryCreated', (e) => {
                        if (!e || !e.story || !e.story.user) {
                            console.error('Dữ liệu story nhận được không hợp lệ:', e);
                            return;
                        }

                        const storiesList = document.getElementById('stories-list');
                        if (!storiesList) return;

                        const imageUrl = e.story.image ? `/storage/${e.story.image}` : '';
                        const imageTag = e.story.image ? `<img src="${imageUrl}" alt="${e.story.title}" class="w-full h-auto object-cover rounded-lg mb-4">` : '';
                        const content = e.story.content ? e.story.content.substring(0, 200) : '';

                        const newStoryHtml = `
                            <div class="p-6 border-b border-gray-200" id="story-${e.story.id}">
                                <div class="flex items-center mb-4">
                                    <div class="font-bold text-lg">${e.story.user.name}</div>
                                    <div class="text-gray-500 text-sm ml-2">Vừa xong</div>
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