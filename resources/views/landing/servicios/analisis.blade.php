@extends('master')

@section('title')
    Servicios - Análisis de datos
@endsection

@section('main')
    <div class="video-container">
        <video id="videoHeader" class="video-header" autoplay loop muted playsinline>
            <source src="{{ asset('website/videos/slider-analisis.mp4') }}" type="video/mp4">
            Tu navegador no admite el elemento <code>video</code>.
        </video>
        <!-- Agregamos un div para la sombra -->
        <div class="video-overlay"></div>
        <div class="video-content text-center">
            <h2>Análisis de datos</h2>
            <p>Investigación y tecnología aplicada.</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var video = document.getElementById('videoHeader');
            video.playbackRate = 1.5;
        });
    </script>

    <section class="service-section section-2" style="padding: 30px 0;">
        <div class="dots"></div>
    </section>

    <section class="service-section bg-grey padding">
        <div class="dark-bg"></div>
        <div class="section-heading dark-background text-center mb-40 wow fadeInUp" data-wow-delay="100ms">
            <span>Análisis de Datos y Business Intelligence</span>
            <h2>Descubre cómo el análisis avanzado de datos puede transformar tu negocio.</h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div id="service-carousel" class="service-carousel box-shadow owl-carousel" style="display: flex;">
                        <!-- Análisis de Datos Espaciales -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-globe-americas"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Análisis de Datos Espaciales</h3>
                            <p style="text-align: justify;">Especializados en análisis de datos espaciales para revelar
                                patrones y tendencias, y entregar insights geográficos valiosos.</p>
                        </div>
                        <!-- Optimización de Procesos -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-sync-alt"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Optimización de Procesos</h3>
                            <p style="text-align: justify;">Identificación de áreas para mejorar la eficiencia operativa,
                                reducir costos y optimizar los procesos empresariales.</p>
                        </div>
                        <!-- Visualización de Datos -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-chart-bar"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Visualización de Datos</h3>
                            <p style="text-align: justify;">Creación de visualizaciones de datos interactivas y
                                comprensibles para facilitar la interpretación y toma de decisiones.</p>
                        </div>
                        <!-- Análisis de Datos Descriptivos -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-info-circle"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Análisis de Datos Descriptivos</h3>
                            <p style="text-align: justify;">Proporcionamos resúmenes estadísticos y descriptivos que
                                resaltan características clave de conjuntos de datos complejos.</p>
                        </div>
                        <!-- Análisis Predictivo -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-brain"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Análisis Predictivo</h3>
                            <p style="text-align: justify;">Utilizamos modelos estadísticos y machine learning para predecir
                                tendencias y comportamientos futuros basados en datos históricos.</p>
                        </div>
                        <!-- Segmentación de Clientes -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-users-cog"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Segmentación de Clientes</h3>
                            <p style="text-align: justify;">Ayudamos a identificar y clasificar a los clientes en segmentos
                                específicos para campañas de marketing más efectivas y personalizadas.</p>
                        </div>
                        <!-- Análisis de Big Data -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-database"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Análisis de Big Data</h3>
                            <p style="text-align: justify;">Manejamos grandes volúmenes de datos con técnicas avanzadas para
                                descubrir patrones ocultos y obtener insights accionables.</p>
                        </div>
                        <!-- Consultoría en Datos -->
                        <div class="service-item"
                            style="display: flex; flex-direction: column; justify-content: space-between; height: 350px;">
                            <div class="service-icon">
                                <i class="fas fa-lightbulb"></i> <!-- Ícono actualizado -->
                            </div>
                            <h3>Consultoría en Datos</h3>
                            <p style="text-align: justify;">Brindamos asesoramiento experto para el desarrollo de
                                estrategias de datos, selección de tecnología y diseño de procesos de gestión de datos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="work-pro-section padding" style="background-color: #333; color: white;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="wow fadeInUp" style="color: white; font-size: 2.5rem; margin-bottom: 20px;">Beneficios del
                        Análisis de Datos</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="100ms">
                        <span class="number">1</span>
                        <div class="number-line"></div>
                        <h3>Descubrimiento de Insights Ocultos</h3>
                        <p style="text-align: justify;">Nuestro análisis avanzado revela patrones, tendencias y
                            correlaciones previamente indetectables, ofreciendo insights profundos que pueden transformar la
                            estrategia de tu negocio.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="200ms">
                        <span class="number">2</span>
                        <div class="number-line"></div>
                        <h3>Optimización de la Toma de Decisiones</h3>
                        <p style="text-align: justify;">El análisis de datos proporciona una base sólida para decisiones
                            empresariales, minimizando riesgos y maximizando la eficiencia y la rentabilidad.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="300ms">
                        <span class="number">3</span>
                        <h3>Mejora Continua</h3>
                        <p style="text-align: justify;">Aplicamos análisis continuo para identificar áreas de mejora,
                            facilitando la optimización de procesos y la innovación constante en tu negocio.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="400ms">
                        <span class="number">4</span>
                        <h3>Personalización del Servicio al Cliente</h3>
                        <p style="text-align: justify;">Utilizamos análisis de datos para segmentar y entender mejor a tus
                            clientes, permitiéndote ofrecer servicios altamente personalizados y mejorar la satisfacción del
                            cliente.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="500ms">
                        <span class="number">5</span>
                        <h3>Visualización de Datos Impactante</h3>
                        <p style="text-align: justify;">Transformamos complejos conjuntos de datos en visualizaciones claras
                            y comprensibles, lo que facilita el análisis y la presentación de información crítica a
                            stakeholders.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 sm-padding">
                    <div class="work-pro-item text-center wow fadeInUp" data-wow-delay="600ms">
                        <span class="number">6</span>
                        <h3>Análisis Predictivo Avanzado</h3>
                        <p style="text-align: justify;">Ofrecemos análisis predictivo para anticipar tendencias,
                            comportamientos del mercado y posibles escenarios futuros, permitiendo a tu empresa estar
                            siempre un paso adelante.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section section-2 bg-grey padding">
        <div class="dots"></div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 sm-padding">
                    <div class="service-content wow fadeInLeft">
                        <h2>Nuestros servicios de análisis de datos</h2>
                        <p style="text-align: justify;">En Grupo de Investigación y Tecnología Aplicada AI, ofrecemos
                            soluciones avanzadas de análisis de
                            datos diseñadas para empoderar tu negocio mediante la transformación de datos brutos en insights
                            accionables. Desde análisis de datos espaciales hasta optimización de procesos y visualización
                            avanzada, nuestro equipo especializado está preparado para ayudarte a navegar por el complejo
                            mundo de los datos, asegurando que puedas tomar decisiones informadas basadas en información
                            precisa y relevante.</p>
                        <a href="{{ route('website.cotizacion') }}" class="default-btn">Cotizar ahora</a>
                    </div>
                </div>
                <div class="col-lg-6 sm-padding">
                    <div class="row services-list">
                        <!-- Análisis de Datos Espaciales -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="100ms">
                                <i class="fas fa-globe-americas"></i>
                                <!-- Ícono actualizado para Análisis de Datos Espaciales -->
                                <h3>Análisis de Datos Espaciales</h3>
                                <p style="text-align: justify;">Identificamos patrones, tendencias y relaciones en datos
                                    geoespaciales para apoyar la toma de decisiones estratégicas en diversos sectores.</p>
                            </div>
                        </div>
                        <!-- Optimización de Procesos -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="300ms">
                                <i class="fas fa-project-diagram"></i>
                                <!-- Ícono actualizado para Optimización de Procesos -->
                                <h3>Optimización de Procesos</h3>
                                <p style="text-align: justify;">Utilizamos el análisis de datos para identificar áreas de
                                    mejora, reducir costos y aumentar la eficiencia operativa de tu negocio.</p>
                            </div>
                        </div>
                        <!-- Visualización de Datos -->
                        <div class="col-md-6 padding-15">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="400ms">
                                <i class="fas fa-chart-bar"></i> <!-- Ícono actualizado para Visualización de Datos -->
                                <h3>Visualización de Datos</h3>
                                <p style="text-align: justify;">Presentamos la información de análisis de forma visual a
                                    través de gráficos, tablas interactivas y mapas para facilitar la interpretación y toma
                                    de decisiones.</p>
                            </div>
                        </div>
                        <!-- Análisis Predictivo -->
                        <div class="col-md-6 padding-15 offset-top">
                            <div class="service-item box-shadow wow fadeInUp" data-wow-delay="500ms">
                                <i class="fas fa-brain"></i> <!-- Ícono actualizado para Análisis Predictivo -->
                                <h3>Análisis Predictivo</h3>
                                <p style="text-align: justify;">Aplicamos modelos de regresión, series temporales y
                                    aprendizaje automático para hacer predicciones fiables sobre eventos futuros y
                                    tendencias del mercado.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>

    <div class="work-pro-section padding">
        <div class="container">
            <div class="cta-content text-center">
                <span class="wow fadeInUp">¿Por qué elegirnos?</span>
                <h2 class="wow fadeInUp" data-wow-delay="300ms">Excelencia en Análisis de Datos</h2>
                <p class="wow fadeInUp" data-wow-delay="400ms">
                    En el Grupo de Investigación y Tecnología Aplicada AI, somos pioneros en el análisis de datos,
                    ofreciendo soluciones avanzadas que transforman los datos en decisiones estratégicas. Desde análisis de
                    datos espaciales hasta optimización de procesos y visualización avanzada, nuestro equipo experto
                    proporciona servicios especializados para identificar patrones, tendencias y relaciones que impulsan el
                    éxito empresarial. Nos comprometemos a entregar análisis descriptivos y predictivos precisos,
                    segmentación de clientes y consultoría en datos para aprovechar al máximo el potencial de tus datos.
                </p>
                <p class="wow fadeInUp" data-wow-delay="500ms">
                    ¿Listo para desbloquear insights transformadores y tomar decisiones basadas en datos? Contáctanos hoy
                    para explorar cómo nuestro análisis de datos avanzado y personalizado puede llevar tus operaciones
                    empresariales al siguiente nivel.
                </p>
                <a href="{{ route('website.cotizacion') }}" class="default-btn wow fadeInUp"
                    data-wow-delay="600ms">Explora
                    Nuestros Servicios</a>
            </div>
        </div>
    </div>

    <section class="service-section section-2">
        <div class="dots"></div>
    </section>
@endsection
