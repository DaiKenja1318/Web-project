<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Tự động thay đổi tiêu đề --}}
            {{ $story->exists ? __('Edit Story') : __('Create New Story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Sửa lại action của form cho nhất quán.
                         Giả sử bạn dùng route resource tên là 'stories' cho cả admin và người dùng.
                         Nếu route của bạn là 'admin.stories', hãy đổi cả hai. --}}
                    <form method="POST" action="{{ $story->exists ? route('stories.update', $story) : route('stories.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Nếu đây là form edit, cần phải giả phương thức PUT --}}
                        @if($story->exists)
                            @method('PUT')
                        @endif

                        {{-- Trường nhập Tiêu đề (Đã đúng) --}}
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $story->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- ========================================================= --}}
                        {{-- SỬA LỖI 1: Đổi name="body" thành name="content" --}}
                        {{-- ========================================================= --}}
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Content')" />
                            <textarea id="content" name="content" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="10">{{ old('content', $story->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        {{-- ========================================================= --}}
                        {{-- SỬA LỖI 2: Thêm trường upload ảnh --}}
                        {{-- ========================================================= --}}
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Image (Optional)')" />
                            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            
                            {{-- Hiển thị ảnh hiện tại nếu đang ở chế độ edit --}}
                            @if ($story->image)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">Current Image:</p>
                                    <img src="{{ Storage::url($story->image) }}" alt="{{ $story->title }}" class="mt-2 rounded-md" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>

                        {{-- Nút Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ $story->exists ? __('Update Story') : __('Create Story') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>