<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::get('/employees/manage', [EmployeeController::class, 'manage'])->name('employees.manage');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

Route::post('/employees/{employee}/start', [TimeEntryController::class, 'start'])->name('time.start');
Route::post('/employees/{employee}/stop', [TimeEntryController::class, 'stop'])->name('time.stop');
Route::get('/time-entries/export', [TimeEntryController::class, 'exportPage'])->name('time.export');
Route::get('/time-entries/export/all', [TimeEntryController::class, 'exportAll'])->name('time.export.all');
Route::get('/time-entries/export/range', [TimeEntryController::class, 'exportRange'])->name('time.export.range');
Route::get('/time-entries/manage', [TimeEntryController::class, 'manage'])->name('time.manage');
Route::get('/time-entries/create', [TimeEntryController::class, 'create'])->name('time.create');
Route::post('/time-entries', [TimeEntryController::class, 'store'])->name('time.store');
Route::get('/time-entries/{timeEntry}/edit', [TimeEntryController::class, 'edit'])->name('time.edit');
Route::put('/time-entries/{timeEntry}', [TimeEntryController::class, 'update'])->name('time.update');
Route::delete('/time-entries/{timeEntry}', [TimeEntryController::class, 'destroy'])->name('time.destroy');
