@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            {{ ___('Document File Management') }}
            <div class="sub header">{{ ___('Manage the document files.') }}</div>
        </div>
    </h1>
@endsection
