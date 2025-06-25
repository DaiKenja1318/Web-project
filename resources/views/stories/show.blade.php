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
                    
                    @if($story->image)
                        <img src="{{ Storage::url($story->image) }}" 
                             alt="{{ $story->title }}"
                             class="max-w-2xl mx-auto h-auto object-cover rounded-lg mb-4"
                             loading="lazy">
                    @endif
                    
                    <div class="flex items-center mb-4">
                        <div class="font-bold text-lg">{{ $story->user->name }}</div>
                        <div class="text-gray-500 text-sm ml-2">{{ $story->created_at->format('M d, Y') }}</div>
                    </div>
                    <p class="text-gray-800 text-lg leading-relaxed">{!! nl2br(e($story->content)) !!}</p>

                    <hr class="my-6">

                    <!-- Comments Section ... -->
                    {{-- Phần comment giữ nguyên --}}

                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    {{-- Phần Javascript giữ nguyên, không thay đổi --}}
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