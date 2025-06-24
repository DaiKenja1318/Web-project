<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Tự động thay đổi tiêu đề: Nếu story đã tồn tại thì là "Edit", ngược lại là "Create" --}}
            {{ $story->exists ? __('Edit Story') : __('Create New Story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Bắt đầu Form --}}
                    {{-- Action của form sẽ tự động trỏ đến route update hoặc store tùy vào ngữ cảnh --}}
                    <form method="POST" action="{{ $story->exists ? route('admin.stories.update', $story) : route('stories.store') }}">
                        
                        {{-- ========================================================= --}}
                        {{-- SỬA LỖI 419: THÊM DÒNG @csrf NÀY VÀO. ĐÂY LÀ PHẦN QUAN TRỌNG NHẤT --}}
                        @csrf
                        {{-- ========================================================= --}}

                        {{-- Nếu đây là form edit, cần phải giả phương thức PUT --}}
                        @if($story->exists)
                            @method('PUT')
                        @endif

                        {{-- Trường nhập Tiêu đề --}}
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $story->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        {{-- Trường nhập Nội dung --}}
                        <div class="mt-4">
                            <x-input-label for="body" :value="__('Content')" />
                            {{-- Sử dụng textarea cho nội dung dài --}}
                            <textarea id="body" name="body" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('body', $story->body) }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        {{-- Trường chọn Trạng thái --}}
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="draft" {{ old('status', $story->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $story->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                        
                        {{-- Nút Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{-- Tự động đổi chữ trên nút bấm --}}
                                {{ $story->exists ? __('Update') : __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                    {{-- Kết thúc Form --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>