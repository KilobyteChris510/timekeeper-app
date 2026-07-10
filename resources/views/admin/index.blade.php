@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Admin Panel</h2>
        <a href="{{ route('employees.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Back to Time Tracking
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Export CSV Card -->
        <a href="{{ route('time.export') }}" class="bg-[#1a1a1a] hover:bg-[#2a2a2a] border border-gray-700 rounded-lg p-6 transition group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-gray-700 group-hover:bg-gray-600 rounded-full p-4 mb-4 transition">
                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200 mb-2">Export to CSV</h3>
                <p class="text-sm text-gray-400">Export all entries or by date range</p>
            </div>
        </a>

        <!-- Add New Employee Card -->
        <a href="{{ route('employees.create') }}" class="bg-[#1a1a1a] hover:bg-[#2a2a2a] border border-gray-700 rounded-lg p-6 transition group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-gray-700 group-hover:bg-gray-600 rounded-full p-4 mb-4 transition">
                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200 mb-2">Add New Employee</h3>
                <p class="text-sm text-gray-400">Register a new employee in the system</p>
            </div>
        </a>

        <!-- Remove Employee Card -->
        <a href="{{ route('employees.manage') }}" class="bg-[#1a1a1a] hover:bg-[#2a2a2a] border border-gray-700 rounded-lg p-6 transition group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-gray-700 group-hover:bg-gray-600 rounded-full p-4 mb-4 transition">
                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200 mb-2">Remove Employee</h3>
                <p class="text-sm text-gray-400">Delete employees from the system</p>
            </div>
        </a>

        <!-- Manage Time Entries Card -->
        <a href="{{ route('time.manage') }}" class="bg-[#1a1a1a] hover:bg-[#2a2a2a] border border-gray-700 rounded-lg p-6 transition group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-gray-700 group-hover:bg-gray-600 rounded-full p-4 mb-4 transition">
                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-200 mb-2">Manage Time Entries</h3>
                <p class="text-sm text-gray-400">Add, edit, or delete time logs</p>
            </div>
        </a>
    </div>
</div>
@endsection
