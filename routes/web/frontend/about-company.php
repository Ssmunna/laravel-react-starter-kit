<?php

use App\Http\Controllers\Frontend\AboutCompanyController;
use Illuminate\Support\Facades\Route;


Route::get('/about-my-company', [AboutCompanyController::class, 'getList'])->name('about_company');
