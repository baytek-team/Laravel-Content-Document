<?php
namespace Baytek\Laravel\Content\Types\Document\Seeders;

use Baytek\Laravel\Content\Seeder;

class DocumentSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'file',
            'title' => 'File',
            'content' => Baytek\Laravel\Content\Types\Document\Models\File::class,
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'folder',
            'title' => 'Folder',
            'content' => Baytek\Laravel\Content\Types\Document\Models\Folder::class,
            'relations' => [
                ['parent-id', 'content-type'],
            ]
        ],
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
