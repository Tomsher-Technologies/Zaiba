<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {!! SEO::generate() !!}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">
    @yield('meta')

    <!-- Favicon -->
    <link rel="icon" href="{{ frontendAsset('img/fav_icon.png') }}">

    <link rel='dns-prefetch' href='//fonts.googleapis.com' />
    <link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />

    <!-- Google Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet"> --}}

    <!-- CSS Files -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />

    <link rel="stylesheet" href="{{ frontendAsset('fonts/Linearicons/Linearicons/Font/demo-files/demo.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('css/bulk-style.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/owl-carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/owl-carousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/slick/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/nouislider/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/lightGallery-master/dist/css/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('plugins/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ frontendAsset('css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @if (get_setting('google_analytics') == 1)
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ env('TRACKING_ID') }}');
        </script>
    @endif

    @if (get_setting('facebook_pixel') == 1)
        <!-- Facebook Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endif

    {{ get_setting('header_script') }}

    <script src="{{ frontendAsset('plugins/jquery.min.js') }}"></script>

    @livewireStyles

    @yield('header')

    <style>
        .menu--mobile .sub-menu {
            padding-left: 15px;
        }
    </style>

</head>

<body>
    <!-- aiz-main-wrapper -->
    @include('frontend.inc.header')

    @yield('content')

    @include('frontend.inc.footer')

    @yield('modal')

    @include('frontend.inc.product_quick_view')

    <!-- SCRIPTS -->
    <script src="{{ frontendAsset('plugins/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/popper.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/masonry.pkgd.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/isotope.pkgd.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/jquery.matchHeight-min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/slick/slick/slick.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/slick-animation.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/lightGallery-master/dist/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/sticky-sidebar/dist/sticky-sidebar.min.js') }}"></script>
    <script src="{{ frontendAsset('plugins/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ frontendAsset('plugins/gmap3.min.js') }}"></script> --}}
    <script src="{{ frontendAsset('js/main.js') }}"></script>
    <script src="{{ frontendAsset('js/product_functions.js') }}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    @yield('script')

    @stack('scripts')

    {{ get_setting('footer_script') }}

    <script>
        var config = {
            routes: {
                prodcut_quick_view: "{{ route('product.quick_view') }}",
                newsletter: "{{ route('subscribers.store') }}",
                enquiry_add: "{{ route('enquiry.add') }}",
                enquiry_remove: "{{ route('enquiry.remove') }}",
                wishlist_add: "{{ route('wishlists.store') }}",
                cart_add: "{{ route('cart.addToCart') }}",
                cart_remove: "{{ route('cart.removeFromCart') }}",
                ajax_search: "{{ route('search.ajax') }}",
            },
            csrf: "{{ csrf_token() }}",
        };

        if ($('#currency-change').length > 0) {
            $('#currency-change .ps-dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var currency_code = $this.data('currency');
                    $.post('{{ route('currency.change') }}', {
                        _token: "{{ csrf_token() }}",
                        currency_code: currency_code
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }
    </script>

    @livewireScripts

    <script>
        window.addEventListener('updateCartCount', event => {
            $('.headerCartCount').html(event.detail.count);
        })
    </script>
</body>

</html>
