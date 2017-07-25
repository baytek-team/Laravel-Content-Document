@extends('Content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            {{ ___('Resource Folder Management') }}
            <div class="sub header">{{ ___('Manage the resource folders.') }}</div>
        </div>
    </h1>
@endsection
