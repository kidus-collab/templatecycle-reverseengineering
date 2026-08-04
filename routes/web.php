<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectController;

// Stage Templates Routes
Route::get('/', [ProjectTemplateController::class, 'index'])->name('templates');

// Projects Portfolio Routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
