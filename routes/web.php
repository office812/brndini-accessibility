<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::get('/signup', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/dashboard/install', [DashboardController::class, 'install'])->name('dashboard.install');
    Route::get('/dashboard/compliance', [DashboardController::class, 'compliance'])->name('dashboard.compliance');
    Route::get('/dashboard/account', [DashboardController::class, 'account'])->name('dashboard.account');
    Route::post('/dashboard/sites', [DashboardController::class, 'storeSite'])->name('dashboard.sites.store');
    Route::post('/dashboard/account/billing', [DashboardController::class, 'updateBilling'])->name('dashboard.account.billing');
    Route::post('/dashboard/account/activate', [DashboardController::class, 'activateLicense'])->name('dashboard.account.activate');
    Route::post('/dashboard/compliance/audit', [DashboardController::class, 'runAudit'])->name('dashboard.compliance.audit');
    Route::post('/dashboard/compliance/alerts', [DashboardController::class, 'updateAlerts'])->name('dashboard.compliance.alerts');
    Route::post('/dashboard', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
