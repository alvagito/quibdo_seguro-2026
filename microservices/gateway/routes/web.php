<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->away('http://localhost:8006'));
