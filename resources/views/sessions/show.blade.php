<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Session Details') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <a href="{{ route('patients.sessions.index', $patient->id) }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Back to sessions
                </a>
            </div>

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Session for {{ $patient->name }}</h1>
                <div class="space-x-2">
                    <a href="{{ route('patients.sessions.edit', [$patient->id, $session->id]) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit Session</a>
                    <button type="button" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="if(confirm('Are you sure you want to delete this session?')) { document.getElementById('delete-form').submit(); }">
                        Delete Session
                    </button>
                    <form id="delete-form" action="{{ route('patients.sessions.destroy', [$patient->id, $session->id]) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date and Time</h3>
                        <p>{{ $session->date->format('Y-m-d H:i') }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Weight</h3>
                        <p>{{ $session->weight ? $session->weight . ' kg' : 'Not recorded' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Fats Rate</h3>
                        <p>{{ $session->fats_rate ? $session->fats_rate . ' %' : 'Not recorded' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Burn Rate</h3>
                        <p>{{ $session->burn_rate ? $session->burn_rate : 'Not recorded' }}</p>
                    </div>
                </div>
            </div>
            
            @if($session->notes)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    {{ $session->notes }}
                </div>
            </div>
            @endif
            
            <div class="mt-6">
                <h3 class="font-medium text-lg text-gray-900 mb-2">{{ __('Medications') }}</h3>
                
                @if($session->medications->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($session->medications as $medication)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $medication->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $medication->pivot->quantity }} {{ $medication->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No medications were prescribed for this session.</p>
                @endif
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>