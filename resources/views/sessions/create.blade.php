<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Session for') }} {{ $patient->name }}
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
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Error!</strong> Please check the form for errors.
                    @if($errors->has('general'))
                        <p>{{ $errors->first('general') }}</p>
                    @endif
                </div>
            @endif
            
            <form method="POST" action="{{ route('patients.sessions.store', $patient->id) }}" id="session-form">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block font-medium text-sm text-gray-700">{{ __('Date & Time') }} <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="date" name="date" value="{{ old('date') }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        @error('date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block font-medium text-sm text-gray-700">{{ __('Weight (kg)') }}</label>
                        <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight') }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('weight')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fats Rate -->
                    <div>
                        <label for="fats_rate" class="block font-medium text-sm text-gray-700">{{ __('Fats Rate (%)') }}</label>
                        <input type="number" step="0.01" id="fats_rate" name="fats_rate" value="{{ old('fats_rate') }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('fats_rate')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Burn Rate -->
                    <div>
                        <label for="burn_rate" class="block font-medium text-sm text-gray-700">{{ __('Burn Rate') }}</label>
                        <input type="number" step="0.01" id="burn_rate" name="burn_rate" value="{{ old('burn_rate') }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('burn_rate')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block font-medium text-sm text-gray-700">{{ __('Notes') }}</label>
                    <textarea id="notes" name="notes" rows="3" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-2">{{ __('Medications (Optional)') }}</h3>
                    <p class="text-sm text-gray-500 mb-4">Add medications prescribed during this session, if any.</p>
                    
                    <div id="medications-container">
                        <!-- Initially empty - no medication rows shown -->
                        <p class="text-gray-500 italic mb-2">Click "Add Medication" to prescribe medications for this session, or leave empty if no medications are needed.</p>
                    </div>
                    
                    <button type="button" id="add-medication" class="mt-2 px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-gray-100 hover:bg-gray-200">
                        + Add Medication
                    </button>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('patients.sessions.index', $patient->id) }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500">
                        {{ __('Create Session') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript for dynamic medication fields -->
    <script>
    let medicationIndex = -1; // Start at -1 since we'll increment on first click
    
    document.getElementById('add-medication').addEventListener('click', function() {
        medicationIndex++;
        
        const container = document.getElementById('medications-container');
        
        // Clear the initial message if this is the first medication
        if (medicationIndex === 0) {
            container.innerHTML = '';
        }
        
        const newItem = document.createElement('div');
        newItem.className = 'border rounded-md p-4 mb-2 medication-item';
        
        newItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium text-sm text-gray-700">{{ __('Medication') }}</label>
                    <select name="medications[${medicationIndex}][id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">-- Select Medication --</option>
                        @foreach($medications as $medication)
                            <option value="{{ $medication->id }}" data-stock="{{ $medication->quantity_in_stock }}"
                                {{ $medication->quantity_in_stock <= 0 ? 'class=text-red-600' : '' }}>
                                {{ $medication->name }} 
                                ({{ $medication->quantity_in_stock }} {{ $medication->unit }} available)
                                {!! $medication->quantity_in_stock <= 0 ? ' - <strong>OUT OF STOCK</strong>' : '' !!}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700">{{ __('Quantity') }}</label>
                    <input type="number" min="1" name="medications[${medicationIndex}][quantity]" value="1" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeMedication(this)" class="text-red-600 px-3 py-1 mt-1">
                        Remove
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newItem);
    });
    
    function removeMedication(button) {
        const item = button.closest('.medication-item');
        const container = document.getElementById('medications-container');
        
        item.remove();
        
        // If all medications have been removed, show the "optional" message again
        if (container.querySelectorAll('.medication-item').length === 0) {
            container.innerHTML = '<p class="text-gray-500 italic mb-2">Click "Add Medication" to prescribe medications for this session, or leave empty if no medications are needed.</p>';
            medicationIndex = -1; // Reset the index if all medications are removed
        }
    }
    
    // Form submission validation
    document.getElementById('session-form').addEventListener('submit', function(e) {
        const medicationItems = document.querySelectorAll('.medication-item');
        let hasAnyMedication = false;
        
        // Check if any medication is selected
        medicationItems.forEach(item => {
            const selectEl = item.querySelector('select');
            if (selectEl && selectEl.value) {
                hasAnyMedication = true;
            }
        });
        
        // If no medications are selected, form can be submitted without further validation
        if (!hasAnyMedication) {
            return;
        }
        
        // Check each medication item only if it has a selected medication
            medicationItems.forEach(item => {
                const selectEl = item.querySelector('select');
                const quantityEl = item.querySelector('input[type="number"]');
                
                if (selectEl && selectEl.value) {
                    // If medication is selected, check quantity
                    if (!quantityEl.value || parseInt(quantityEl.value) < 1) {
                        e.preventDefault();
                        alert('Please enter a valid quantity for each selected medication.');
                        return;
                    }
                    
                    // Check stock availability
                    const selectedOption = selectEl.options[selectEl.selectedIndex];
                    const availableStock = parseInt(selectedOption.dataset.stock);
                    const requestedQuantity = parseInt(quantityEl.value);
                    
                    if (requestedQuantity > availableStock) {
                        e.preventDefault();
                        const medName = selectedOption.text.split('(')[0].trim();
                        
                        if (availableStock <= 0) {
                            alert(`${medName} is currently out of stock. Please select a different medication or update your inventory.`);
                        } else {
                            alert(`Insufficient stock for ${medName}. Only ${availableStock} available.`);
                        }
                        return;
                    }
                }
            });
        });
    </script>
    @endsection
</x-app-layout>