@extends('admin.resources.folder.template')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <form action="{{route('resource.folder.store')}}" method="POST" class="ui form">
                {{ csrf_field() }}
                <input type="hidden" id="parent_id" name="parent_id" value="{{ $parent->id }}">

                @include('admin.resources.folder.form')

                <div class="field actions">
    	            <a class="ui button" href="{{ route('resource.folder.index') }}">{{ ___('Cancel') }}</a>
    	            <button type="submit" class="ui right floated primary button">
    	            	{{ ___('Create Folder') }}
                	</button>
                </div>
            </form>
        </div>
    </div>
@endsection