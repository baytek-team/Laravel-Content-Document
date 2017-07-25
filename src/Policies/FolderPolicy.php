<?php

namespace Baytek\Laravel\Content\Types\Document\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;

use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy extends GeneralPolicy
{
    public $contentType = 'Folder';
}
