@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('heading', 'Edit Produk')
@section('subheading', $product->name)

@section('content')
    @include('admin.products.partials.form')
@endsection
