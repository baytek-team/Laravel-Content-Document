<?php

use Baytek\Laravel\Content\Types\Document\Models\Folder;
use Baytek\Laravel\Content\Types\Document\Models\File;

/**
 * Resource Folders
 */
$factory->define(Folder::class, function (Faker\Generator $faker) {

    $title = ucwords(implode(' ', $faker->unique()->words(rand(1,2))));

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => null,
        'status' => Folder::APPROVED,
        'language' => App::getLocale(),
    ];
});

/**
 * Resource Files
 */
$factory->define(File::class, function (Faker\Generator $faker) {

    $title = ucwords(implode(' ', $faker->unique()->words(rand(1,5))));

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => null,
        'status' => File::APPROVED,
        'language' => App::getLocale(),
    ];
});
