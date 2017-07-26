@extends('documents::file.template')

@section('content')
    <div id="registration" class="ui container">
        <div class="ui hidden divider"></div>
        <form action="{{ route('document.file.update', [$file->id]) }}" method="POST" class="ui form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            @include('documents::file.form')
            <div class="ui hidden divider"></div>

            <div class="ui hidden error message"></div>
            <div class="field actions">
                <a class="ui button" href="{{ $file->parent() != content('content-type/folder', false) ? route('document.folder.show', $file->parent()) : route('document.folder.index') }}">{{ ___('Cancel') }}</a>

                <button type="submit" class="ui right floated primary button">
                    {{ ___('Update') }}
                </button>
            </div>
        </form>
    </div>
@endsection