@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-medium text-gray-200">Employee Time Tracking</h2>
        <a href="{{ route('admin.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Admin
        </a>
    </div>

    @if($employees->isEmpty())
        <p class="text-gray-400 text-center py-8 text-sm">No employees found. Please add employees from the Admin panel.</p>
    @else
        @php
            $activeEmployees = $employees->filter(function($emp) {
                return $emp->timeEntries()->whereNull('end_time')->exists();
            });
            $inactiveEmployees = $employees->filter(function($emp) {
                return !$emp->timeEntries()->whereNull('end_time')->exists();
            });
        @endphp

        <!-- Employee Selection Dropdown -->
        <div class="mb-6">
            <label for="employee-select" class="block text-sm font-medium text-gray-300 mb-2">Select Employee to Start Tracking</label>
            <div class="flex gap-3">
                <select id="employee-select" class="flex-1 bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
                    <option value="">Choose an employee...</option>
                    @foreach($inactiveEmployees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->employee_number }} - {{ $employee->full_name }}</option>
                    @endforeach
                </select>
                <button id="start-tracking-btn" disabled class="bg-gray-700 hover:bg-gray-600 disabled:bg-gray-800 disabled:text-gray-500 disabled:cursor-not-allowed text-gray-200 font-medium py-2 px-6 rounded border border-gray-600 transition text-sm">
                    Start Tracking
                </button>
            </div>
        </div>

        <!-- Active Employees Table -->
        @if($activeEmployees->isEmpty())
            <div class="text-center py-12 bg-[#1a1a1a] rounded-lg border border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-400 text-sm">No active time tracking sessions</p>
                <p class="text-gray-500 text-xs mt-1">Select an employee above to start tracking time</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-[#1a1a1a] border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Employee #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Time Elapsed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($activeEmployees as $employee)
                            @php
                                $activeEntry = $employee->timeEntries()->whereNull('end_time')->first();
                            @endphp
                            <tr class="hover:bg-[#2a2a2a] transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $employee->employee_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200 font-medium">{{ $employee->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-green-900/30 text-green-400 border border-green-800">
                                            Active
                                        </span>
                                        <span class="text-base text-gray-200 font-mono font-semibold timer" data-start="{{ $activeEntry->start_time->toIso8601String() }}">
                                            00:00:00
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('time.active.edit', $employee) }}" class="inline-flex w-20 items-center justify-center bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded border border-blue-600 transition text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('time.stop', $employee) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="button" class="review-completion-btn inline-flex w-20 items-center justify-center bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm" data-employee="{{ $employee->full_name }}" data-start="{{ $activeEntry->start_time->format('M d, Y g:i A') }}">
                                                Done
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>

<div id="completion-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4" role="dialog" aria-modal="true" aria-labelledby="completion-modal-title">
    <div class="w-full max-w-md bg-[#262626] rounded-lg shadow-xl border border-gray-700 p-6">
        <h3 id="completion-modal-title" class="text-lg font-medium text-gray-200">Review time before completing</h3>
        <p class="text-sm text-gray-400 mt-2">
            Please double-check the details for <span id="review-employee" class="text-gray-200 font-medium"></span>.
        </p>
        <div class="mt-5 space-y-3 bg-[#1a1a1a] rounded border border-gray-700 p-4 text-sm">
            <div class="flex justify-between gap-4">
                <span class="text-gray-400">Started</span>
                <span id="review-start" class="text-gray-200 font-mono text-right"></span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-gray-400">Completing now</span>
                <span id="review-end" class="text-gray-200 font-mono text-right"></span>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Need to change the start or end time? Choose Edit Entry before completing.</p>
        <div class="flex justify-end gap-2 mt-6">
            <button type="button" id="cancel-completion" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">Cancel</button>
            <a id="edit-active-entry" href="#" class="bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded border border-blue-600 transition text-sm">Edit Entry</a>
            <button type="button" id="confirm-completion" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm">Confirm &amp; Complete</button>
        </div>
    </div>
</div>

<script>
    // Handle employee selection dropdown
    const employeeSelect = document.getElementById('employee-select');
    const startBtn = document.getElementById('start-tracking-btn');
    
    if (employeeSelect && startBtn) {
        employeeSelect.addEventListener('change', function() {
            startBtn.disabled = !this.value;
        });
        
        startBtn.addEventListener('click', function() {
            const employeeId = employeeSelect.value;
            if (employeeId) {
                // Create and submit a form to start tracking
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/employees/${employeeId}/start`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Review the current times before committing the active entry.
    const completionModal = document.getElementById('completion-modal');
    const cancelCompletion = document.getElementById('cancel-completion');
    const confirmCompletion = document.getElementById('confirm-completion');
    const editActiveEntry = document.getElementById('edit-active-entry');
    let completionForm = null;

    document.querySelectorAll('.review-completion-btn').forEach(button => {
        button.addEventListener('click', function() {
            completionForm = this.closest('form');
            document.getElementById('review-employee').textContent = this.dataset.employee;
            document.getElementById('review-start').textContent = this.dataset.start;
            document.getElementById('review-end').textContent = new Date().toLocaleString([], {
                dateStyle: 'medium',
                timeStyle: 'short'
            });
            editActiveEntry.href = this.closest('tr').querySelector('a[href*="time-entry/edit"]').href;
            completionModal.classList.remove('hidden');
        });
    });

    function closeCompletionModal() {
        completionModal.classList.add('hidden');
        completionForm = null;
    }

    cancelCompletion.addEventListener('click', closeCompletionModal);
    confirmCompletion.addEventListener('click', function() {
        if (completionForm) {
            completionForm.submit();
        }
    });

    completionModal.addEventListener('click', function(event) {
        if (event.target === completionModal) {
            closeCompletionModal();
        }
    });

    // Timer update function
    function updateTimers() {
        document.querySelectorAll('.timer').forEach(timer => {
            const startTime = new Date(timer.dataset.start);
            const now = new Date();
            const diffMs = now - startTime;
            
            if (diffMs < 0) {
                timer.textContent = '00:00:00';
                return;
            }
            
            const totalSeconds = Math.floor(diffMs / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            
            timer.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        });
    }

    if (document.querySelectorAll('.timer').length > 0) {
        updateTimers();
        setInterval(updateTimers, 1000);
    }
</script>
@endsection
