<?php

namespace Baytek\Laravel\Content\Types\Document\Controllers\Api;

use Baytek\Laravel\Content\Types\Document\Models\Folder;
use Baytek\Laravel\Content\Types\Document\Models\File;
use Baytek\Laravel\Content\Controllers\ApiController;

use Baytek\Laravel\Content\Models\Content;

use Illuminate\Http\Request;

use App;
use Auth;
use Carbon\Carbon;
use Response;
use Storage;

use File as FS;

class FileController extends ApiController
{
    public function view($folder, $file)
    {
        $folder = preg_replace('/\/*file$/', '', $folder);
        
        $path = ($folder) ? "folder/{$folder}/{$file}" : "folder/{$file}";
        $file = (new File)->getWithPath($path)->first()->load('meta');

        if ($this->allowDownload($folder, $file)) {
            return Response::download(storage_path('app/' . $file->metadata('file')), $file->metadata('original'));
        }
        else {
            return abort(403);
        }
    }

    /**
     * Overwritable method for restricting download permissions later
     */
    public function allowDownload($folder, $file)
    {
        return true;
    }

    public function create(Request $request, $folder)
    {
        //Get the folder, or else set the folder to the committee, for root level folder creation
        $key = preg_replace('/\/*file\/upload$/', '', $folder);
        if ($key) {
           $folder = content('folder/' . $key, true, Folder::class);
        } else {
            $folder = content('content-type/folder');
        }

        $uploaded = $request->file('file');
        $originalName = $uploaded->getClientOriginalName();

        $path = $uploaded->store('resources');

        $file = new File([
            'key' => str_slug($originalName) . '_' . date('Y-m-d_H-i-s'),
            'language' => 'en',
            'title' => $originalName,
            'content' => ''
        ]);

        $file->save();

        $file->saveRelation('content-type', $file->getContentIdByKey('file'));
        $file->saveMetadata('file', $path);
        $file->saveMetadata('original', $originalName);
        $file->saveMetadata('size', FS::size($uploaded));
        $file->saveMetadata('mime', FS::mimeType($uploaded));

        if(!is_null($folder)) {
            $file->saveRelation('parent-id', $folder->id);
        }

        return $file->load('meta');
    }

    public function approve(Request $request, $path)
    {
        $file = content('folder/' . preg_replace('/\/*file\/approve$/', '', $path), true, File::class);

        if ($request->title) {
            $file->title = $request->title;
        }

        $file->onBit(File::APPROVED)->update();

        return response()->json([
            'status' => 'success',
            'file' => $file->load('meta'),
        ]);
    }

    public function destroy(Request $request, $path)
    {
        $file = content('folder/' . preg_replace('/\/file\/delete$/', '', $path), true, File::class);

        $file->offBit(File::APPROVED)->onBit(File::DELETED)->update();
        Storage::delete($file->getMeta('file'));
        $file->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

}
