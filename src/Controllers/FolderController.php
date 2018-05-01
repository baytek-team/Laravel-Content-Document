<?php

namespace Baytek\Laravel\Content\Types\Document\Controllers;

use Baytek\Laravel\Content\Types\Document\Models\Folder;
use Baytek\Laravel\Content\Types\Document\Requests\FolderRequest;

use Baytek\Laravel\Content\Events\ContentEvent;
use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Models\Content;

use Illuminate\Http\Request;

use Cache;
use View;
use Validator;
use Auth;

class FolderController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the document
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = Folder::class;
    protected $request = FolderRequest::class;

    /**
     * [$viewPrefix description]
     * @var string
     */
    protected $viewPrefix = 'admin';
    /**
     * Namespace from which to load the view
     * @var string
     */
    protected $viewNamespace = 'documents';
    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'folder.index',
        'create' => 'folder.create',
        'edit' => 'folder.edit',
        'show' => 'folder.index',
    ];

    protected $redirectsKey = 'document.folder';

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Folder::withoutGlobalScopes()
            ->childrenOfType(['folder'], Auth::user()->can('View File') ? ['folder', 'file'] : ['folder'])
            ->with(['relations', 'relations.relation', 'relations.relationType'])
            ->withStatus(Folder::APPROVED)
            ->get()
            ->sortByDesc(function($category){
                return $category->relationships()->get('content_type') == 'folder';
            });

        $current_category_id = Content::where('contents.key', 'folder')->get()->first()->id;

        $this->viewData['index'] = [
            'current_category_id' => $current_category_id,
            'categories' => $categories,
        ];

        return parent::contentIndex();
    }


    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $categories = Content::childrenOfType($id, Auth::user()->can('View File') ? ['folder', 'file'] : ['folder'])
            ->with(['relations', 'relations.relation', 'relations.relationType'])
            ->withStatus(Folder::APPROVED)
            ->get()
            ->sortBy(function($category){
                // dump($category->relationships()->get('content_type'));
                return $category->relationships()->get('content_type') != 'folder';
            });

        $this->viewData['index'] = [
            'current_category_id' => $id,
            'categories' => $categories,
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $parent = is_null($id) ?
            Content::where('contents.key', 'folder')->get()->first() :
            Content::find($id);
        $root = content('content-type/folder', false);

        $this->viewData['create'] = [
            'parents' => [],
            'parent' => $parent,
            'root' => $root
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            (new $this->request)->rules(),
            (new $this->request)->messages()
        )->validate();

        $this->redirects = false;

        $request->merge(['key' => str_slug($request->title)]);

        $category = parent::contentStore($request);
        $category->saveRelation('parent-id', $request->parent_id);
        $category->onBit(Folder::APPROVED)->save();

        //Content event to update the cache
        event(new ContentEvent($category));

        if ($request->parent_id && $request->parent_id != content('content-type/folder', false)) {
            return redirect(route('document.folder.show', $request->parent_id));
        }
        else {
            return redirect(route('document.folder.index'));
        }
    }

    /**
     * Update a folder using the parent method
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make(
            $request->all(),
            (new $this->request)->rules(),
            (new $this->request)->messages()
        )->validate();

        $this->redirects = false;
        $folder = parent::contentUpdate($request, $id);

        //See whether the parent needs to change
        $parent = $folder->parent();
        if (($parent && $parent != $request->parent_id) || ($request->parent_id && !$parent)) {
            $parent = $request->parent_id ?: content('content-type/folder', false);

            $folder->removeRelationByType('parent-id');
            $folder->saveRelation('parent-id', $parent);

            //Content event to update the cache
            event(new ContentEvent($folder));
        }

        if ($parent && $parent != 'folder') {
            return redirect(route('document.folder.show', $parent));
        }
        else {
            return redirect(route('document.folder.index'));
        }
    }

    /**
     * Show the form for editing a folder.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $folder = $this->bound($id);
        $parent = $folder->getRelationship('parent-id');
        $root = content('content-type/folder', false);

        $this->viewData['edit'] = [
            'parents' => [],
            'parent' => $parent,
            'root' => $root
        ];

        return parent::contentEdit($id);
    }

    /**
     * Show the form for editing a folder, with editable parent.
     *
     * @return \Illuminate\Http\Response
     */
    public function editParent($id)
    {
        $folder = $this->bound($id);
        $parent = $folder->getRelationship('parent-id');
        $folders = Folder::withStatus(Folder::APPROVED)->orderBy('title', 'asc')->get();
        $root = content('content-type/folder', false);

        $this->viewData['edit'] = [
            'parents' => $this->hierarchical($root, $folders),
            'parent' => $parent,
            'disabledFlag' => false,
            'disabledDepth' => 0,
            'root' => $root,
            'indent' => 0,
            'levels' => []
        ];

        return parent::contentEdit($id);
    }

    public function destroy(Request $request, Folder $folder)
    {
        $folder->load(['relations', 'relations.relation', 'relations.relationType']);

        $this->authorize('delete', $folder);

        $parent = $folder->relationships()->get('parent_id');
        $parent_id = $folder->parent();

        getChildrenAndDelete($folder);

        flash('Folder Deleted');

        if ($parent && $parent != 'folder') {
            return redirect(route('document.folder.show', $parent_id));
        }
        else {
            return redirect(route('document.folder.index'));
        }
    }

    /**
     * Sort folders alphabetically, from a single parent source
     *
     * @param  int         $folder_id    The root parent
     * @param  Collection  $folders
     *
     * @return array       $folder_list  The sorted folders
     */
    protected function hierarchical($folder_id, $folders) {
        $children = [];
        foreach ($folders as $folder) {
            $children[$folder->parent()][] = $folder;
        }

        $folder_list = [];
        if (isset($children[$folder_id])) {
            $to_look = array_reverse($children[$folder_id]);

            while($to_look) {
                $f = array_pop($to_look);
                $folder_list[] = $f;
                if (isset($children[$f->id])) {
                    foreach(array_reverse($children[$f->id]) as $child) {
                        $to_look[] = $child;
                    }
                }
            }
        }

        return $folder_list;
    }
}
