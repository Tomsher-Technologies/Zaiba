function productQuickView($id) {
    if ($('#product-quickview').length) {

        $('#product-quickview').modal('show');
        $('#product-quickview .ps-product--detail').hide();
        $('#product-quickview .loadingIcon').show();

        $.ajax({
            type: "GET",
            url: config.routes.prodcut_quick_view,
            data: {
                'id': $id
            },
            success: function (data) {
                $('.ps-product--detail.ps-product--quickview').html(data);
                $('#product-quickview .ps-product--detail').show();
                $('#product-quickview .loadingIcon').hide();
            }
        });
    }
}

function addEnquiry(slug) {
    $.ajax({
        type: "POST",
        url: config.routes.enquiry_add,
        data: {
            'slug': slug,
            '_token': config.csrf
        },
        success: function (data, status, xhr) {
            if (xhr.status == 200) {
                console.log(data);
                launchToast(data.message);
                $('.headerEnquiryCount').html(data.count)
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            launchToast('Something went wrong, please try again', 'error');
        },
    });
}

function addToCart(slug, qty = 1) {
    $.ajax({
        type: "POST",
        url: config.routes.cart_add,
        data: {
            'slug': slug,
            'quantity': qty,
            '_token': config.csrf
        },
        success: function (data, status, xhr) {
            if (xhr.status == 200) {
                launchToast(data.message);
                $('.headerCartCount').html(data.count)
                Livewire.emit('cartUpdated')
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status == 404) {
                launchToast('Something went wrong, please try again', 'error');
            }
        },
    });
}

function addToWishList(slug) {
    $.ajax({
        type: "POST",
        url: config.routes.wishlist_add,
        data: {
            'slug': slug,
            '_token': config.csrf
        },
        success: function (data, status, xhr) {
            if (xhr.status == 200) {
                launchToast(data.message);
                $('.headerWishlistCount').html(data.count)
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status == 401) {
                launchToast('Please login first', 'error');

            }
            if (xhr.status == 404) {
                launchToast('Something went wrong, please try again', 'error');
            }
        },
    });
}


function owlCarouselConfig2() {
    var target = $('.owl-slider2');
    const rtl = $('html').attr('dir') === 'rtl' ? true : false;
    if (target.length > 0) {
        target.each(function () {
            var el = $(this),
                dataAuto = el.data('owl-auto'),
                dataLoop = el.data('owl-loop'),
                dataSpeed = el.data('owl-speed'),
                dataGap = el.data('owl-gap'),
                dataNav = el.data('owl-nav'),
                dataDots = el.data('owl-dots'),
                dataAnimateIn = el.data('owl-animate-in') ?
                    el.data('owl-animate-in') :
                    '',
                dataAnimateOut = el.data('owl-animate-out') ?
                    el.data('owl-animate-out') :
                    '',
                dataDefaultItem = el.data('owl-item'),
                dataItemXS = el.data('owl-item-xs'),
                dataItemSM = el.data('owl-item-sm'),
                dataItemMD = el.data('owl-item-md'),
                dataItemLG = el.data('owl-item-lg'),
                dataItemXL = el.data('owl-item-xl'),
                dataNavLeft = el.data('owl-nav-left') ?
                    el.data('owl-nav-left') :
                    "<i class='icon-chevron-left'></i>",
                dataNavRight = el.data('owl-nav-right') ?
                    el.data('owl-nav-right') :
                    "<i class='icon-chevron-right'></i>",
                duration = el.data('owl-duration'),
                datamouseDrag =
                    el.data('owl-mousedrag') == 'on' ? true : false;
            if (
                target.children('div, span, a, img, h1, h2, h3, h4, h5, h5')
                    .length >= 2
            ) {
                el.addClass('owl-carousel').owlCarousel({
                    animateIn: dataAnimateIn,
                    animateOut: dataAnimateOut,
                    margin: dataGap,
                    autoplay: dataAuto,
                    autoplayTimeout: dataSpeed,
                    autoplayHoverPause: true,
                    loop: dataLoop,
                    nav: dataNav,
                    mouseDrag: datamouseDrag,
                    touchDrag: true,
                    autoplaySpeed: duration,
                    navSpeed: duration,
                    dotsSpeed: duration,
                    dragEndSpeed: duration,
                    navText: [dataNavLeft, dataNavRight],
                    dots: dataDots,
                    items: dataDefaultItem,
                    rtl: rtl,
                    responsive: {
                        0: {
                            items: dataItemXS,
                        },
                        480: {
                            items: dataItemSM,
                        },
                        768: {
                            items: dataItemMD,
                        },
                        992: {
                            items: dataItemLG,
                        },
                        1200: {
                            items: dataItemXL,
                        },
                        1680: {
                            items: dataDefaultItem,
                        },
                    },
                });
            }
        });
    }
}

// (function ($) {
//     'use strict';

// })(jQuery);