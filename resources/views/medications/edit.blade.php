<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Medication') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <a href="{{ route('medications.index') }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Back to medications
                </a>
            </div>
            
            <form method="POST" action="{{ route('medications.update', $medication->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Medication Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $medication->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="3"
                           class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $medication->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="quantity_in_stock" :value="__('Quantity in Stock')" />
                    <x-text-input id="quantity_in_stock" class="block mt-1 w-full" type="number" min="0" name="quantity_in_stock" :value="old('quantity_in_stock', $medication->quantity_in_stock)" required />
                    <x-input-error :messages="$errors->get('quantity_in_stock')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <x-input-label for="unit" :value="__('Unit')" />
                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $medication->unit)" required />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    <p class="text-sm text-gray-500 mt-1">Examples: pcs, tablets, bottles, boxes, etc.</p>
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('medications.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancel</a>
                    <x-primary-button>
                        {{ __('Update Medication') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @endsection
</x-app-layout>