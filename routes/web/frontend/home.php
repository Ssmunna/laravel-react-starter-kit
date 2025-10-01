<?php

use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'getList'])->name('home');


//Route::get('sample-scripts', function () {
//    return Inertia::render('Frontend/SampleScripts/Page');
//});
//
//
//
//Route::get('contact-details', function () {
//    return Inertia::render('Frontend/ContactDetails/Page');
//});
