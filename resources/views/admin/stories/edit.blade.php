<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $story->exists ? __('Edit Story') : __('Create New Story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ $story->exists ? route('admin.stories.update', $story) : route('admin.stories.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                @csrf
                @if($story->exists)
                    @method('PUT')
                @endif
                
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Story Details</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Provide the basic information for the story.</p>
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $story->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="content" :value="__('Content')" />
                        <textarea id="content" name="content" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="10">{{ old('content', $story->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                     <div>
                        <x-input-label for="image" :value="__('Cover Image')" />
                        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        
                        @if ($story->image)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Current Image:</p>
                                <img src="{{ Storage::url($story->image) }}" alt="{{ $story->title }}" class="mt-2 rounded-md" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <div x-data="{ status: '{{ old('status', $story->status) ?? 'draft' }}' }">
                        <x-input-label for="status" :value="__('Status')" class="mb-2" />
                        <input type="hidden" name="status" :value="status">
                        <div class="flex items-center space-x-4">
                            <button type="button" @click="status = (status === 'draft' ? 'published' : 'draft')" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800" :class="{ 'bg-indigo-600': status === 'published', 'bg-gray-200 dark:bg-gray-600': status === 'draft' }" role="switch" :aria-checked="status === 'published' ? 'true' : 'false'">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="{ 'translate-x-5': status === 'published', 'translate-x-0': status === 'draft' }"></span>
                            </button>
                            <div class="flex items-center">
                                <span x-show="status === 'published'" class="flex items-center text-green-600 dark:text-green-400 font-semibold">Published</span>
                                <span x-show="status === 'draft'" class="flex items-center text-gray-600 dark:text-gray-400 font-semibold">Draft</span>
                            </div>
                        </div>
                         <p class="mt-2 text-sm text-gray-500">Select "Published" to make this story visible to everyone.</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-x-4 bg-gray-50 dark:bg-gray-800/50 px-6 py-4">
                    <a href="{{ route('admin.stories.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">Cancel</a>
                    <x-primary-button>
                        {{ $story->exists ? __('Update Story') : __('Create Story') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>