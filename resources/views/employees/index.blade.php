@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Employee Time Tracking</h2>
        <div class="flex gap-2">
            <a href="{{ route('time.export') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Export to CSV
            </a>
            <a href="{{ route('employees.manage') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Remove
            </a>
            <a href="{{ route('employees.create') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Add New
            </a>
        </div>
    </div>

    @if($employees->isEmpty())
        <p class="text-gray-400 text-center py-8 text-sm">No employees found. Click "Add New" to get started.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#1a1a1a] border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Employee #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($employees as $employee)
                        @php
                            $activeEntry = $employee->timeEntries()->whereNull('end_time')->first();
                            $isActive = $activeEntry !== null;
                        @endphp
                        <tr class="hover:bg-[#2a2a2a] transition">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-300">{{ $employee->employee_number }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-200">{{ $employee->full_name }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if($isActive)
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-gray-700 text-gray-200 border border-gray-600">
                                        Active
                                    </span>
                                    <span class="text-xs text-gray-400 ml-2 font-mono" id="timer-{{ $employee->id }}">
                                        <span class="timer" data-start="{{ $activeEntry->start_time->toIso8601String() }}"></span>
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-gray-800 text-gray-500 border border-gray-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right">
                                @if($isActive)
                                    <form action="{{ route('time.stop', $employee) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-1.5 px-4 rounded border border-gray-600 transition text-sm">
                                            Stop
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('time.start', $employee) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-1.5 px-4 rounded border border-gray-600 transition text-sm">
                                            Start
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    function updateTimers() {
        document.querySelectorAll('.timer').forEach(timer => {
            const startTime = new Date(timer.dataset.start);
            const now = new Date();
            const diffMs = now - startTime;
            
            if (diffMs < 0) {
                timer.textContent = '0:00:00';
                return;
            }
            
            const totalSeconds = Math.floor(diffMs / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            
            timer.textContent = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        });
    }

    if (document.querySelectorAll('.timer').length > 0) {
        updateTimers();
        setInterval(updateTimers, 1000);
    }
</script>
@endsection
