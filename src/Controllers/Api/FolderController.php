<?php

namespace Baytek\Laravel\Content\Types\Document\Controllers\Api;

use Baytek\Laravel\Content\Types\Document\Models\Folder;
use Baytek\Laravel\Content\Types\Document\Models\File;
use Baytek\Laravel\Content\Types\Document\Scopes\ApprovedFolderScope;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Events\ContentEvent;
use Baytek\Laravel\Content\Controllers\ContentController;

use Illuminate\Http\Request;
use App\ContentTypes\Committees\Requests\FolderRequest;

use App;
use Auth;
use Carbon\Carbon;
use Response;
use Validator;

class FolderController extends Controller
{
    public function view($path = '')
    {
        $parent = $path ? content(trim("folder/$path", '/')) : content('content-type/folder');

        $folders = Content::childrenOfType($parent, 'folder');

        return [
            'folders' => $folders->get()->each(function(&$self) use ($path, $parent) {
                $self->path = $path.'/'.$self->key;
                $self->count = File::childrenOfType($self->id, 'file')->withStatus('r', File::APPROVED)->count();
            }),
            'files' => Content::childrenOfType($parent, 'file')->withStatus('r', File::APPROVED)->get()->load('meta'),
            'path' => $path,
            'parent' => content($parent->parent()),
            'title' => $parent->title,
        ];
    }

    public function create(Request $request, $path)
    {
        Validator::make(
            $request->all(),
            (new FolderRequest)->rules(),
            (new FolderRequest)->messages()
        )->validate();

        //Get the folder, or else set the folder to the committee, for root level folder creation
        $key = preg_replace('/\/*folder\/create$/', '', $path);

        if ($key) {
            $folder = content('folder/' . $key, true, Folder::class);
        }
        else {
            $folder = content('content-type/folder');
        }

        $request->merge(['key' => str_slug($request->title)]);
    	$request->merge(['language' => App::getLocale()]);

        //Save folder and relationships
    	$newFolder = new Folder($request->all());
    	$newFolder->save();
    	$newFolder->saveMetadata('author_id', Auth::user()->id);
    	$newFolder->saveRelation('parent-id', $folder->id);
    	$newFolder->saveRelation('content-type', content('content-type/folder', false));

        //Approve the folder
        $newFolder->onBit(Folder::APPROVED)->update();

        //ContentEvent required here, otherwise the parent id isn't properly accessible
        event(new ContentEvent($newFolder));

        //Add the path before returning the response
        $newFolder->path = '/documents/';
        if ($key) {
            $newFolder->path .= $key.'/';
        }
        $newFolder->path .= $newFolder->key;
        $newFolder->parent = $newFolder->parent();

        return response()->json([
            'status' => 'success',
            'folder' => $newFolder,
        ]);
    }

    public function destroy(Request $request, $path)
    {
    	$folder = content("folder/". preg_replace('/\/*folder\/delete$/', '', $path), true, Folder::class)->load(['relations', 'relations.relation', 'relations.relationType']);

		getChildrenAndDelete($folder);

        return response()->json([
            'status' => 'success',
            'message' => 'Document deleted!'
        ]);
    }
}
