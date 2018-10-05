<?php

Route::get('token', 'TranslationController@token');
Route::post('update', 'TranslationController@update');
Route::delete('delete', 'TranslationController@delete');