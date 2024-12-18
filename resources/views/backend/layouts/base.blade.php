<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | @if(env('WHITE_LEVEL') == 1) {{ setting('system_short_name') != '' ? setting('system_short_name') : config('white_level.system_short_name') }} @else
        {{ config('white_level.system_short_name') }} @endif</title>
    <!-- Fav Icon  -->

    @if(env('WHITE_LEVEL') == 1)
        @if (setting('admin_favicon'))
            <link rel="icon" type="image/png"
                href="{{ setting('admin_favicon') && isset(setting('admin_favicon')['original_image']) && is_file_exists(setting('admin_favicon')['original_image']) ? static_asset(setting('admin_favicon')['original_image']) : static_asset('images/default/favicon/favicon-16x16.png') }}">
        @else
            <link rel="icon" type="image/png"
            href="{{ static_asset('images/default/favicon/favicon.png') }}">
        @endif
    @else
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ static_asset('images/default/favicon/favicon.png') }}">
    @endif

    <link rel="manifest" href="{{ static_asset('admin/images/favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ static_asset('admin/images/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    <!-- CSS Files -->
    <!--====== LineAwesome ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/line-awesome.min.css') }}">
    <!--====== select2 CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/select2.min.css') }}">
    <!--====== Nestable CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/nestable.css') }}">
    <!--====== Summernote CSS ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/summernote-lite.min.css') }}">
    <!--====== datatable ======-->
    <link rel="stylesheet" href="{{ static_asset('admin/css/timeline.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/material-design-iconic-font.min.css') }}">
    <link href="{{ static_asset('admin/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/app.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/responsive.min.css') }}">
    <link rel="stylesheet" href="{{ static_asset('admin/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css">
    @if(env('WHITE_LEVEL') == 1)
        @if(setting('theme_color') == 'green')
            <link rel="stylesheet" href="{{ static_asset('admin/css/theme_color/green.css') }}?v={{ time() }}">
        @elseif(setting('theme_color') == 'blue')
            <link rel="stylesheet" href="{{ static_asset('admin/css/theme_color/blue.css') }}?v={{ time() }}">
        @elseif(setting('theme_color') == 'orange')
            <link rel="stylesheet" href="{{ static_asset('admin/css/theme_color/orange.css') }}?v={{ time() }}">
        @elseif(setting('theme_color') == 'purple')
            <link rel="stylesheet" href="{{ static_asset('admin/css/theme_color/purple.css') }}?v={{ time() }}">
        @endif
    @endif
    <link rel="stylesheet" href="{{ static_asset('admin/css/style.css') }}?v={{ time() }}">
    <style>
        .header__midddle.text-danger {
            border-color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity));
        }
        .header__midddle.text-danger .slider__content i,
        .header__midddle.text-danger .right__side .group__item a {
           color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity));
        }
        .header__midddle.text-danger .right__side .group__item a.btn {
           background-color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity)) !important;
           border-color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity)) !important;
           color: #fff;
        }

        .header__midddle.text-warning {
            border-color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity));
        }
        .header__midddle.text-warning .slider__content i,
        .header__midddle.text-warning .right__side .group__item a {
           color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity));
        }
        .header__midddle.text-warning .right__side .group__item a.btn {
           background-color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
           border-color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
           color: #fff;
        }


        .header__midddle {
            flex: 1;
            border: 1px solid var(--sg-primary);
            border-radius: 5px;
            padding: 6px 15px;
            background-color: #ffffff;
            margin: 0 15px;
            position: relative;
            /* display: flex; */
            align-items: center;
            justify-content: space-between;
        }

        .left__side {
            display: flex;
            align-items: center;
            /* width: 70%; */
        }

        .right__side {
            margin-left: auto;
        }
        .right__side .group__item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .right__side .group__item a {
            text-transform: capitalize;
            color: var(--sg-primary);
            white-space: nowrap;

        }
        .right__side .group__item a.btn {
            padding: 2px 12px;
            border-radius: 4px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .right__side .group__item a.btn i {
            color: #fff;
            font-size: 16px;
            top: 0;
        }

        .slider__content {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-left: 5px;
            background: #fff;
            color: #556068;
        }
        .slider__content i {
            font-size: 20px;
            /* color: var(--sg-primary); */
            top: 3px;
            position: relative;
        }
        .swiper__control {
            position: relative;
        }
        .header__midddle .swiper {
            height: 25px;
            margin: 0;
            width: 100%;
        }

        .swiper__control .swiper-pagination {
            width: 50px;
            background: transparent;
            font-size: 13px;
            pointer-events: none;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            color: #556068;
        }
        .swiper__navigation {
            position: relative;
            display: flex;
            align-items: center;
            gap: 25px;
            z-index: 9;
            width: 75px;
            justify-content: space-between;
        }
        .swiper__navigation .swiper-button-next,
        .swiper__navigation .swiper-button-prev {
            font-size: 16px;
            height: 20px;
            width: 20px;
            color: #556068;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            top: 0;
            left: 0;
        }
        .swiper__navigation .swiper-button-next.swiper-button-disabled,
        .swiper__navigation .swiper-button-prev.swiper-button-disabled {
        color: #ddd;
        opacity: 1;
        }
        .swiper__navigation .swiper-button-next::after,
        .swiper__navigation .swiper-button-prev::after {
        display: none;
        }

        @media (max-width: 1199px) {
            .navbar-collapse {
                flex-wrap: wrap;
            }
            .header__midddle {
                order: 3;
                margin: 10px 0 0;
            }
        }

        @media (max-width: 767px) {
            .header__midddle {
                order: 3;
            }
            .navbar-respons {
                flex-wrap: wrap;
            }
        }
        @media (max-width: 575px) {
            .header__midddle {
                flex-wrap: wrap;
                flex: auto;
                height: auto;
                gap: 10px;
                margin: 0;
            }
            .left__side {
                width: 100%;
            }
        }


    </style>
    @stack('css')
    @if(app()->getLocale() =='bn')
        <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
        <style>
            :root {
                --body-fonts: 'SolaimanLipi', Arial, sans-serif !important;
                --heading-font: 'SolaimanLipi', Arial, sans-serif !important;
            }
            /*html * ,.secondary-font, .heading-font {*/
            /*    font-family: 'SolaimanLipi', Arial, sans-serif !important;*/
            /*    !*font-weight: normal !important;*!*/
            /*}*/
        </style>
    @else
        <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --body-fonts: 'jost', sans-serif !important;
                --heading-font: 'jost', sans-serif !important;
            }
        </style>
    @endif
    <script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>

