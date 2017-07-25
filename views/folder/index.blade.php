@extends('admin.resources.folder.template')
@include('admin.resources.folder.dropzone', ['resource_id' => $current_category_id])

@section('page.head.menu')
    <div class="ui secondary menu">
        {{--  href="{{ route('resource.folder.resource.create', $current_category_id) }}" --}}
        @if($can_add_file)
        <a class="item dz-clickable">
            <i class="file text icon"></i>
            {{ ___('Add File') }}
        </a>
        @endif
        @if(isset($committee))
            @link(___('Add Folder'), [
                'location' => 'committees.folders.create',
                'type' => 'route',
                'class' => 'item',
                'prepend' => '<i class="folder icon"></i>',
                'model' => [
                    'committee' => $current_category_id
                ]
            ])
        @else
            @link(___('Add Folder'), [
                'location' => 'resource.folder.create.child',
                'type' => 'route',
                'class' => 'item',
                'prepend' => '<i class="folder icon"></i>',
                'model' => $current_category_id
            ])
        @endif

        {{-- <a class="item" href="{{ route('resource.folder.create.child', $current_category_id) }}">
            <i class="folder text icon"></i>
            {{ ___('Add Folder') }}
        </a> --}}
    </div>
@endsection

@section('content')
    @include('admin.resources.folder.table')

    {{-- {{ $categories->links('pagination.default') }} --}}
@endsection