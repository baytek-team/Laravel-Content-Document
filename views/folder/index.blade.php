@extends('documents::folder.template')
@include('documents::folder.dropzone', ['resource_id' => $current_category_id])

@section('page.head.menu')
    <div class="ui secondary menu">

        <a class="item dz-clickable">
            <i class="file text icon"></i>
            {{ ___('Add File') }}
        </a>

        @link(___('Add Folder'), [
            'location' => 'document.folder.create.child',
            'type' => 'route',
            'class' => 'item',
            'prepend' => '<i class="folder icon"></i>',
            'model' => $current_category_id
        ])
    </div>
@endsection

@section('content')
    @include('documents::folder.table')
@endsection