<?php

use Route\Route;

Route::add('plugin_list', '/api/plugin/list', \Controllers\PluginController::class, 'list', ['post'], Route::json);
Route::add('plugin_update', '/api/plugin/update', \Controllers\PluginController::class, 'update', ['post'], Route::json);
Route::add('plugin_detail', '/api/plugin/detail', \Controllers\PluginController::class, 'getDetail', ['post'], Route::json);
Route::add('plugin_add', '/api/plugin/add', \Controllers\PluginController::class, 'addPlugin', ['post'], Route::json);
Route::add('plugin_remove', '/api/plugin/remove', \Controllers\PluginController::class, 'removePlugin', ['post'], Route::json);
