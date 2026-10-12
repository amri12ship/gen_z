@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('heading', 'Tambah Kategori')
@section('subheading', 'Buat kategori produk baru')

@section('content')
    @include('admin.categories.partials.form')
@endsection
