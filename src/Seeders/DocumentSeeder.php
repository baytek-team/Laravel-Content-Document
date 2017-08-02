<?php
namespace Baytek\Laravel\Content\Types\Document\Seeders;

use Baytek\Laravel\Content\Seeder;

class DocumentSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'file',
            'title' => 'File',
            'content' => \Baytek\Laravel\Content\Types\Document\Models\File::class,
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'folder',
            'title' => 'Folder',
            'content' => \Baytek\Laravel\Content\Types\Document\Models\Folder::class,
            'relations' => [
                ['parent-id', 'content-type'],
            ]
        ],
        [
            'key' => 'document-menu',
            'title' => 'Document Navigation Menu',
            'content' => '',
            'relations' => [
                ['content-type', 'menu'],
                ['parent-id', 'admin-menu'],
            ]
        ],
        [
            'key' => 'document-index',
            'title' => 'Documents',
            'content' => 'document.folder.index',
            'meta' => [
                'type' => 'route',
                'class' => 'item',
                'append' => '</span>',
                'prepend' => '<i class="file text outline left icon"></i><span class="collapseable-text">',
            ],
            'relations' => [
                ['content-type', 'menu-item'],
                ['parent-id', 'document-menu'],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStructure($this->data);
    }
}
