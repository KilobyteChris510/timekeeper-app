@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Manage Time Entries</h2>
        <div class="flex gap-2">
            <a href="{{ route('time.create') }}" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm">
                Add New Entry
            </a>
            <a href="{{ route('admin.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Back to Admin
            </a>
        </div>
    </div>

    <form action="{{ route('time.manage') }}" method="GET" class="flex flex-wrap items-end gap-3 mb-6 p-4 bg-[#1a1a1a] rounded border border-gray-700">
        <div class="flex-1 min-w-[220px]">
            <label for="employee_id" class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">
                Filter by employee
            </label>
            <select id="employee_id" name="employee_id" class="w-full bg-[#262626] border border-gray-600 text-gray-200 rounded px-3 py-2 text-sm focus:border-green-600 focus:ring-green-600">
                <option value="">All employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) request('employee_id') === (string) $employee->id)>
                        {{ $employee->employee_number }} - {{ $employee->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm">
            Filter
        </button>
        @if(request()->filled('employee_id'))
            <a href="{{ route('time.manage') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Clear
            </a>
        @endif
    </form>

    @if($timeEntries->isEmpty())
        <p class="text-gray-400 text-center py-8 text-sm">No time entries found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#1a1a1a] border-b border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Start Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">End Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Hours</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($timeEntries as $entry)
                        <tr class="hover:bg-[#2a2a2a] transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-200 font-medium">{{ $entry->employee_name }}</div>
                                <div class="text-xs text-gray-400">{{ $entry->employee_number }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                {{ $entry->date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300 font-mono">
                                {{ $entry->start_time->format('g:i A') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300 font-mono">
                                {{ $entry->end_time ? $entry->end_time->format('g:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-200 font-mono font-semibold">
                                {{ $entry->hours_logged ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($entry->end_time)
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-gray-800 text-gray-400 border border-gray-700">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-green-900/30 text-green-400 border border-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('time.edit', $entry) }}" class="bg-blue-700 hover:bg-blue-600 text-white font-medium py-1.5 px-3 rounded border border-blue-600 transition text-xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('time.destroy', $entry) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this time entry?')" class="bg-red-800 hover:bg-red-700 text-white font-medium py-1.5 px-3 rounded border border-red-700 transition text-xs">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $timeEntries->links() }}
        </div>
    @endif
</div>
@endsection
