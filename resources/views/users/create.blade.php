<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New User') }}
        </h2>
    </x-slot>

    @section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Back to users
                </a>
            </div>
            
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Name') }}</label>
                    <input id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus />
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" value="{{ old('email') }}" required />
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password" required />
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password_confirmation" required />
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">Cancel</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Create User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endsection
</x-app-layout>