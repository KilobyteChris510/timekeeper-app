<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    public function start(Employee $employee)
    {
        $activeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereNull('end_time')
            ->first();

        if ($activeEntry) {
            return redirect()->route('employees.index')->with('error', 'Employee already has an active time entry!');
        }

        TimeEntry::create([
            'employee_id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'employee_name' => $employee->full_name,
            'start_time' => now(),
            'date' => now()->toDateString(),
        ]);

        return redirect()->route('employees.index')->with('success', 'Time tracking started!');
    }

    public function stop(Employee $employee)
    {
        $activeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereNull('end_time')
            ->first();

        if (!$activeEntry) {
            return redirect()->route('employees.index')->with('error', 'No active time entry found!');
        }

        $endTime = now();
        
        $totalSeconds = $endTime->timestamp - $activeEntry->start_time->timestamp;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        $timeLogged = $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);

        $activeEntry->update([
            'end_time' => $endTime,
            'hours_logged' => $timeLogged,
        ]);

        return redirect()->route('employees.index')->with('success', 'Time tracking stopped!');
    }

    public function exportPage()
    {
        return view('time-entries.export');
    }

    public function exportAll()
    {
        $timeEntries = TimeEntry::with('employee')
            ->whereNotNull('end_time')
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'time_records_all_' . now()->format('Y-m-d_His') . '.csv';

        return $this->generateCsvExport($timeEntries, $filename);
    }

    public function exportRange(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $timeEntries = TimeEntry::with('employee')
            ->whereNotNull('end_time')
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'time_records_' . $validated['start_date'] . '_to_' . $validated['end_date'] . '.csv';

        return $this->generateCsvExport($timeEntries, $filename);
    }

    private function generateCsvExport($timeEntries, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($timeEntries) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Employee Number', 'Employee Name', 'Date', 'Start Time', 'End Time', 'Hours Logged']);

            foreach ($timeEntries as $entry) {
                fputcsv($file, [
                    $entry->employee_number,
                    $entry->employee_name,
                    $entry->date->format('Y-m-d'),
                    $entry->start_time->format('Y-m-d H:i:s'),
                    $entry->end_time->format('Y-m-d H:i:s'),
                    $entry->hours_logged,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function manage()
    {
        $timeEntries = TimeEntry::with('employee')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('time-entries.manage', compact('timeEntries'));
    }

    public function create()
    {
        $employees = Employee::orderBy('employee_number')->get();
        return view('time-entries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        
        $startDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endDateTime = $validated['end_time'] ? Carbon::parse($validated['date'] . ' ' . $validated['end_time']) : null;

        $hoursLogged = null;
        if ($endDateTime) {
            $totalSeconds = $endDateTime->timestamp - $startDateTime->timestamp;
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;
            $hoursLogged = $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
        }

        TimeEntry::create([
            'employee_id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'employee_name' => $employee->full_name,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'hours_logged' => $hoursLogged,
            'date' => $validated['date'],
        ]);

        return redirect()->route('time.manage')->with('success', 'Time entry added successfully!');
    }

    public function edit(TimeEntry $timeEntry)
    {
        $employees = Employee::orderBy('employee_number')->get();
        return view('time-entries.edit', compact('timeEntry', 'employees'));
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        
        $startDateTime = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $endDateTime = $validated['end_time'] ? Carbon::parse($validated['date'] . ' ' . $validated['end_time']) : null;

        $hoursLogged = null;
        if ($endDateTime) {
            $totalSeconds = $endDateTime->timestamp - $startDateTime->timestamp;
            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;
            $hoursLogged = $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
        }

        $timeEntry->update([
            'employee_id' => $employee->id,
            'employee_number' => $employee->employee_number,
            'employee_name' => $employee->full_name,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'hours_logged' => $hoursLogged,
            'date' => $validated['date'],
        ]);

        return redirect()->route('time.manage')->with('success', 'Time entry updated successfully!');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $timeEntry->delete();
        return redirect()->route('time.manage')->with('success', 'Time entry deleted successfully!');
    }
}
