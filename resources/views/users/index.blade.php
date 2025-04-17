<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Users</h1>
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add New User
                </a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('users.index') }}" class="mb-6">
                <div class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                           class="w-full border-gray-300 rounded-l-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-r-md hover:bg-gray-700">
                        Search
                    </button>
                </div>
            </form>
            
            @if(request('search'))
                <div class="mb-4">
                    <p class="text-gray-600">
                        Search results for: <span class="font-semibold">{{ request('search') }}</span>
                        <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline ml-2">
                            Clear search
                        </a>
                    </p>
                </div>
            @endif
            
            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-right">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">
                                            Delete
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 border-b border-gray-200 text-center">
                                @if(request('search'))
                                    No users found matching "{{ request('search') }}".
                                @else
                                    No users found.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>