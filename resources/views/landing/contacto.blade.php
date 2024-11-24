@extends('landing.master')

@section('title')
    Contacto
@endsection

@section('main')
    <div class="mapouter">
        <div class="gmap_canvas"><iframe width="100%" height="350" id="gmap_canvas"
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3761.3399682834665!2d-99.1806659247843!3d19.48400398180636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTnCsDI5JzAyLjQiTiA5OcKwMTAnNDEuMSJX!5e0!3m2!1ses-419!2spe!4v1711232643877!5m2!1ses-419!2spe"
                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a
                href="https://www.emojilib.com/"></a></div>
        <style>
            .mapouter {
                position: relative;
                text-align: right;
                height: 350px;
                width: 100%;
            }

            .gmap_canvas {
                overflow: hidden;
                background: none !important;
                height: 350px;
                width: 100%;
            }
        </style>
    </div>
    <section class="contact-section bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="contact-wrap d-flex align-items-center row">
                <div class="col-md-6 padding-15">
                    <div class="contact-info">
                        <h2>¡Estamos aquí para ayudarte!</h2>
                        <p>Si tienes preguntas sobre nuestros servicios de cartografía, servicios de aplicaciones, mapas
                            satelitales o análisis de datos, o si estás interesado en colaborar con nosotros en un proyecto,
                            no dudes en ponerte en contacto con nuestro equipo.</p>
                        <h3>Calz. Santo Tomás 110, Santo Tomas, Azcapotzalco, 02020 Ciudad de México, CDMX</h3>
                        <h4>
                            <span>Email:</span> contacto@grupoitaai.com <br>
                            <span>Teléfono:</span> +52 1 55 3912 9260 <br>
                            <span>Horario de Atención:</span> Lunes a Viernes: 9:00 AM - 6:00 PM. Sábado y Domingo: Cerrado
                        </h4>
                    </div>
                </div>
                <div class="col-md-6 padding-15">
                    <div class="contact-form">
                        <form action="{{ route('website.contacto.send') }}" method="POST" class="form-horizontal">
                            @csrf

                            <!-- Aquí vamos a mostrar los errores de validación -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group colum-row row">
                                <div class="col-sm-6">
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="form-control" placeholder="Nombres" required>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="form-control" placeholder="Correo electrónico" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                        class="form-control" placeholder="Teléfono">
                                    @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="subject" name="subject" class="form-control"
                                        placeholder="Asunto" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="message" name="message" cols="30" rows="5" class="form-control message" placeholder="Mensaje"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button id="submit" class="default-btn" type="submit">Enviar mensaje</button>
                                </div>
                            </div>
                            <div id="form-messages" class="alert" role="alert"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
