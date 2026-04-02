<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/brndini-services', 'public-services')->name('brndini.services');
Route::view('/terms', 'terms')->name('legal.terms');
Route::view('/privacy', 'privacy')->name('legal.privacy');
Route::get('/statement/{publicKey}', [DashboardController::class, 'statementPage'])->name('statement.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::get('/signup', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboard.super-admin');
    Route::post('/dashboard/super-admin/tracking', [DashboardController::class, 'updateGlobalTracking'])->name('dashboard.super-admin.tracking');
    Route::post('/dashboard/super-admin/tickets/{ticketKey}', [DashboardController::class, 'updateSupportTicketAdmin'])->name('dashboard.super-admin.tickets.update');
    Route::post('/dashboard/super-admin/leads/{leadKey}', [DashboardController::class, 'updateServiceLeadAdmin'])->name('dashboard.super-admin.leads.update');
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/dashboard/services', [DashboardController::class, 'services'])->name('dashboard.services');
    Route::get('/dashboard/install', [DashboardController::class, 'install'])->name('dashboard.install');
    Route::get('/dashboard/compliance', [DashboardController::class, 'compliance'])->name('dashboard.compliance');
    Route::get('/dashboard/account', [DashboardController::class, 'account'])->name('dashboard.account');
    Route::get('/dashboard/support', [DashboardController::class, 'support'])->name('dashboard.support');
    Route::post('/dashboard/sites', [DashboardController::class, 'storeSite'])->name('dashboard.sites.store');
    Route::post('/dashboard/account/billing', [DashboardController::class, 'updateBilling'])->name('dashboard.account.billing');
    Route::post('/dashboard/account/activate', [DashboardController::class, 'activateLicense'])->name('dashboard.account.activate');
    Route::post('/dashboard/compliance/audit', [DashboardController::class, 'runAudit'])->name('dashboard.compliance.audit');
    Route::post('/dashboard/compliance/alerts', [DashboardController::class, 'updateAlerts'])->name('dashboard.compliance.alerts');
    Route::post('/dashboard/compliance/statement', [DashboardController::class, 'updateStatementBuilder'])->name('dashboard.compliance.statement');
    Route::post('/dashboard/services/leads', [DashboardController::class, 'storeServiceLead'])->name('dashboard.services.store');
    Route::post('/dashboard/support/tickets', [DashboardController::class, 'storeSupportTicket'])->name('dashboard.support.store');
    Route::post('/dashboard', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
