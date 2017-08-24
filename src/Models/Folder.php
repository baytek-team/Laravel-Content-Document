<?php

namespace Baytek\Laravel\Content\Types\Document\Models;

use Baytek\Laravel\Content\Types\Document\Scopes\FolderScope;
use Baytek\Laravel\Content\Types\Document\Scopes\ApprovedFolderScope;
use Baytek\Laravel\Content\Models\Content;

use Cache;

class Folder extends Content
{
    // Defining the fillable fields when saving records
    protected $fillable = [
        'revision',
        'status',
        'language',
        'key',
        'title',
        'content',
        'order',
    ];

	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'folder',
	];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new FolderScope);
        static::addGlobalScope(new ApprovedFolderScope);

        parent::boot();
    }

}
