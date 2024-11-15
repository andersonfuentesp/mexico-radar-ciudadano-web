<!DOCTYPE html>
<html lang="en">

@include('landing.partials.head')

<body>

    @include('landing.partials.header')

    <main id="main">
        @yield('main')
    </main>

    @include('landing.partials.footer')
    <a data-scroll href="#header" id="scroll-to-top"><i class="arrow_carrot-up"></i></a>
    @include('landing.partials.scripts')

    <a href="https://wa.me/5215539129260?text=Hola%2C%20estoy%20muy%20interesado%20en%20los%20servicios%20de%20Grupo%20ITAAI"
        class="whatsapp-icon" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

</body>

</html>
