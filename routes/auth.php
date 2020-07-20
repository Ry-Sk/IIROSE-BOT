<?php

use Route\Route;

Route::add('login', '/api/auth/login', \Controllers\AuthController::class, 'login', ['post'], Route::json);
