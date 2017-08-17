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

@if(count($categories))
    @section('content')
        @include('documents::folder.table')
    @endsection
@else
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
@endif