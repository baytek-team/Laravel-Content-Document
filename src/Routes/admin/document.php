<?php

Route::group(['as' => 'document.'], function () {
	Route::get('folder/{folder}/child', 'FolderController@create')
		->name('folder.create.child');

	Route::resource('folder', FolderController::class);
	Route::resource('file', FileController::class);
});
