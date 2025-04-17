<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-semibold mb-6">Clinic Management Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Patients</h2>
                    <p class="text-4xl font-bold">{{ App\Models\Patient::count() }}</p>
                    <div class="mt-4">
                        <a href="{{ route('patients.index') }}" class="text-blue-600 hover:text-blue-800">View all patients →</a>
                    </div>
                </div>
                
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Today's Sessions</h2>
                    <p class="text-4xl font-bold">
                        {{ App\Models\PatientSession::whereDate('date', today())->count() }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('patients.index') }}" class="text-green-600 hover:text-green-800">Manage sessions →</a>
                    </div>
                </div>
                
                <div class="bg-yellow-100 p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Medications</h2>
                    <p class="text-4xl font-bold">{{ App\Models\Medication::count() }}</p>
                    <div class="mt-4">
                        <a href="{{ route('medications.index') }}" class="text-yellow-600 hover:text-yellow-800">Manage inventory →</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-10">
                <h2 class="text-xl font-semibold mb-4">Recent Patients</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Session</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Patient::latest()->take(5)->get() as $patient)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $patient->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $patient->email }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $patient->phone }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    @if($patient->sessions->count() > 0)
                                        {{ $patient->sessions->sortByDesc('date')->first()->date->format('Y-m-d') }}
                                    @else
                                        No sessions
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200 text-right">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if(App\Models\Patient::count() === 0)
                            <tr>
                                <td colspan="5" class="py-4 px-4 border-b border-gray-200 text-center">No patients found. <a href="{{ route('patients.create') }}" class="text-blue-600 hover:text-blue-900">Add your first patient</a>.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-10">
                <h2 class="text-xl font-semibold mb-4">Low Stock Medications</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medication</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Medication::where('quantity_in_stock', '<', 10)->get() as $medication)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $medication->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 {{ $medication->quantity_in_stock < 5 ? 'text-red-600 font-bold' : '' }}">{{ $medication->quantity_in_stock }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $medication->unit }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 text-right">
                                    <a href="{{ route('medications.edit', $medication->id) }}" class="text-blue-600 hover:text-blue-900">Update Stock</a>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if(App\Models\Medication::where('quantity_in_stock', '<', 10)->count() === 0)
                            <tr>
                                <td colspan="4" class="py-4 px-4 border-b border-gray-200 text-center">No medications with low stock.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-app-layout>