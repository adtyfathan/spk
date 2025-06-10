<?php

// routes/web.php
use App\Http\Controllers\ScholarshipController;
use Illuminate\Support\Facades\Route;

// Main scholarship routes
Route::get('/', [ScholarshipController::class, 'index'])->name('scholarship.form');
Route::post('/calculate', [ScholarshipController::class, 'calculate'])->name('scholarship.calculate');
Route::get('/history', [ScholarshipController::class, 'history'])->name('scholarship.history');

// Admin routes (optional)
Route::prefix('admin')->group(function () {
    Route::get('/criteria', [ScholarshipController::class, 'manageCriteria'])->name('scholarship.admin.criteria');
});

// API routes for AJAX calls (optional)
Route::prefix('api')->group(function () {
    Route::get('/application/{id}', [ScholarshipController::class, 'getApplicationDetails'])->name('api.application.details');
});