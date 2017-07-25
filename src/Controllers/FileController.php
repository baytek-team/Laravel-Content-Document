<?php

namespace Baytek\Laravel\Content\Types\Document\Controllers;

use Baytek\Laravel\Content\Types\Document\Models\File;
use Baytek\Laravel\Content\Types\Document\Models\Folder;
use Baytek\Laravel\Content\Controllers\ContentController;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use App;
use DB;
use ReflectionClass;
use Route;
use Storage;
use View;
use Response;
use File as FS;

/**
 * The Content Controller is suppose to act as an abstract class that facilitates
 * rendering and saving of common resource tables.
 *
 * There are three primary models used for all content types:
 *     Content
 *     ContentMeta
 *     ContentRelations
 *
 * Due to this commonality, it makes sense to have a super class which can handle all
 * data storage and relegate all content specific stuff to the sub classes.
 */
class FileController extends ContentController
{
    /**
     * Show the form for editing a file's name
     */
    public function download($file)
    {
        $file = File::find($file);

        $file->load('meta');

        return Response::download(storage_path('app/' . $file->metadata('file')), $file->metadata('original'));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        $this->redirects = false;

        $uploaded = $request->file('file');
        $originalName = $uploaded->getClientOriginalName();

        $path = $uploaded->store('resources');

        $file = new File([
            'key' => str_slug($originalName) . '_' . date('Y-m-d_H-i-s'),
            'language' => $request->language,
            'title' => $originalName,
            'content' => ''
        ]);

        $file->save();

        $file->saveRelation('content-type', $file->getContentIdByKey('file'));
        $file->saveMetadata('file', $path);
        $file->saveMetadata('original', $originalName);
        $file->saveMetadata('size', FS::size($uploaded));
        $file->saveMetadata('mime', FS::mimeType($uploaded));

        if(!is_null($id)) {
            $file->saveRelation('parent-id', $id);
        }

        $file->onBit(File::APPROVED)->update();

        return $file;
    }

    /**
     * Show the form for editing a file's name
     */
    public function edit($file)
    {
        $file = File::find($file);
        return view('admin.documents.file.edit', [
            'file' => $file,
        ]);
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $file)
    {
        $file = File::find($file)->load(['relations', 'relations.relation', 'relations.relationType']);
        $file->update($request->all());

        $parent = $file->relationships()->get('parent_id');
        if ($parent && $parent != 'folder') {
            return redirect(route('document.folder.show', $file->parent()));
        }
        else {
            return redirect(route('document.folder.index'));
        }
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $file)
    {
        $file = File::find($file)->load(['relations', 'relations.relation', 'relations.relationType']);
        $file->offBit(File::APPROVED)->onBit(File::DELETED)->update();
        Storage::delete($file->getMeta('file'));
        $file->delete();

        $parent = $file->relationships()->get('parent_id');
        if ($parent && $parent != 'folder') {
            return redirect(route('document.folder.show', $parent));
        }
        else {
            return redirect(route('document.folder.index'));
        }
    }

}