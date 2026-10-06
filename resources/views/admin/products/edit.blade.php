@extends('layouts.admin')

@section('title', trans_db('admin.edit'))

@section('content')
    @include('admin.products.form', ['product' => $product])
@endsection
