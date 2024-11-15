<script src="{{ asset('website/js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/tether.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/headroom.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/owl.carousel.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/smooth-scroll.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/venobox.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/slick.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/waypoints.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/odometer.min.js') }}"></script>
<script src="{{ asset('website/js/vendor/wow.min.js') }}"></script>
<script src="{{ asset('website/js/toastr.min.js') }}"></script>
<script src="{{ asset('website/js/main.js') }}"></script>

<script>
    @if (Session::has('notification'))
        var type = "{{ Session::get('notification.alert-type', 'info') }}"
        switch (type) {
            case 'info':
                toastr.info(" {{ Session::get('notification.message') }} ");
                break;

            case 'success':
                toastr.success(" {{ Session::get('notification.message') }} ");
                break;

            case 'warning':
                toastr.warning(" {{ Session::get('notification.message') }} ");
                break;

            case 'error':
                toastr.error(" {{ Session::get('notification.message') }} ");
                break;
        }
    @endif
</script>
