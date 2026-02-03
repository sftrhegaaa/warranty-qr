<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QrController;
use App\Http\Controllers\WarrantyController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukQrController;
use App\Http\Controllers\Admin\HistoryWarrantyController;
use App\Http\Controllers\Admin\HistoryUserController;
use App\Http\Controllers\Admin\DownloadStikerController;

use Illuminate\Support\Facades\Http;




// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin', function () {
    return redirect()->route('admin.users');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');

    Route::get('/produk-qr', [ProdukQrController::class, 'index'])->name('admin.produk_qr.index');
    // Route::get('/produk-qr', [ProdukQrController::class, 'index'])->name('admin.produk_qr');
    
    /* CREATE */
    Route::get('/produk-qr/create', [ProdukQrController::class, 'create'])->name('admin.produk_qr.create');
    Route::post('/produk-qr', [ProdukQrController::class, 'store'])->name('admin.produk_qr.store');

    /* UPDATE */
    Route::get('/produk-qr/{produk}/edit', [ProdukQrController::class, 'edit'])->name('admin.produk_qr.edit');

    Route::put('/produk-qr/{produk}', [ProdukQrController::class, 'update'])->name('admin.produk_qr.update');

    /* DELETE */
    Route::delete('/produk-qr/{produk}', [ProdukQrController::class, 'destroy'])->name('admin.produk_qr.destroy');

    /* Produk QR SVG */
    Route::get('/produk-qr/{id}/svg', [ProdukQrController::class, 'downloadSvg'])->name('admin.produk_qr.svg');

    /* History Warranty */
    Route::get('/warranty', [HistoryWarrantyController::class, 'index'])->name('admin.warranty.index');
    /* Warranty Data */
    Route::get('/warranty/data', [HistoryWarrantyController::class, 'data'])->name('admin.warranty.data');

    /* History User */
    Route::get('/history-user',[HistoryUserController::class, 'index'])->name('admin.user-history.index');

    // Route::get('/produk-qr/{id}/generate', [ProdukQrController::class, 'generateQr'])->name('admin.produk_qr.generate');

    // Route::post('/produk-qr/{id}/generate',[ProdukQrController::class, 'generateQr'])->name('admin.produk_qr.generate');


});


    Route::get('/qr/{kode}', [QrController::class, 'show'])->name('qr.index');

    // halaman form (GET)
    Route::get('/warranty/{kode_barang}', [WarrantyController::class, 'create'])->name('warranty.create');

    // submit form (POST)
    Route::post('/warranty/{kode_barang}', [WarrantyController::class, 'store'])->name('warranty.store');

    Route::get('/warranty/already-scan', function () { return view('warranty.already-scan'); });

    Route::get('/warranty/{kode_barang}/verified',[WarrantyController::class, 'verified'])->name('warranty.verified');



    Route::get('/admin/stiker/{kode}', [DownloadStikerController::class, 'download']);
    
    Route::get('/api/indo/provinces', function () {
        return Http::get(
            'https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json'
        )->json();
    });

    Route::get('/api/indo/regencies/{id}', function ($id) {
        return Http::get(
            "https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$id}.json"
        )->json();
    });

    Route::get('/api/indo/districts/{id}', function ($id) {
        return Http::get(
            "https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$id}.json"
        )->json();
    });

    Route::get('/api/indo/villages/{id}', function ($id) {
        return Http::get(
            "https://emsifa.github.io/api-wilayah-indonesia/api/villages/{$id}.json"
        )->json();
    });

require __DIR__.'/auth.php';



