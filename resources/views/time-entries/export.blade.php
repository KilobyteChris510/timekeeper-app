@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Export Time Entries</h2>
        <a href="{{ route('admin.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Back to Admin
        </a>
    </div>

    <div class="space-y-4">
        <!-- Export All -->
        <div class="bg-[#1a1a1a] border border-gray-700 rounded-lg p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-200 mb-2">Export All Time Entries</h3>
                    <p class="text-sm text-gray-400 mb-4">Download all completed time entries as a CSV file</p>
                </div>
                <a href="{{ route('time.export.all') }}" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-6 rounded border border-green-600 transition text-sm whitespace-nowrap">
                    Export All
                </a>
            </div>
        </div>

        <!-- Export Date Range -->
        <div class="bg-[#1a1a1a] border border-gray-700 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-200 mb-2">Export by Date Range</h3>
            <p class="text-sm text-gray-400 mb-4">Download time entries within a specific date range</p>
            
            <form action="{{ route('time.export.range') }}" method="GET">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->subDays(30)->toDateString()) }}" required class="w-full bg-[#0f0f0f] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', now()->toDateString()) }}" required class="w-full bg-[#0f0f0f] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-900/20 border border-red-800 rounded text-sm text-red-400">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded border border-blue-600 transition text-sm">
                        Export Date Range
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
