@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori')
@section('subheading', $category->name)

@section('content')
    @include('admin.categories.partials.form')
@endsection
