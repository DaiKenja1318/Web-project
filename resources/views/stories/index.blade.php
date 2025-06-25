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
                        {{-- ========================================================= --}}
                        {{-- PHẦN 1: SỬA LOGIC HIỂN THỊ KHI TẢI TRANG (PHP/BLADE) --}}
                        {{-- ========================================================= --}}
                        @forelse($stories as $story)
                            @if ($story->user)
                                <div class="p-6 border-b border-gray-200" id="story-{{ $story->id }}">
                                    <div class="flex items-center mb-4">
                                        <div class="font-bold text-lg">{{ $story->user->name }}</div>
                                        <div class="text-gray-500 text-sm ml-2">{{ $story->created_at->diffForHumans() }}</div>
                                    </div>
                                    <a href="{{ route('stories.show', $story) }}">
                                        {{-- Dùng file_type để quyết định hiển thị <img> hay <video> --}}
                                        @if($story->file_type === 'image' && $story->image)
                                            <img src="{{ asset('storage/' . $story->image) }}"
                                                 alt="{{ $story->title }}"
                                                 class="max-w-2xl mx-auto h-auto object-cover rounded-lg mb-4"
                                                 loading="lazy">
                                        @elseif($story->file_type === 'video' && $story->image)
                                            <video controls class="max-w-2xl mx-auto h-auto rounded-lg mb-4">
                                                <source src="{{ asset('storage/' . $story->image) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
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
    {{-- ============================================================= --}}
    {{-- PHẦN 2: SỬA LOGIC HIỂN THỊ KHI CÓ SỰ KIỆN REAL-TIME (JS) --}}
    {{-- ============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kiểm tra xem Echo đã được khởi tạo chưa
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('stories')
                    .listen('StoryCreated', (e) => {
                        console.log('New story received:', e); // Dòng này để debug, có thể xóa sau

                        const storiesList = document.getElementById('stories-list');
                        if (!storiesList) return;

                        // Xóa thông báo "No stories" nếu có
                        const noStories = storiesList.querySelector('.text-center');
                        if(noStories) {
                            noStories.remove();
                        }
                        
                        // Tạo thẻ media (ảnh hoặc video) dựa trên 'fileType' từ event
                        let mediaTag = '';
                        if (e.fileType === 'image' && e.fileUrl) {
                            mediaTag = `
                                <img src="${e.fileUrl}"
                                     alt="${e.story.title}"
                                     class="max-w-2xl mx-auto h-auto object-cover rounded-lg mb-4"
                                     loading="lazy">
                            `;
                        } else if (e.fileType === 'video' && e.fileUrl) {
                            mediaTag = `
                                <video controls class="max-w-2xl mx-auto h-auto rounded-lg mb-4">
                                    <source src="${e.fileUrl}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            `;
                        }
                        
                        // Giới hạn nội dung hiển thị
                        const content = e.story.content ? e.story.content.substring(0, 200) : '';

                        // Tạo khối HTML cho story mới
                        const newStoryHtml = `
                            <div class="p-6 border-b border-gray-200" id="story-${e.story.id}" style="opacity: 0; transform: translateY(-20px); transition: all 0.5s ease-out;">
                                <div class="flex items-center mb-4">
                                    <div class="font-bold text-lg">${e.story.user.name}</div>
                                    <div class="text-gray-500 text-sm ml-2">Just now</div>
                                </div>
                                <a href="/stories/${e.story.id}">
                                    ${mediaTag}  <!-- Chèn thẻ media vào đây -->
                                    <h3 class="text-2xl font-bold mb-2">${e.story.title}</h3>
                                </a>
                                <p class="text-gray-700">${content}</p>
                            </div>
                        `;

                        // Thêm story mới vào đầu danh sách
                        storiesList.insertAdjacentHTML('afterbegin', newStoryHtml);
                        
                        // Thêm hiệu ứng xuất hiện mượt mà
                        const newStoryElement = document.getElementById(`story-${e.story.id}`);
                        setTimeout(() => {
                            if(newStoryElement) {
                                newStoryElement.style.opacity = '1';
                                newStoryElement.style.transform = 'translateY(0)';
                            }
                        }, 50);

                    });
            } else {
                console.warn('Laravel Echo not configured.');
            }
        });
    </script>
    @endpush
</x-app-layout>