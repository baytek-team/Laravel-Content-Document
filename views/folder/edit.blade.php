@extends('documents::folder.template')

@section('content')
<div id="registration" class="ui container">
    <div class="ui hidden divider"></div>
    <form action="{{ route('document.folder.update', $folder->id) }}" method="POST" class="ui form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('documents::folder.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('document.folder.index') }}">{{ ___('Cancel') }}</a>

            <button type="submit" class="ui right floated primary button">
                {{ ___('Update Folder') }}
            </button>
        </div>
    </form>
</div>

@endsection