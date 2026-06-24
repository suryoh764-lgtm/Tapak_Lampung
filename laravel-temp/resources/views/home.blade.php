@extends('layouts.app')

@section('title', 'Tapak Lampung — Jelajahi Keindahan Tersembunyi Lampung')

@section('content')
    @include('partials.hero')
    @include('partials.search')
    @include('partials.gems')
    @include('partials.how')
    @include('partials.trips')
    @include('partials.kuliner')
    @include('partials.map')
    @include('partials.cta')
@endsection