</head>
<body>
<input type="hidden" class="base_url" value="{{ url('/') }}">
@yield('base.content')
@include('backend.layouts.expired_package')

<!--====== Bootstrap & Popper JS ======-->
<script src="{{ static_asset('admin/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>


<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>


<script>
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.withCredentials = true;
    var path = {!! json_encode(url('/')) !!};
</script>

<script src="{{ static_asset('admin/js/jquery.dataTables.min.js') }}"></script>
{{-- <script src="{{ static_asset('admin/datatables-bs5/datatables-bootstrap5.js') }}"></script> --}}
<script src="{{ static_asset('admin/js/dataTables.responsive.min.js') }}"></script>
<!--====== NiceScroll ======-->
<script src="{{ static_asset('admin/js/jquery.nicescroll.min.js') }}"></script>
<!--====== Summernote JS ======-->
<script src="{{ static_asset('admin/js/summernote-lite.min.js') }}"></script>
<!--====== select2 JS ======-->
<script src="{{ static_asset('admin/js/select2.min.js') }}"></script>
<!--====== Chart JS ======-->
<script src="{{ static_asset('admin/js/chart.min.js') }}"></script>
@stack('js_asset')
<script src="{{ static_asset('admin/flatpickr/flatpickr.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
<!--====== MainJS ======-->
<script src="{{ static_asset('admin/js/app.js') }}"></script>
<!--============= toastr=======-->
<script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
{!! Toastr::message() !!}
<script src="{{ static_asset('admin/js/sweetalert211.min.js') }}"></script>
@if (auth()->check() && auth()->user()->role_id > 1)
    <script src="{{ static_asset('admin/js/OneSignalSDK.js') }}" defer></script>
@endif
@stack('js')
@if (session()->has('error'))
    <script>
        toastr.error("{{ session('error') }}")
    </script>
@endif
@if (session()->has('danger'))
    <script>
        toastr.error("{{ session('danger') }}")
    </script>
@endif
@if (session()->has('success'))
    <script>
        toastr.success("{{ session('success') }}")
    </script>
@endif
@php
    if(Sentinel::check()){
        $route              = '';
        $auth               = Sentinel::getUser() ?? jwtUser();;
        $notification_count = App\Models\NotificationUser::where('user_id', $auth->id)->where('is_read', 0)->count();
    }
@endphp

