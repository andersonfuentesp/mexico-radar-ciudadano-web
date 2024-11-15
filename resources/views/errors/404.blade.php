@extends('master')

@section('title')
    404
@endsection

@section('main')
    <section class="error-section padding">
        <div class="container">
            <div class="error-content text-center">
                <img src="{{ asset('website/img/404.png') }}" alt="img">
                <div class="error-info">
                    <h2>404</h2>
                    <P>¡Vaya! ¡Parece que te has equivocado! <br>De cualquier manera, probablemente deberías <br> 
                        <a href="{{ route('website.index') }}">Volver a la Página Principal</a></P>
                </div>
            </div>
        </div>
    </section>
@endsection