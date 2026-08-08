<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectController;

// Stage Templates Controller Routes
Route::controller(ProjectTemplateController::class)->group(function () {
    Route::get('/', 'index')->name('templates');
    Route::post('/templates', 'store')->name('templates.store');
    Route::put('/templates/{template}', 'update')->name('templates.update');
    Route::delete('/templates/{template}', 'destroy')->name('templates.destroy');
    Route::post('/templates/{template}/apply', 'apply')->name('templates.apply');
    Route::put('/templates/{template}/sync', 'sync')->name('templates.sync');
    
    // API Routes for Template Builder
    Route::post('/templates/{template}/tracks', 'storeTrack')->name('templates.tracks.store');
    Route::put('/tracks/{track}', 'updateTrack')->name('templates.tracks.update');
    Route::delete('/tracks/{track}', 'destroyTrack')->name('templates.tracks.destroy');
    
    Route::post('/tracks/{track}/milestones', 'storeMilestone')->name('templates.milestones.store');
    Route::put('/milestones/{milestone}', 'updateMilestone')->name('templates.milestones.update');
    Route::delete('/milestones/{milestone}', 'destroyMilestone')->name('templates.milestones.destroy');
});

// Projects Portfolio Controller Routes
Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'index')->name('projects.index');
    Route::post('/projects', 'store')->name('projects.store');
    Route::get('/projects/{project}', 'show')->name('projects.show');
    Route::put('/projects/{project}', 'update')->name('projects.update');
    Route::put('/projects/{project}/progress', 'updateProgress')->name('projects.progress.update');
    Route::delete('/projects/{project}', 'destroy')->name('projects.destroy');
    Route::delete('/projects/{project}/template', 'removeTemplate')->name('projects.template.remove');
});
