<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\IkuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IkuEvaluationExportController;

// Public Routes (No Authentication Required)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');



// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
    Route::get('/register-department', [RegisterController::class, 'showRegis'])->name('department.form');
    Route::post('/register-department', [RegisterController::class, 'registerDepartment'])->name('department.submit');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/dashboard-admin', [DashboardController::class, 'showAdmin'])->name('dashboards');

    // Kontrak Page
    Route::get('/kontrak', [KontrakController::class, 'showKontrak'])->name('show-kontrak');
    Route::get('/form-kontrak', [KontrakController::class, 'index'])->name('form-kontrak');
    Route::get('/check-kontrak', [KontrakController::class, 'checkOrCreateKontrak'])->name('check-kontrak');
    Route::post('/form-sasaran', [KontrakController::class, 'storeSasaran'])->name('store-sasaran');
    Route::post('/form-kontrak', [KontrakController::class, 'storeKpi'])->name('store-kpi');
    Route::get('/edit-kpi/{id}', [KontrakController::class, 'editKpi'])->name('edit-kpi');
    Route::put('/update-kpi/{id}', [KontrakController::class, 'updateKpi'])->name('update-kpi');
    Route::delete('/delete-kpi/{id}', [KontrakController::class, 'deleteKpi'])->name('delete-kpi');
    Route::delete('/delete-sasaran/{id}', [KontrakController::class, 'deleteSasaran'])->name('delete-sasaran');
    Route::get('/export-kontrak-manajemen', [KontrakController::class, 'exportKontrakManajemen'])->name('export.kontrak');
    Route::get('/penjabaran', [KontrakController::class, 'showPenjabaran'])->name('show-penjabaran');
    Route::get('/check-penjabaran', [KontrakController::class, 'checkOrCreatePenjabaran'])->name('check-penjabaran');
    Route::get('/isi-penjabaran', [KontrakController::class, 'showForm'])->name('form-penjabaran');
    Route::post('/isi-penjabaran', [KontrakController::class, 'storePenjabaran'])->name('store-penjabaran');

    // IKU Page
    Route::get('/iku', [IkuController::class, 'showIku'])->name('iku.show');
    Route::post('/iku/addVersion', [IkuController::class, 'addVersion'])->name('iku.addVersion');
    Route::delete('/iku/deleteVersion/{iku_id}/{version}', [IkuController::class, 'deleteVersion'])->name('iku.deleteVersion');
    Route::get('/form-iku', [IkuController::class, 'index'])->name('form-iku');
    Route::get('/check-iku', [IkuController::class, 'checkOrCreateIku'])->name('check-iku');
    Route::post('/form-iku', [IkuController::class, 'storeIku'])->name('store-iku');
    Route::get('/edit-iku/{id}', [IkuController::class, 'editIku'])->name('edit-iku');
    Route::put('/update-iku/{id}', [IkuController::class, 'updateIku'])->name('update-iku');
    Route::delete('/delete-iku/{id}', [IkuController::class, 'deleteIku'])->name('delete-iku');
    Route::get('/iku/{id}/detail', [IkuController::class, 'showDetail'])->name('iku.detail');
    Route::get('/export-iku', [IkuController::class, 'exportIku'])->name('export.iku');


    // Progress Page
    Route::get('/progres', [ProgresController::class, 'index'])->name('progres.index');
    Route::get('/progres/create', [ProgresController::class, 'create'])->name('progres.create');
    Route::post('/progres', [ProgresController::class, 'store'])->name('progres.store');
    Route::get('/progres/{id}/edit', [ProgresController::class, 'edit'])->name('progres.edit');
    Route::put('/progres/{id}', [ProgresController::class, 'update'])->name('progres.update');
    Route::delete('/progres/{id}', [ProgresController::class, 'destroy'])->name('progres.destroy');

    // Users
    Route::get('/users', [UserController::class, 'showAll'])->name('users');
    Route::get('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/users/edit/{id}', [UserController::class, 'edit']);
    Route::post('/users/update/{id}', [UserController::class, 'update']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update-username', [ProfileController::class, 'updateUsername'])->name('profile.updateUsername');

    // Signature Page
    Route::get('/signature', function () {
        return view('pages.signature');
    })->name('signature');

    // Evaluasi Page
    Route::get('/evaluasi', [EvaluasiController::class, 'showEvaluasi'])->name('show-evaluasi');
    Route::get('/form-evaluasi', [EvaluasiController::class, 'index'])->name('form-evaluasi');
    Route::post('/store-eval', [EvaluasiController::class, 'store'])->name('store-eval');
    Route::get('/form-evaluasi/edit/{id}', [EvaluasiController::class, 'edit'])->name('evaluasi.edit');
    Route::put('/form-evaluasi/update/{id}', [EvaluasiController::class, 'update'])->name('evaluasi.update');
    Route::delete('/form-evaluasi/delete/{id}', [EvaluasiController::class, 'destroy'])->name('evaluasi.destroy');

    // Export Routes
    Route::get('/export-iku-evaluations', [IkuEvaluationExportController::class, 'export'])->name('export.iku.evaluations');
});
