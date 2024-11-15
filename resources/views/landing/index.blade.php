@extends('landing.master')

@section('title')
    Radar ciudadano
@endsection

@section('main')

    @include('landing.body.slides')
    @include('landing.body.specialist')
    @include('landing.body.services')

    @include('landing.body.benefits')

    @include('landing.body.projects')
    @include('landing.body.solutions')
    {{-- @include('body.testimonial') --}}
    {{-- @include('body.blog') --}}
    {{-- @include('body.sponsor')  --}}

@endsection
