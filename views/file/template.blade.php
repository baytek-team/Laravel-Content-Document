@extends('Content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            {{ ___('Resource File Management') }}
            <div class="sub header">{{ ___('Manage the resource files.') }}</div>
        </div>
    </h1>
@endsection
