@extends('adminlte::page')

@section('plugins.Sweetalert2', true)
@section('plugins.Toastr', true)

@section('css')
    @yield('csscode')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="icon" href="{{ asset('frontend/images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-datatables/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-datatables/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-datatables/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/oficial-select2/css/select2-bootstrap4.min.css') }}">
    <link href="{{ asset('backend/assets/css/app-styles.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Acto Book';
            src: url('{{ asset('backend/assets/fonts/Acto-Book.ttf') }}') format('truetype');
            font-weight: normal;
            font-display: swap;
            font-style: normal;
        }
    </style>

@stop

@include('partials.notification')

@section('js')

    <script src="{{ asset('vendor/oficial-highcharts/js/highcharts.js') }}"></script>
    <script src="{{ asset('vendor/oficial-highcharts/js/exporting.js') }}"></script>
    <script src="{{ asset('vendor/oficial-highcharts/js/export-data.js') }}"></script>
    <script src="{{ asset('vendor/oficial-highcharts/js/accessibility.js') }}"></script>

    @yield('jscode')

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>

    <script src="{{ asset('backend/assets/js/dark-mode.js') }}"></script>

    <script src="{{ asset('vendor/oficial-datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('vendor/oficial-datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-datatables/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('vendor/oficial-select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-select2/js/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-select2/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-select2/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('vendor/oficial-select2/js/ckeditor.js') }}"></script>

    <script src="{{ asset('backend/assets/js/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/code.js') }}"></script>

@stop
