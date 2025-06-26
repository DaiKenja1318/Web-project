<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Welcome, Admin!</h3>
                    <p class="mb-6">From this dashboard, you can manage the content of the website.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-indigo-50 dark:bg-indigo-900/50 p-6 rounded-lg shadow">
                            <h4 class="font-semibold text-lg text-indigo-800 dark:text-indigo-200">Manage Stories</h4>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">View, edit, and delete all user stories.</p>
                            <a href="{{ route('admin.stories.index') }}" class="inline-block mt-4 bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition-colors">
                                Go to Stories →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>