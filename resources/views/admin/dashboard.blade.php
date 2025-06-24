<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Stories Management</h3>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Title</th>
                                    <th scope="col" class="px-6 py-3">Author</th>
                                    <th scope="col" class="px-6 py-3">Created At</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stories as $story)
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $story->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ Str::limit($story->title, 40) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $story->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $story->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 flex items-center gap-2">
                                        <a href="{{ route('admin.stories.edit', $story) }}" class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-md hover:bg-blue-600 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this story?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center px-6 py-4">
                                        No stories found to manage.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>