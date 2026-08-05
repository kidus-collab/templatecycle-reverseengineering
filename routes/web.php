<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectController;

// Stage Templates Controller Routes
Route::controller(ProjectTemplateController::class)->group(function () {
    Route::get('/', 'index')->name('templates');
    Route::post('/templates/{template}/apply', 'apply')->name('templates.apply');
});

// Projects Portfolio Controller Routes
Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'index')->name('projects.index');
    Route::post('/projects', 'store')->name('projects.store');
    Route::get('/projects/{project}', 'show')->name('projects.show');
    Route::put('/projects/{project}', 'update')->name('projects.update');
    Route::delete('/projects/{project}', 'destroy')->name('projects.destroy');
});
