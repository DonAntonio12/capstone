<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\PredictionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/testing', [TestingController::class, 'index'])->name('testing');
    Route::view('/history', 'history')->name('history');
    Route::view('/about', 'about')->name('about');
    Route::view('/contact', 'contact')->name('contact');
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/testing/latest', [TestingController::class, 'latest']);
Route::post('/testing/save', [TestingController::class, 'save']);
Route::post('/testing/start', [TestingController::class, 'start']);
Route::post('/testing/collect', [TestingController::class, 'collect']);
Route::get('/history', [TestingController::class, 'history']);
Route::get('/history/download', [App\Http\Controllers\TestingController::class, 'downloadHistory'])->name('history.download');
Route::delete('/history/{id}', [App\Http\Controllers\TestingController::class, 'destroy'])->name('history.destroy');
Route::post('/predict', [PredictionController::class, 'predict'])->name('predict');
Route::get('/prediction/form', function () {
    return view('prediction.form');
})->name('prediction.form');

// Admin Login Routes
Route::get('/admin/login', [\App\Http\Controllers\Api\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Api\AdminAuthController::class, 'login'])->name('admin.login.submit');

// Admin Dashboard (to be protected)
Route::get('/admin', [\App\Http\Controllers\Api\AdminAuthController::class, 'dashboard'])->middleware('auth:admin')->name('admin.dashboard');

// Admin Logout
Route::post('/admin/logout', [\App\Http\Controllers\Api\AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');

// Admin User Management CRUD
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Api\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [\App\Http\Controllers\Api\AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [\App\Http\Controllers\Api\AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Api\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Api\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Api\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/{user}', [\App\Http\Controllers\Api\AdminUserController::class, 'show'])->name('admin.users.show');
    // Soil Data Management
    Route::get('/soil-data', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'index'])->name('admin.soil.index');
    Route::get('/soil-data/create', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'create'])->name('admin.soil.create');
    Route::post('/soil-data', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'store'])->name('admin.soil.store');
    Route::get('/soil-data/{sensorReading}/edit', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'edit'])->name('admin.soil.edit');
    Route::put('/soil-data/{sensorReading}', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'update'])->name('admin.soil.update');
    Route::delete('/soil-data/{sensorReading}', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'destroy'])->name('admin.soil.destroy');
    // Soil Type Management
    Route::get('/soil-types', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'index'])->name('admin.soil_types.index');
    Route::get('/soil-types/create', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'create'])->name('admin.soil_types.create');
    Route::post('/soil-types', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'store'])->name('admin.soil_types.store');
    Route::get('/soil-types/{soilType}/edit', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'edit'])->name('admin.soil_types.edit');
    Route::put('/soil-types/{soilType}', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'update'])->name('admin.soil_types.update');
    Route::delete('/soil-types/{soilType}', [\App\Http\Controllers\Api\AdminSoilTypeController::class, 'destroy'])->name('admin.soil_types.destroy');
    // Soil Data Records Management
    Route::get('/soil-data-records', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsIndex'])->name('admin.soil_data_records.index');
    Route::get('/soil-data-records/create', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsCreate'])->name('admin.soil_data_records.create');
    Route::post('/soil-data-records', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsStore'])->name('admin.soil_data_records.store');
    Route::get('/soil-data-records/{sensorReading}/edit', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsEdit'])->name('admin.soil_data_records.edit');
    Route::put('/soil-data-records/{sensorReading}', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsUpdate'])->name('admin.soil_data_records.update');
    Route::delete('/soil-data-records/{sensorReading}', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'recordsDestroy'])->name('admin.soil_data_records.destroy');
    // View User Soil Data
    Route::get('/soil-users', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'userList'])->name('admin.soil_users.index');
    Route::get('/soil-users/{user}', [\App\Http\Controllers\Api\AdminSoilDataController::class, 'userSoilData'])->name('admin.soil_users.show');
    // System Settings
    Route::get('/settings', [\App\Http\Controllers\Api\AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [\App\Http\Controllers\Api\AdminSettingsController::class, 'update'])->name('admin.settings.update');
});

require __DIR__.'/auth.php';
