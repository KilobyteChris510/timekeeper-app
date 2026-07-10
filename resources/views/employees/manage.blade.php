@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Manage Employees</h2>
        <a href="{{ route('admin.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Back to Admin
        </a>
    </div>

    @if($employees->isEmpty())
        <p class="text-gray-400 text-center py-8 text-sm">No employees found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#1a1a1a] border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Employee #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($employees as $employee)
                        <tr class="hover:bg-[#2a2a2a] transition">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-300">{{ $employee->employee_number }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-200">{{ $employee->full_name }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right">
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete {{ $employee->full_name }}? Their time entries will be preserved.')" class="bg-gray-800 hover:bg-gray-700 text-gray-400 font-medium py-1.5 px-4 rounded border border-gray-700 transition text-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
