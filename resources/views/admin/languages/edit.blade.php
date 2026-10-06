@extends('layouts.admin')
@section('title', trans_db('admin.edit'))
@section('content')
    @include('admin.languages.form', ['language' => $language])
@endsection
