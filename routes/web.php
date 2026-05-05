<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;

// Redirect root to patients index (or a dashboard)
Route::get('/', function () {
    return redirect()->route('patients.index');
});

// CRUD Resource Routes
Route::resource('departments', DepartmentController::class);
Route::resource('services', ServiceController::class);
Route::resource('staff', StaffController::class);
Route::resource('patients', PatientController::class);
Route::resource('schedules', ScheduleController::class);
Route::resource('appointments', AppointmentController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('user_accounts', UserController::class);

Route::get('/invoices/{id}/manage-items', [InvoiceController::class, 'manageItems'])->name('invoices.items.manage');
Route::post('/invoices/{id}/items', [InvoiceController::class, 'storeItem'])->name('invoices.items.store');
Route::delete('/invoices/{invoice}/items/{item}', [InvoiceController::class, 'destroyItem'])->name('invoices.items.destroy');