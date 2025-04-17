<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medications') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Medications</h1>
                <a href="{{ route('medications.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add New Medication
                </a>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('medications.index') }}" class="mb-6">
                <div class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medications..." 
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
                        <a href="{{ route('medications.index') }}" class="text-blue-600 hover:underline ml-2">
                            Clear search
                        </a>
                    </p>
                </div>
            @endif
            
            <!-- Medications Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medications as $medication)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $medication->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ Str::limit($medication->description, 50) }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $medication->quantity_in_stock }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $medication->unit }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-right">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('medications.edit', $medication->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="if(confirm('Are you sure you want to delete this medication?')) { document.getElementById('delete-form-{{ $medication->id }}').submit(); }">
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $medication->id }}" action="{{ route('medications.destroy', $medication->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 border-b border-gray-200 text-center">
                                @if(request('search'))
                                    No medications found matching "{{ request('search') }}".
                                @else
                                    No medications found.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $medications->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>