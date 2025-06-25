<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $story->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Story Content -->
                    
                    {{-- BẮT ĐẦU PHẦN SỬA LẠI HÌNH ẢNH --}}
                    @if($story->image)
                        @php
                            // Tách đường dẫn để tạo tên file cho các phiên bản khác
                            $pathInfo = pathinfo($story->image);
                            $directory = $pathInfo['dirname'];
                            $filename = $pathInfo['basename'];

                            // Tạo URL đầy đủ cho từng phiên bản ảnh
                            $largeUrl = Storage::url($story->image);
                            $mediumUrl = Storage::url($directory . '/medium_' . $filename);
                            $thumbUrl = Storage::url($directory . '/thumb_' . $filename);
                        @endphp

                        {{-- Sử dụng srcset để trình duyệt tự chọn ảnh phù hợp nhất --}}
                        <img src="{{ $mediumUrl }}" {{-- Ảnh mặc định cho trình duyệt cũ --}}
                             srcset="{{ $thumbUrl }} 400w, 
                                     {{ $mediumUrl }} 800w, 
                                     {{ $largeUrl }} 1200w"
                             sizes="(max-width: 800px) 100vw, 800px" {{-- Hướng dẫn trình duyệt chọn size nào --}}
                             alt="{{ $story->title }}"
                             class="max-w-xl mx-auto h-auto object-cover rounded-lg mb-4"
                             loading="lazy"> {{-- Thêm lazy loading để tăng tốc tải trang --}}
                    @endif
                    {{-- KẾT THÚC PHẦN SỬA LẠI HÌNH ẢNH --}}
                    
                    <div class="flex items-center mb-4">
                        <div class="font-bold text-lg">{{ $story->user->name }}</div>
                        <div class="text-gray-500 text-sm ml-2">{{ $story->created_at->format('M d, Y') }}</div>
                    </div>
                    <p class="text-gray-800 text-lg leading-relaxed">{{ $story->content }}</p>

                    <hr class="my-6">

                    <!-- Comments Section -->
                    <h3 class="text-xl font-bold mb-4">Comments</h3>

                    <!-- Post a comment form -->
                    @auth
                    <form method="POST" action="{{ route('comments.store', $story) }}" class="mb-6">
                        @csrf
                        <textarea name="content" rows="3" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Write a comment..." required></textarea>
                        <x-primary-button class="mt-2">
                            Post Comment
                        </x-primary-button>
                    </form>
                    @else
                    <p class="mb-6"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in</a> to post a comment.</p>
                    @endauth

                    <!-- Comments List -->
                    <div id="comments-list" class="space-y-4">
                        @forelse ($story->comments->sortByDesc('created_at') as $comment) {{-- Sắp xếp comment mới nhất lên đầu --}}
                            <div class="flex space-x-3" id="comment-{{$comment->id}}">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="font-semibold">{{ $comment->user->name }}</p>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <p id="no-comments">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Phần Javascript không thay đổi --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                window.Echo.private('stories.{{ $story->id }}')
                    .listen('CommentCreated', (e) => {
                        console.log('New comment received:', e.comment);
                        const commentsList = document.getElementById('comments-list');
                        const noCommentsEl = document.getElementById('no-comments');
                        if(noCommentsEl) {
                            noCommentsEl.remove();
                        }

                        const newCommentHtml = `
                            <div class="flex space-x-3" id="comment-${e.comment.id}">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600">
                                        ${e.comment.user.name.substring(0, 1)}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-100 p-3 rounded-lg">
                                        <p class="font-semibold">${e.comment.user.name}</p>
                                        <p class="text-gray-700">${e.comment.content}</p>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Just now</div>
                                </div>
                            </div>
                        `;
                        commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>