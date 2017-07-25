<?php

Route::post('{folder?}', 'FolderController@figureout')
	->where(['folder' => '.*?[folder/create|file/upload|folder/delete|file/delete|file/approve]']);
Route::get('{folder?}/{file}', 'FileController@view')->where(['folder' => '.*\/*file']);
Route::get('{folder?}', 'FolderController@view')->where(['folder' => '.*']);
