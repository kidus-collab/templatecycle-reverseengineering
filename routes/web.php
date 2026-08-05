<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectController;

// Stage Templates Routes
Route::get('/', [ProjectTemplateController::class, 'index'])->name('templates');
Route::post('/templates/{template}/apply', [ProjectTemplateController::class, 'apply'])->name('templates.apply');

// Projects Portfolio Routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
