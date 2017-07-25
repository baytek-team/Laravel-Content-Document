@extends('admin.resources.folder.template')

@section('content')
<div id="registration" class="ui container">
    <div class="ui hidden divider"></div>
    <form action="{{ route('resource.folder.update', $folder->id) }}" method="POST" class="ui form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('admin.resources.folder.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('resource.folder.index') }}">{{ ___('Cancel') }}</a>

            <button type="submit" class="ui right floated primary button">
                {{ ___('Update Folder') }}
            </button>
        </div>
    </form>
</div>

@endsection