@if (setting('is_pusher_notification_active') && Sentinel::check())
    <script src="{{ static_asset('admin/js/pusher.min.js') }}"></script>
    <script>
        var routeUrl = "{{ $route }}";
        let notificationCount = {{ $notification_count }};
        const pusher = new Pusher('{{ setting('pusher_app_key') }}', {
            cluster: '{{ setting('pusher_app_cluster') }}',
            encrypted: true
        });
        const channel = pusher.subscribe('notification-send-{{ Sentinel::getUser()->id }}');
        channel.bind('App\\Events\\PusherNotification', (data) => {
            var imageUrl = data.image ?  data.image : "{{ static_asset('admin/images/default/user32x32.jpg') }}";
            var notificationHtml = `
                <li>
                    <a class="dropdown-item" href="${routeUrl.replace('__notification_id__', data.id)}" style="text-align:left">
                        <div class="notification-content d-flex justify-content-between">
                            <div class="notification-img inst-avtar">
                                <img src="${imageUrl}" alt="">
                            </div>
                            <div class="notification-text">
                                <h6>${data.created_by}</h6>
                                <p>${data.details}</p>
                            </div>
                            <span class="notification-time" style="text-align:right">${data.created_at}</span>
                        </div>
                    </a>
                </li>`;
            $('.pusher-notification').append(notificationHtml);
            toastr[data.message_type](data.message);
            notificationCount++;
            $('.has_notifications').text(notificationCount);
            $('.has_notifications').show();
        });
    </script>
@endif
<script>
    $.each($('ul.sub-menu'), function (index, item) {
        if ($(item).find('li').length == 0) {
            // $(item).parents('li').hide();
        }
    })
    flatpickr(".date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

    flatpickr(".date-picker", {
    dateFormat: "Y-m-d",
    enableTime: false,
    time_24hr: false,
    });
</script>
    {{-- Ajax Select2 Search Global Function --}}
    <script>
        const delivery_man_search_url = "{{ route('get-delivery-man-live') }}";
        const getLiveSearch = (searchUrl, placeholder = 'Select Value') => {
            return {
                placeholder: placeholder,
                minimumInputLength: 2,
                ajax: {
                    type: "GET",
                    dataType: 'json',
                    url: searchUrl,
                    data: function(params) {
                        return {
                            q: params.term
                        }
                    },
                    delay: 400,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        }
                    },
                    cache: true
                }
            }
        }
    </script>
@stack('script')
@isset($dataTable)
{{ $dataTable->scripts() }}
@endisset
@isset($datatable)
{{ $datatable->scripts() }}
@endisset
<script>
    $(document).ready(function() {
        var dataTableBuilder = $('#dataTableBuilder');
        if (dataTableBuilder.length) {
            var length = $('#dataTableBuilder_length');
            var search = $('#dataTableBuilder_filter');
            search.find('label').addClass('mb-0');
            search.addClass('d-flex');
            search.appendTo('#data_table_option_container');
            length.appendTo('#data_table_option_container');
            var dataTableButtons = $('.dt-buttons');
            if (dataTableButtons.length && dataTableButtons.html().length === 0) {
                dataTableButtons.remove();
            }
            $('tfoot').remove();
        }
    });
</script>


<script>
    /*********************************
    /*  Header Slider
    *********************************/
    $(document).ready(function () {
        // Initialize Swiper
        var swiper = new Swiper(".header__slider", {
            direction: "vertical",
            pagination: {
                el: ".swiper-pagination",
                type: "fraction",
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: true,
            },
            navigation: {
                nextEl: ".header-swipe-next",
                prevEl: ".header-swipe-prev",
            },
            on: {
                init: function () {
                    // Remove 'swiper-pagination-lock' if there is a single slide
                    if ($('.swiper-wrapper .swiper-slide').length <= 1) {
                        $('.swiper-pagination').removeClass('swiper-pagination-lock');
                    }
                }
            }
        });

        // Custom functionality for navigation buttons
        $(".header-swipe-next").on("click", function () {
            swiper.slideNext(); // Go to the next slide
        });

        $(".header-swipe-prev").on("click", function () {
            swiper.slidePrev(); // Go to the previous slide
        });

        // Update header class on slide change
        swiper.on("slideChange", function () {
            updateHeaderClass();
        });

        function updateHeaderClass() {
            var activeSlide = swiper.slides[swiper.activeIndex];
            $(".header__middle").removeClass("text-warning text-danger");

            if ($(activeSlide).hasClass("slide-upgrade-now")) {
                $(".header__middle").addClass("text-warning");
            } else if ($(activeSlide).hasClass("slide-expired")) {
                $(".header__middle").addClass("text-danger");
            } else if ($(activeSlide).hasClass("slide-expire-date-coming-soon")) {
                $(".header__middle").addClass("text-warning");
            } else if ($(activeSlide).hasClass("slide-sms-credit-end")) {
                $(".header__middle").addClass("text-danger");
            } else if ($(activeSlide).hasClass("slide-sms-credit-almost-end")) {
                $(".header__middle").addClass("text-warning");
            }
        }
    });

</script>
<script src="{{ static_asset('admin/js/custom.js')}}"></script>

@if (expireSubscription())
<script>
    $(document).ready(function() {
        $('#accessDeniedModal').modal('show');
        $('#daysRemaining').text("{{ session('daysRemaining') }} days remaining.");
    });
</script>
@endif

</body>
</html>
