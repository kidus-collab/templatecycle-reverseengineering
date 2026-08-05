<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectController;

// Stage Templates Routes
Route::get('/', [ProjectTemplateController::class, 'index'])->name('templates');
Route::post('/templates/{template}/apply', [ProjectTemplateController::class, 'apply'])->name('templates.apply');

// Projects Portfolio Resource Routes
Route::resource('projects', ProjectController::class);

