<?php

use Route\Route;

Route::add('plugin_market_list','/api/pluginMarket/list',\Controllers\PluginMarketController::class,'list',['post'],Route::json);