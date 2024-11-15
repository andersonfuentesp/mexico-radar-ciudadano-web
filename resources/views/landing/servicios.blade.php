@extends('master')

@section('title')
    Servicios
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Nuestros servicios</h2>
                <p>Investigación y tecnología aplicada.</p>
            </div>
        </div>
    </section>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>

    @include('body.services-2')

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
