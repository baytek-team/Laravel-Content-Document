@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            {{ ___('Document Folder Management') }}
            <div class="sub header">{{ ___('Manage the document folders.') }}</div>
        </div>
    </h1>
@endsection
