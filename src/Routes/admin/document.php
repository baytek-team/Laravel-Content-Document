<?php

Route::group(['as' => 'document.'], function () {

	/**
	 * Folder Routes
	 */
	Route::get('folder/{folder}/child', 'FolderController@create')
		->name('folder.create.child');
	Route::resource('folder', FolderController::class);

	/**
	 * File Routes
	 */
	Route::get('file/{file}/download', 'FileController@download')
		->name('file.download');
	Route::resource('file', FileController::class);
});
