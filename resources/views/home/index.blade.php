@extends('layouts.app')

@section('title', 'Apins Digital | Solusi Digital Presisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
@endpush

@section('content')
    @include('sections.hero')
    @include('sections.stats')
    @include('sections.services')
    @include('sections.portfolio')
    @include('sections.about')
    @include('sections.team')
    @include('sections.testimonials')
    @include('sections.faq')
    @include('sections.blog')
    @include('sections.contact')
@endsection
