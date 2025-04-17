<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Patient') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Back to patient
                </a>
            </div>
            
            <form method="POST" action="{{ route('patients.update', $patient->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $patient->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $patient->email)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="phone" :value="__('Phone')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $patient->phone)" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="address" :value="__('Address')" />
                    <textarea id="address" name="address" rows="3"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('address', $patient->address) }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                    <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d'))" />
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('patients.show', $patient->id) }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancel</a>
                    <x-primary-button>
                        {{ __('Update Patient') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @endsection
</x-app-layout>