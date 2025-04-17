<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Details') }}
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
                <h1 class="text-2xl font-semibold">{{ $patient->name }}</h1>
                <div class="space-x-2">
                    <a href="{{ route('patients.sessions.create', $patient->id) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">New Session</a>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Edit Patient</a>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Email</h3>
                        <p>{{ $patient->email ?? 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                        <p>{{ $patient->phone ?? 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date of Birth</h3>
                        <p>{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : 'Not provided' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Age</h3>
                        <p>{{ $patient->age ? $patient->age . ' ' . __('Years') : 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Address</h3>
                        <p>{{ $patient->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Recent Sessions</h2>
                    <a href="{{ route('patients.sessions.index', $patient->id) }}" class="text-blue-600 hover:text-blue-900">View all sessions</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fats Rate</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Burn Rate</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->sessions()->latest('date')->take(5)->get() as $session)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $session->date->format('Y-m-d H:i') }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $session->weight ?? 'N/A' }} kg</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $session->fats_rate ?? 'N/A' }} %</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $session->burn_rate ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-right">
                                    <a href="{{ route('patients.sessions.show', [$patient->id, $session->id]) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 border-b border-gray-200 text-center">No sessions found. <a href="{{ route('patients.sessions.create', $patient->id) }}" class="text-blue-600 hover:text-blue-900">Create the first session</a>.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>