{{-- File: resources/views/stories/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Stories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Hiển thị danh sách các story --}}
                    @forelse ($stories as $story)
                        <div class="mb-4 p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-bold">
                                <a href="{{ route('stories.show', $story) }}" class="hover:underline">
                                    {{ $story->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                By {{ $story->user->name }} on {{ $story->created_at->format('M d, Y') }}
                            </p>
                            <p class="mt-2">
                                {{ Str::limit($story->body, 150) }}
                            </p>
                        </div>
                    @empty
                        <p>No stories found.</p>
                    @endforelse

                    {{-- Hiển thị phân trang --}}
                    <div class="mt-4">
                        {{ $stories->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>