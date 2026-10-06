@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-4">{{ $page->title }}</h1>
    <div class="prose text-gray-600">{!! $page->body !!}</div>
</div>
@endsection
