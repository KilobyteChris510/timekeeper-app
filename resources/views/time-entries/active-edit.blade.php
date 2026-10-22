@extends('layouts.app')

@section('content')
<div class="bg-[#262626] rounded shadow-lg p-6 border border-gray-700 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-medium text-gray-200">Edit Active Time Entry</h2>
            <p class="text-sm text-gray-400 mt-1">{{ $employee->employee_number }} - {{ $employee->full_name }}</p>
        </div>
        <a href="{{ route('employees.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
            Back
        </a>
    </div>

    <form id="active-entry-form" action="{{ route('time.active.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-300 mb-2">Start Date and Time</label>
            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time', $timeEntry->start_time->format('Y-m-d\TH:i')) }}" required class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
            @error('start_time')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="end_time" class="block text-sm font-medium text-gray-300 mb-2">End Date and Time</label>
            <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time', $timeEntry->end_time ? $timeEntry->end_time->format('Y-m-d\TH:i') : '') }}" class="w-full bg-[#1a1a1a] border border-gray-600 text-gray-200 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 p-2.5">
            @error('end_time')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Leave empty to keep the timer running. Enter a value to complete the entry.</p>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('employees.index') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">
                Cancel
            </a>
            <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded border border-blue-600 transition text-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>
<div id="active-edit-confirmation-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4" role="dialog" aria-modal="true" aria-labelledby="active-edit-confirmation-title">
    <div class="w-full max-w-md bg-[#262626] rounded-lg shadow-xl border border-gray-700 p-6">
        <h3 id="active-edit-confirmation-title" class="text-lg font-medium text-gray-200">Review time before saving</h3>
        <p class="text-sm text-gray-400 mt-2">
            Please double-check the details for <span class="text-gray-200 font-medium">{{ $employee->full_name }}</span>.
        </p>
        <div class="mt-5 space-y-3 bg-[#1a1a1a] rounded border border-gray-700 p-4 text-sm">
            <div class="flex justify-between gap-4">
                <span class="text-gray-400">Started</span>
                <span id="active-edit-review-start" class="text-gray-200 font-mono text-right"></span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-gray-400">End time</span>
                <span id="active-edit-review-end" class="text-gray-200 font-mono text-right"></span>
            </div>
        </div>
        <p id="active-edit-review-message" class="text-xs text-gray-500 mt-4"></p>
        <div class="flex justify-end gap-2 mt-6">
            <button type="button" id="cancel-active-edit-confirmation" class="bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium py-2 px-4 rounded border border-gray-600 transition text-sm">Cancel</button>
            <button type="button" id="continue-active-edit" class="bg-blue-700 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded border border-blue-600 transition text-sm">Edit Entry</button>
            <button type="button" id="confirm-active-edit" class="bg-green-700 hover:bg-green-600 text-white font-medium py-2 px-4 rounded border border-green-600 transition text-sm">Confirm &amp; Save</button>
        </div>
    </div>
</div>

<script>
    const activeEntryForm = document.getElementById('active-entry-form');
    const activeEditConfirmationModal = document.getElementById('active-edit-confirmation-modal');
    const cancelActiveEditConfirmation = document.getElementById('cancel-active-edit-confirmation');
    const continueActiveEdit = document.getElementById('continue-active-edit');
    const confirmActiveEdit = document.getElementById('confirm-active-edit');

    function formatReviewDateTime(value) {
        if (!value) {
            return 'Still running';
        }

        return new Date(value).toLocaleString([], {
            dateStyle: 'medium',
            timeStyle: 'short'
        });
    }

    activeEntryForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const hasEndTime = document.getElementById('end_time').value !== '';
        document.getElementById('active-edit-review-start').textContent = formatReviewDateTime(document.getElementById('start_time').value);
        document.getElementById('active-edit-review-end').textContent = formatReviewDateTime(document.getElementById('end_time').value);
        document.getElementById('active-edit-review-message').textContent = hasEndTime
            ? 'Saving this end time will complete the time entry.'
            : 'The timer will continue running after these changes are saved.';
        confirmActiveEdit.textContent = hasEndTime ? 'Confirm & Complete' : 'Confirm & Save';
        activeEditConfirmationModal.classList.remove('hidden');
    });

    function closeActiveEditConfirmation() {
        activeEditConfirmationModal.classList.add('hidden');
    }

    cancelActiveEditConfirmation.addEventListener('click', closeActiveEditConfirmation);
    continueActiveEdit.addEventListener('click', closeActiveEditConfirmation);
    confirmActiveEdit.addEventListener('click', function() {
        activeEntryForm.submit();
    });

    activeEditConfirmationModal.addEventListener('click', function(event) {
        if (event.target === activeEditConfirmationModal) {
            closeActiveEditConfirmation();
        }
    });
</script>

@endsection
