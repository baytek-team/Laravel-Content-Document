<?php

namespace Baytek\Laravel\Content\Types\Document\Models;

use Baytek\Laravel\Content\Models\Content;

class File extends Content
{
    const EXCLUDED = 2 ** 9;  // Exclude from search

    /**
     * Return the status messages
     *
     * @return Mixed status message
     */
    public static function statusMessages()
    {
        // return Statuses\TermMessages::class;
        return [
            self::EXCLUDED => 'Excluded From Search',
        ];
    }
    
	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'file'
	];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Returns an icon class used by Semantic UI for a filename based on the file extension.
     * @see    https://semantic-ui.com/elements/icon.html
     * @return string the css classes for the icon
     */
    public static function getIconCssClass($filename)
    {
        $iconClass = '';
        $parts = pathinfo($filename);
        $ext = isset($parts['extension']) ? $parts['extension'] : '';
        switch($ext) {
            case 'gif':
            case 'png':
            case 'jpeg':
                $iconClass = 'file image outline icon';
                break;
            case 'pdf':
                $iconClass = 'file pdf outline icon';
                break;
            case 'xlsx':
                $iconClass = 'file excel outline icon';
                break;
            case 'docx':
                $iconClass = 'file word outline icon';
                break;
            case 'zip':
            case 'gz':
                $iconClass = 'file archive outline icon';
                break;
            case 'txt':
            case 'csv':
                $iconClass = 'file text outline icon';
                break;
            default:
                $iconClass = 'file outline icon';
                break;
        }

        return $iconClass;
    }

    /**
     * Returns an icon class used by Semantic UI for the file based on the file extension.
     * @see    getIconCssClass
     * @return string the css classes for the icon
     */
    public function getIconCssClassAttribute()
    {
        return self::getIconCssClass($this->title);
    }

}
