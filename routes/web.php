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

use App\Http\Controllers\Auth\EmployeeAuthController;
use App\Http\Controllers\Dashboard\DashboardEmployeeController;
use App\Http\Controllers\Pelatihan\JenisPelatihanController;
use App\Http\Controllers\Pelatihan\PelatihanController;
use App\Http\Controllers\Riwayat\RiwayatJabatanController;
use App\Http\Controllers\Riwayat\RiwayatKeluargaController;
use App\Http\Controllers\Riwayat\RiwayatKontrakController;
use App\Http\Controllers\Riwayat\RiwayatLainController;
use App\Http\Controllers\Riwayat\RiwayatPelatihanController;
use App\Http\Controllers\Riwayat\RiwayatPendidikanController;
use App\Http\Controllers\Riwayat\RiwayatSippController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Setting\UserController;

Route::view('/', 'indexs');

Route::get('adminlogin', [AdminAuthController::class, 'adminlogin'])->name('adminlogin');
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
    Route::put('/employee/{id}/update-password', [EmployeeController::class, 'updatePassword'])->name('employee.update_password');
    Route::post('/employee/import', [EmployeeController::class, 'import'])->name('employee.import');
    Route::get('/employee/viewImport', [EmployeeController::class, 'viewImport'])->name('employee.viewImport');
    Route::get('/download-import-template', [EmployeeController::class, 'downloadImportTemplate'])->name('employee.download.import.template');

    Route::resource('setting/role', RoleController::class);
    Route::resource('setting/user', UserController::class);
    Route::resource('pelatihan/jenispelatihan', JenisPelatihanController::class);
    Route::resource('pelatihan/pelatihan', PelatihanController::class);

    Route::resource('riwayat/riwayat_pendidikan', RiwayatPendidikanController::class);
    Route::resource('riwayat/riwayat_jabatan', RiwayatJabatanController::class);
    Route::resource('riwayat/riwayat_keluarga', RiwayatKeluargaController::class);
    Route::resource('riwayat/riwayat_sipp', RiwayatSippController::class);
    Route::resource('riwayat/riwayat_kontrak', RiwayatKontrakController::class);
    Route::resource('riwayat/riwayat_lain', RiwayatLainController::class);
    Route::resource('riwayat/riwayat_pelatihan', RiwayatPelatihanController::class);
    Route::post('/riwayat/riwayat_pelatihan/direct-store', [RiwayatPelatihanController::class, 'directstore'])->name('riwayat_pelatihan.directstore');
    
    
});

Route::get('employeelogin', [EmployeeAuthController::class, 'employeelogin'])->name('employeelogin');
Route::post('actionloginemployee', [EmployeeAuthController::class, 'actionloginemployee'])->name('actionloginemployee');

Route::middleware(['auth'])->group(function () {

    Route::get('dashboardEmployee', [DashboardEmployeeController::class, 'index'])->name('dashboardEmployee');
    Route::get('actionlogout', [EmployeeAuthController::class, 'actionlogout'])->name('actionlogout');
});