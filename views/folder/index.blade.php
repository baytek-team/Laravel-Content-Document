@extends('documents::folder.template')

@can('Create File')
    @include('documents::folder.dropzone', ['resource_id' => $current_category_id])
@endcan

@section('page.head.menu')
    <div class="ui secondary contextual menu">

        <div class="item">
            @can('Create Folder')
            @link(___('Add Folder'), [
                'location' => 'document.folder.create.child',
                'type' => 'route',
                'class' => 'ui button',
                'prepend' => '<i class="folder icon"></i>',
                'model' => $current_category_id
            ])
            &nbsp;
            @endcan
            @can('Create File')
            <a class="ui primary button dz-clickable">
                <i class="file text icon"></i>
                {{ ___('Add File') }}
            </a>
            @endcan
        </div>
    </div>
@endsection


@section('content')
    @include('documents::folder.table')
@endsection

{{-- @if(@count($categories))
    @section('outer-content')
        <div class="ui middle aligned padded grid no-result">
            <div class="column">
                <div class="ui center aligned padded grid">
                    <div class="column">
                        <h2>{{ ___('We couldn\'t find anything') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif --}}