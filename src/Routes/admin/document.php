<?php

Route::group(['as' => 'document.'], function () {

	/**
	 *	Root route also goes to folder index
	 */
	Route::get('/', 'FolderController@index');

	/**
	 * Folder Routes
	 */
	Route::get('folder/{folder}/child', 'FolderController@create')
		->name('folder.create.child');
	Route::resource('folder', FolderController::class);
	Route::get('folder/{folder}/edit/parent', 'FolderController@editParent')
		->name('folder.edit.parent');

	/**
	 * File Routes
	 */
	Route::get('file/{file}/download', 'FileController@download')
		->name('file.download');
	Route::post('file/{folder?}', 'FileController@store')
		->name('file.store');
	Route::resource('file', FileController::class, ['except' => ['store']]);
});
