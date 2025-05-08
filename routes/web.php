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
use App\Http\Controllers\Employee\PegawaiController;
use App\Http\Controllers\Keuangan\DefaultGajiController;
use App\Http\Controllers\Keuangan\SettingGajiController;
use App\Http\Controllers\Keuangan\SlipGajiController;
use App\Http\Controllers\Navmenus\NavmenuController;
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
use App\Http\Controllers\Setting\WebSettingController;

Route::view('/', 'indexs')->name('indexs');

Route::post('actionlogin', [AdminAuthController::class, 'actionlogin'])->name('actionlogin');
Route::post('actionloginemployee', [EmployeeAuthController::class, 'actionloginemployee'])->name('actionloginemployee');
Route::get('/logout/admin', [AdminAuthController::class, 'logoutAdmin'])->name('logoutAdmin');
Route::get('/keuangan/slip_gaji/slip-gaji-karyawan', [SlipGajiController::class, 'slipGajiKaryawan']);

Route::prefix('navmenu')->middleware(['auth.check:admin', 'dynamic.role'])->name('navmenu.')->group(function () {
    Route::get('indexmenu', [NavmenuController::class, 'indexmenu'])->name('indexmenu');
    Route::get('data/', [NavmenuController::class, 'index'])->name('index');
    Route::post('update/', [NavmenuController::class, 'update'])->name('update');    
    Route::post('store/', [NavmenuController::class, 'store'])->name('store');
    Route::get('destroy/{id}', [NavmenuController::class, 'destroy'])->name('destroy');
    Route::get('get-navmenu/{roleId}', [NavmenuController::class, 'getNavmenu']);
    Route::post('/update-hakakses', [NavmenuController::class, 'updateHakakses']);
});

Route::middleware(['auth.check:admin', 'dynamic.role'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('actionlogout', [AdminAuthController::class, 'actionlogout'])->name('actionlogout');
    //setting Web
    Route::resource('setting/websetting', WebSettingController::class);

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
    Route::get('/employee/export', [EmployeeController::class, 'export'])->name('employee.export');
    Route::get('/employee/index/trash', [EmployeeController::class, 'indexTrash'])->name('employee.indexTrash');
    Route::get('/employee/restore', [EmployeeController::class, 'restore'])->name('employee.restore');
    Route::get('/employee/trash', [EmployeeController::class, 'trash'])->name('employee.trash');

    Route::resource('setting/role', RoleController::class);
    Route::resource('setting/user', UserController::class);
    Route::resource('pelatihan/jenispelatihan', JenisPelatihanController::class);
    Route::get('/pelatihan/pelatihan/report-pelatihan', [PelatihanController::class, 'report'])->name('pelatihan.report');
    Route::resource('pelatihan/pelatihan', PelatihanController::class);

    Route::resource('riwayat/riwayat_pendidikan', RiwayatPendidikanController::class);
    Route::resource('riwayat/riwayat_jabatan', RiwayatJabatanController::class);
    Route::resource('riwayat/riwayat_keluarga', RiwayatKeluargaController::class);
    Route::resource('riwayat/riwayat_sipp', RiwayatSippController::class);
    Route::resource('riwayat/riwayat_kontrak', RiwayatKontrakController::class);
    Route::resource('riwayat/riwayat_lain', RiwayatLainController::class);
    Route::resource('riwayat/riwayat_pelatihan', RiwayatPelatihanController::class);
    Route::post('/riwayat/riwayat_pelatihan/direct-store', [RiwayatPelatihanController::class, 'directstore'])->name('riwayat_pelatihan.directstore'); 
    
    //keuangan    
    Route::get('keuangan/setting_gaji/export-gaji', [SettingGajiController::class, 'exportEmployeeGaji'])->name('setting_gaji.exportEmployeeGaji');
    Route::post('keuangan/setting_gaji/import-gaji', [SettingGajiController::class, 'importEmployeeGaji'])->name('setting_gaji.importEmployeeGaji');
    Route::resource('keuangan/default_gaji', DefaultGajiController::class);
    Route::resource('keuangan/setting_gaji', SettingGajiController::class);
    Route::post('keuangan/setting_gaji/storeOrUpdate/{id}', [SettingGajiController::class, 'storeOrUpdate'])->name('setting_gaji.storeOrUpdate');

    Route::resource('keuangan/slip_gaji', SlipGajiController::class);
    Route::post('/keuangan/slip-gaji/store-all', [SlipGajiController::class, 'storeAllSlip'])->name('slip_gaji.storeAllSlip');
    Route::get('keuangan/slip-gaji/index-send-all', [SlipGajiController::class, 'IndexSendSlip'])->name('slip_gaji.IndexSendSlip');
    Route::get('keuangan/slip_gaji/cetakpdf/{id}/{bulan}/{tahun}', [SlipGajiController::class, 'CetakSlipPenggajian'])->name('slip_gaji.CetakSlipPenggajian');
    Route::post('keuangan/slip_gaji/send-all', [SlipGajiController::class, 'SendAllSlip'])->name('slip_gaji.SendAllSlip');
    Route::get('keuangan/slip_gaji/send/{id}/{bulan}/{tahun}', [SlipGajiController::class, 'SendSlip'])->name('slip_gaji.SendSlip');

    //inventaris
    Route::post('inventaris/storeSign', [\App\Http\Controllers\Inventaris\InventarisController::class, 'storeSign'])->name('inventaris.storeSign');
    Route::resource('inventaris/inventaris', \App\Http\Controllers\Inventaris\InventarisController::class);
    Route::get('inventaris/indexChecker', [\App\Http\Controllers\Inventaris\InventarisController::class, 'indexChecker'])->name('inventaris.indexChecker');
    Route::get('inventaris/cetakQrBarang/{id}', [\App\Http\Controllers\Inventaris\InventarisController::class, 'cetakQrBarang'])->name('inventaris.cetakQrBarang');
    Route::post('inventaris/cetakQrBarangAll', [\App\Http\Controllers\Inventaris\InventarisController::class, 'cetakQrBarangBulk'])->name('inventaris.cetakQrBarangBulk');
    Route::get('inventaris/cetakQrRuang/{id}', [\App\Http\Controllers\Inventaris\InventarisController::class, 'cetakQrRuang'])->name('inventaris.cetakQrRuang');
    Route::post('inventaris/cetakQrRuangAll', [\App\Http\Controllers\Inventaris\InventarisController::class, 'cetakQrRuangBulk'])->name('inventaris.cetakQrRuangBulk');

});

