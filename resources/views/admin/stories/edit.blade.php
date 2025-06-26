<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Tiêu đề tự động thay đổi, không cần sửa --}}
            {{ $story->exists ? __('Chỉnh sửa Truyện') : __('Tạo Truyện Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Thêm enctype để form có thể upload file --}}
            <form method="POST" action="{{ $story->exists ? route('stories.update', $story) : route('stories.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                @csrf
                @if($story->exists)
                    @method('PUT')
                @endif
                
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Chi tiết Truyện
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Điền các thông tin cơ bản cho truyện của bạn.
                        </p>
                    </div>

                    {{-- Trường nhập Tiêu đề (Title) --}}
                    <div>
                        <x-input-label for="title" :value="__('Tiêu đề')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $story->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- SỬA LỖI: Đổi name="body" thành name="content" để khớp với database --}}
                    <div>
                        <x-input-label for="content" :value="__('Nội dung')" />
                        <textarea id="content" name="content" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="10">{{ old('content', $story->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>
                     <div>
                        <x-input-label for="image" :value="__('Ảnh bìa')" />
                        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        @if ($story->image)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Ảnh hiện tại:</p>
                                <img src="{{ Storage::url($story->image) }}" alt="{{ $story->title }}" class="mt-2 rounded-md" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <div x-data="{ status: '{{ old('status', $story->status) ?? 'draft' }}' }">
                        <x-input-label for="status" :value="__('Trạng thái')" class="mb-2" />
                        <input type="hidden" name="status" :value="status">
                        <div class="flex items-center space-x-4">
                             <!-- The Toggle Switch -->
                            <button type="button" @click="status = (status === 'draft' ? 'published' : 'draft')"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                    :class="{ 'bg-indigo-600': status === 'published', 'bg-gray-200 dark:bg-gray-600': status === 'draft' }"
                                    role="switch" :aria-checked="status === 'published' ? 'true' : 'false'">
                                <span aria-hidden="true"
                                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="{ 'translate-x-5': status === 'published', 'translate-x-0': status === 'draft' }"></span>
                            </button>
                             <!-- The Status Label -->
                            <div class="flex items-center">
                                <span x-show="status === 'published'" class="flex items-center text-green-600 dark:text-green-400 font-semibold">
                                     <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Đã xuất bản
                                </span>
                                <span x-show="status === 'draft'" class="flex items-center text-gray-600 dark:text-gray-400 font-semibold">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Bản nháp
                                </span>
                            </div>
                        </div>
                         <p class="mt-2 text-sm text-gray-500">
                        </p>
                    </div>
                </div>
                
                {{-- Khu vực nút bấm đã được làm đẹp hơn --}}
                <div class="flex items-center justify-end gap-x-4 bg-gray-50 dark:bg-gray-800/50 px-6 py-4">
                    <a href="{{ route('stories.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">Hủy</a>
                    <x-primary-button>
                        {{ $story->exists ? __('') : __('') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>