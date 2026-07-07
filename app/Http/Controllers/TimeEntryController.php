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

    public function export()
    {
        $timeEntries = TimeEntry::with('employee')
            ->whereNotNull('end_time')
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'time_records_' . now()->format('Y-m-d_His') . '.csv';

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
}
