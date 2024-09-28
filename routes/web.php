<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Master\GolonganController;
use App\Http\Controllers\Master\PendidikanController;
use App\Http\Controllers\Master\UnitController;
use App\Http\Controllers\Master\ProfesiController;
use App\Http\Controllers\Master\StatusKaryawanController;
use App\Http\Controllers\Master\StatusKeluargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminAuthController::class, 'login'])->name('login');
Route::post('actionlogin', [AdminAuthController::class, 'actionlogin'])->name('actionlogin');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('actionlogout', [AdminAuthController::class, 'actionlogout'])->name('actionlogout');

    //data master data
    Route::resource('master/unit', UnitController::class);
    Route::resource('master/profesi', ProfesiController::class);
    Route::resource('master/pendidikan', PendidikanController::class);
    Route::resource('master/golongan', GolonganController::class);
    Route::resource('master/statuskaryawan', StatusKaryawanController::class);
    Route::resource('master/statuskeluarga', StatusKeluargaController::class);
    Route::resource('employee/employee', EmployeeController::class);
    Route::get('employees/data', [EmployeeController::class, 'getEmployees'])->name('employee.getEmployees');
    Route::post('/employee/import', [EmployeeController::class, 'import'])->name('employee.import');
    Route::get('/employee/viewImport', [EmployeeController::class, 'viewImport'])->name('employee.viewImport');
    Route::get('/download-import-template', [EmployeeController::class, 'downloadImportTemplate'])->name('employee.download.import.template');

});