Route::get('employeelogin', [EmployeeAuthController::class, 'employeelogin'])->name('employeelogin');
Route::post('actionloginemployee', [EmployeeAuthController::class, 'actionloginemployee'])->name('actionloginemployee');

Route::middleware(['auth.check:pegawai'])->group(function () {
    Route::get('/fitur-tertentu', function () {
        return view('maintenance');
    })->name('feature.maintenance')->middleware('feature.maintenance');    
    Route::get('dashboardEmployee', [DashboardEmployeeController::class, 'index'])->name('dashboardEmployee');
    Route::get('pegawai/profile', [PegawaiController::class, 'profile'])->name('pegawai.profile');
    Route::get('pegawai/profile-edit', [PegawaiController::class, 'editProfile'])->name('pegawai.editProfile');
    Route::get('pegawai/profile-update/{id}', [PegawaiController::class, 'updateProfile'])->name('pegawai.updateProfile');
    Route::get('pegawai/riwayat_pendidikan', [PegawaiController::class, 'riwayatPendidikan'])->name('pegawai.riwayat_pendidikan');
    Route::get('pegawai/riwayat_jabatan', [PegawaiController::class, 'riwayatJabatan'])->name('pegawai.riwayat_jabatan');
    Route::get('pegawai/riwayat_keluarga', [PegawaiController::class, 'riwayatKeluarga'])->name('pegawai.riwayat_keluarga');
    Route::get('pegawai/riwayat_sipp', [PegawaiController::class, 'riwayatSipp'])->name('pegawai.riwayat_sipp');
    Route::get('pegawai/riwayat_kontrak', [PegawaiController::class, 'riwayatKontrak'])->name('pegawai.riwayat_kontrak');
    Route::get('pegawai/riwayat_lain', [PegawaiController::class, 'riwayatLain'])->name('pegawai.riwayat_lain');
    Route::get('pegawai/riwayat_pelatihan', [PegawaiController::class, 'riwayatPelatihan'])->name('pegawai.riwayat_pelatihan');
    Route::get('pegawai/presensi', [PegawaiController::class, 'presensi'])->name('pegawai.presensi');
    Route::get('pegawai/jadwal_presensi', [PegawaiController::class, 'jadwalPresensi'])->name('pegawai.jadwal_presensi');
    Route::get('pegawai/riwayat_presensi', [PegawaiController::class, 'riwayatPresensi'])->name('pegawai.riwayat_presensi');
    Route::get('pegawai/gaji', [PegawaiController::class, 'gaji'])->name('pegawai.gaji');
    // Route::get('actionlogout', [EmployeeAuthController::class, 'actionlogout'])->name('actionlogout');
});
Route::get('/logout/pegawai', [EmployeeAuthController::class, 'logoutPegawai'])->name('logoutPegawai');