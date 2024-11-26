@extends('landing.master')

@section('title')
    Servicios
@endsection

@section('main')
    <section class="page-header padding">
        <div class="container">
            <div class="page-content text-center">
                <h2>Nuestros servicios</h2>
                <p>Soluciones tecnológicas para reportes ciudadanos y mejora comunitaria.</p>
            </div>
        </div>
    </section>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>

    @include('landing.body.services-2')

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
