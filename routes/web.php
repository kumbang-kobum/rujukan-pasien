<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RujukanController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\SOAPController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BerkasMedisController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RumahSakitController;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/berkas/file/{filename}', function ($filename) {
    $path = storage_path('app/public/berkas/'.$filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

Route::middleware(['auth'])->group(function () {

    // ADMIN ONLY
    Route::middleware('role:admin')->group(function () {
        // Admin
        Route::resource('rumahsakit', RumahSakitController::class)->names('rumahsakit');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/admin/password', [AdminController::class, 'editPassword'])->name('admin.password.edit');
        Route::post('/admin/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');
        Route::resource('admin/pegawai', AdminController::class);
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AJAX: dokter by RS
    Route::get('/ajax/dokter-by-rs/{rs}', [UserController::class, 'dokterByRs'])
        ->name('ajax.dokter-by-rs');

    Route::resource('users', UserController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('soap', SOAPController::class);
    Route::resource('rujukan', RujukanController::class);
    Route::resource('berkas', BerkasMedisController::class);

    // Kunjungan routes
    Route::get('soap/{soap}/cetak', [\App\Http\Controllers\SOAPController::class,'cetak'])->name('soap.cetak');
    Route::patch('/kunjungan/{kunjungan}/pulangkan', [KunjunganController::class, 'pulangkan'])
        ->name('kunjungan.pulangkan');
    Route::get('kunjungan/cetak', [KunjunganController::class, 'cetak'])
        ->name('kunjungan.cetak');
    Route::resource('kunjungan', KunjunganController::class);
    Route::patch('/rujukan/{rujukan}/status/{status}', [RujukanController::class,'ubahStatus'])->name('rujukan.ubahStatus');

    // Default redirect setelah login
    Route::get('/', function () { 
        return redirect()->route('dashboard');
    });
});

require __DIR__.'/auth.php';