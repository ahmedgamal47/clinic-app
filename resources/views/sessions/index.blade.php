<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sessions for') }} {{ $patient->name }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Back to patients
                </a>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Sessions for {{ $patient->name }}</h1>
                <a href="{{ route('patients.sessions.create', $patient->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Add New Session
                </a>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('patients.sessions.index', $patient->id) }}" class="mb-6">
                <div class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by notes or date..." 
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
                        <a href="{{ route('patients.sessions.index', $patient->id) }}" class="text-blue-600 hover:underline ml-2">
                            Clear search
                        </a>
                    </p>
                </div>
            @endif
            
            <!-- Sessions Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fats Rate</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Burn Rate</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sessions as $session)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $session->date->format('Y-m-d H:i') }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $session->weight ? $session->weight . ' kg' : '-' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $session->fats_rate ? $session->fats_rate . ' %' : '-' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $session->burn_rate ?: '-' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200 text-right">
                                <div class="flex space-x-2 justify-end">
                                    <a href="{{ route('patients.sessions.show', [$patient->id, $session->id]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('patients.sessions.edit', [$patient->id, $session->id]) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="if(confirm('Are you sure you want to delete this session?')) { document.getElementById('delete-form-{{ $session->id }}').submit(); }">
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $session->id }}" action="{{ route('patients.sessions.destroy', [$patient->id, $session->id]) }}" method="POST" class="hidden">
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
                                    No sessions found matching "{{ request('search') }}".
                                @else
                                    No sessions recorded for this patient yet.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $sessions->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>