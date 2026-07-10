@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Add Time Entry</h2>
        <a href="{{ route('time.manage') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Back
        </a>
    </div>

    <form action="{{ route('time.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="employee_id" class="block text-sm font-medium text-gray-300 mb-2">Employee</label>
            <select name="employee_id" id="employee_id" required class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                <option value="">Select an employee...</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->employee_number }} - {{ $employee->full_name }}
                    </option>
                @endforeach
            </select>
            @error('employee_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date', now()->toDateString()) }}" required class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
            @error('date')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-300 mb-2">Start Time</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                @error('start_time')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-300 mb-2">End Time</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                @error('end_time')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('time.manage') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Cancel
            </a>
            <button type="submit" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm">
                Add Entry
            </button>
        </div>
    </form>
</div>
@endsection
