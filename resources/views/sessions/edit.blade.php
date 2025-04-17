<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Session for') }} {{ $patient->name }}
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
            
            <form method="POST" action="{{ route('patients.sessions.update', [$patient->id, $session->id]) }}" id="session-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block font-medium text-sm text-gray-700">{{ __('Date & Time') }} <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="date" name="date" value="{{ old('date', $session->date->format('Y-m-d\TH:i')) }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        @error('date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block font-medium text-sm text-gray-700">{{ __('Weight (kg)') }}</label>
                        <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight', $session->weight) }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('weight')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fats Rate -->
                    <div>
                        <label for="fats_rate" class="block font-medium text-sm text-gray-700">{{ __('Fats Rate (%)') }}</label>
                        <input type="number" step="0.01" id="fats_rate" name="fats_rate" value="{{ old('fats_rate', $session->fats_rate) }}" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('fats_rate')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Burn Rate -->
                    <div>
                        <label for="burn_rate" class="block font-medium text-sm text-gray-700">{{ __('Burn Rate') }}</label>
                        <input type="number" step="0.01" id="burn_rate" name="burn_rate" value="{{ old('burn_rate', $session->burn_rate) }}" 
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
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('notes', $session->notes) }}</textarea>
                    @error('notes')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Medications (Optional) -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-700 mb-2">{{ __('Medications (Optional)') }}</h3>
                    <p class="text-sm text-gray-500 mb-4">Add medications prescribed during this session, if any.</p>
                    
                    <div id="medications-container">
                        @if($session->medications->count() > 0)
                            @foreach($session->medications as $index => $sessionMed)
                                <div class="border rounded-md p-4 mb-2 medication-item">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block font-medium text-sm text-gray-700">{{ __('Medication') }}</label>
                                            <select name="medications[{{ $index }}][id]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">-- Select Medication --</option>
                                                @foreach($medications as $medication)
                                                    @php
                                                        // Calculate available stock, adding back this session's quantity if it's the same medication
                                                        $adjustedStock = $medication->quantity_in_stock;
                                                        if($medication->id == $sessionMed->id) {
                                                            $adjustedStock += $sessionMed->pivot->quantity;
                                                        }
                                                        
                                                        // For selected medications, always show them even if out of stock
                                                        $showOption = $adjustedStock > 0 || $medication->id == $sessionMed->id;
                                                    @endphp
                                                    
                                                    @if($showOption)
                                                        <option value="{{ $medication->id }}" 
                                                                data-stock="{{ $adjustedStock }}" 
                                                                data-current-qty="{{ $medication->id == $sessionMed->id ? $sessionMed->pivot->quantity : 0 }}"
                                                                {{ $medication->id == $sessionMed->id ? 'selected' : '' }}>
                                                            {{ $medication->name }} ({{ $adjustedStock }} {{ $medication->unit }} available)
                                                            @if($medication->id == $sessionMed->id && $adjustedStock <= $sessionMed->pivot->quantity)
                                                                - Currently using {{ $sessionMed->pivot->quantity }}
                                                            @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-sm text-gray-700">{{ __('Quantity') }}</label>
                                            <input type="number" min="1" name="medications[{{ $index }}][quantity]" value="{{ $sessionMed->pivot->quantity }}" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <input type="hidden" name="medications[{{ $index }}][original_id]" value="{{ $sessionMed->id }}">
                                            <input type="hidden" name="medications[{{ $index }}][original_quantity]" value="{{ $sessionMed->pivot->quantity }}">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" onclick="removeMedication(this)" class="text-red-600 px-3 py-1 mt-1">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Initially show a message indicating medications are optional -->
                            <p class="text-gray-500 italic mb-2">Click "Add Medication" to prescribe medications for this session, or leave empty if no medications are needed.</p>
                        @endif
                    </div>
                    
                    <button type="button" id="add-medication" class="mt-2 px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-gray-100 hover:bg-gray-200">
                        + Add Medication
                    </button>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('patients.sessions.show', [$patient->id, $session->id]) }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500">
                        {{ __('Update Session') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript for dynamic medication fields -->
    <script>
        let medicationIndex = {{ $session->medications->count() > 0 ? $session->medications->count() - 1 : -1 }};
        
        document.getElementById('add-medication').addEventListener('click', function() {
            medicationIndex++;
            
            const container = document.getElementById('medications-container');
            
            // Clear the initial message if this is the first medication
            if (medicationIndex === 0 && container.querySelector('p.italic')) {
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
                                @if($medication->quantity_in_stock > 0)
                                    <option value="{{ $medication->id }}" data-stock="{{ $medication->quantity_in_stock }}">
                                        {{ $medication->name }} ({{ $medication->quantity_in_stock }} {{ $medication->unit }} available)
                                    </option>
                                @endif
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
            
            // If all medications have been removed, show the "optional" message
            if (container.querySelectorAll('.medication-item').length === 0) {
                container.innerHTML = '<p class="text-gray-500 italic mb-2">Click "Add Medication" to prescribe medications for this session, or leave empty if no medications are needed.</p>';
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
                    const currentQty = parseInt(selectedOption.dataset.currentQty || 0);
                    const requestedQuantity = parseInt(quantityEl.value);
                    
                    // Only check stock if we're increasing the quantity or using a different medication
                    if (requestedQuantity > availableStock && requestedQuantity > currentQty) {
                        e.preventDefault();
                        alert(`Insufficient stock for ${selectedOption.text.split('(')[0].trim()}. Only ${availableStock} available.`);
                        return;
                    }
                }
            });
        });
    </script>
    @endsection
</x-app-layout>