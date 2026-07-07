<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::get('/employees/manage', [EmployeeController::class, 'manage'])->name('employees.manage');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::post('/employees/{employee}/start', [TimeEntryController::class, 'start'])->name('time.start');
Route::post('/employees/{employee}/stop', [TimeEntryController::class, 'stop'])->name('time.stop');
Route::get('/time-entries/export', [TimeEntryController::class, 'export'])->name('time.export');
