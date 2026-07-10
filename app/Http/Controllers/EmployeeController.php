<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('timeEntries')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.index')->with('success', 'Employee added successfully!');
    }

    public function manage()
    {
        $employees = Employee::all();
        return view('employees.manage', compact('employees'));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.index')->with('success', 'Employee deleted successfully!');
    }
}
