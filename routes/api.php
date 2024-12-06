<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserFormController;

Route::post('/submit-form', [UserFormController::class, 'store'])->name('submit-form');
