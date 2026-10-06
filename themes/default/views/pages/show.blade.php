@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold tracking-tight mb-6">{{ $page->title }}</h1>
    <div class="prose prose-neutral max-w-none text-neutral-600">
        {!! $page->body !!}
    </div>
</div>
@endsection
