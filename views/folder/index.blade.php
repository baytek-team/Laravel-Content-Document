@extends('documents::folder.template')
@include('documents::folder.dropzone', ['resource_id' => $current_category_id])

@section('page.head.menu')
    <div class="ui secondary contextual menu">

        <div class="item">
            @link(___('Add Folder'), [
                'location' => 'document.folder.create.child',
                'type' => 'route',
                'class' => 'ui button',
                'prepend' => '<i class="folder icon"></i>',
                'model' => $current_category_id
            ])
            &nbsp;
            <a class="ui primary button dz-clickable">
                <i class="file text icon"></i>
                {{ ___('Add File') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('documents::folder.table')
@endsection