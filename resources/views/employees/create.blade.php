@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-[#262626] rounded shadow-lg p-6 border border-gray-700">
    <h2 class="text-xl font-medium text-gray-200 mb-6">Add New Employee</h2>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="employee_number" class="block text-gray-300 text-sm font-medium mb-2">Employee Number</label>
            <input type="text" 
                   name="employee_number" 
                   id="employee_number" 
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 bg-[#1a1a1a] text-gray-200 leading-tight focus:outline-none focus:border-gray-500 @error('employee_number') border-gray-500 @enderror"
                   value="{{ old('employee_number') }}"
                   required>
            @error('employee_number')
                <p class="text-gray-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="first_name" class="block text-gray-300 text-sm font-medium mb-2">First Name</label>
            <input type="text" 
                   name="first_name" 
                   id="first_name" 
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 bg-[#1a1a1a] text-gray-200 leading-tight focus:outline-none focus:border-gray-500 @error('first_name') border-gray-500 @enderror"
                   value="{{ old('first_name') }}"
                   required>
            @error('first_name')
                <p class="text-gray-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="last_name" class="block text-gray-300 text-sm font-medium mb-2">Last Name</label>
            <input type="text" 
                   name="last_name" 
                   id="last_name" 
                   class="appearance-none border border-gray-600 rounded w-full py-2 px-3 bg-[#1a1a1a] text-gray-200 leading-tight focus:outline-none focus:border-gray-500 @error('last_name') border-gray-500 @enderror"
                   value="{{ old('last_name') }}"
                   required>
            @error('last_name')
                <p class="text-gray-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 focus:outline-none transition text-sm">
                Add Employee
            </button>
            <a href="{{ route('employees.index') }}" class="inline-block align-baseline font-medium text-sm text-gray-400 hover:text-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
