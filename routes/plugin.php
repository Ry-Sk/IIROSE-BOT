<?php

use Route\Route;

Route::add('plugin_list','/api/plugin/list',\Controllers\PluginController::class,'list',['post'],Route::json);
Route::add('plugin_update','/api/plugin/update',\Controllers\PluginController::class,'update',['post'],Route::json);
Route::add('plugin_detail','/api/plugin/detail',\Controllers\PluginController::class,'getDetail',['post'],Route::json);