<?php

use Illuminate\Support\Facades\Route;

// Redirect root to projects or templates
Route::get('/', function () {
    return view('stage-templates', ['initialView' => 'templates']);
})->name('templates');

// Dedicated Projects Portfolio Route
Route::get('/projects', function () {
    return view('stage-templates', ['initialView' => 'projects']);
})->name('projects.index');

// Individual Project Route
Route::get('/projects/{project}', function ($project) {
    return view('stage-templates', ['initialView' => 'projects', 'activeProjectRef' => $project]);
})->name('projects.show');
