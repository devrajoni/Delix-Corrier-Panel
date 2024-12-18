<style>
    .txt-bg-success {
        color: #fff !important;
        background-color: #41cc68  !important;
        /* border: 1px solid #0080001f !important;
        animation: pulse-animation-success 2s infinite; */
    }
    .txt-bg-warning {
        color: #fff !important;
        background-color: #f5a300  !important;
        border-color: #f5a300  !important;
        /* border: 1px solid #f5a30033 !important;
        animation: pulse-animation-warning 2s infinite; */
    }

    .txt-bg-danger {
        color: #fff !important;
        background-color: #ff2424 !important;
        /* border: 1px solid #ff24241a !important;
        animation: pulse-animation-danger 2s infinite; */
    }

    /* @keyframes pulse-animation-success {
      0% {
        box-shadow: 0 0 0 0px #41cc6896;
      }
      100% {
        box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
      }
    }

    @keyframes pulse-animation-warning {
      0% {
        box-shadow: 0 0 0 0px #f5a30096;
      }
      100% {
        box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
      }
    }


    @keyframes pulse-animation-danger {
      0% {
        box-shadow: 0 0 0 0px #ff242480;
      }
      100% {
        box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
      }
    } */


</style>

<nav class="navbar navbar-top navbar-expand-lg bg-body-tertiary py-20 bg-white sticky-top">
    <div class="container-fluid g-5">
        <span class="sidebar-toggler">
            <span class="icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 6H3" stroke="#7E7F92" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                    <path d="M21 12H3" stroke="#7E7F92" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                    <path d="M18 18H3" stroke="#7E7F92" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
            </span>
        </span>
        <a class="navbar-brand ms-auto d-none"
            href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.dashboard') : route('dashboard') }}">
            <img src="{{ getFileLink('80X80',setting('admin_mini_logo')) }}" alt="Logo">
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="las la-ellipsis-v"></span>
        </button>
        <div class="collapse navbar-collapse navbar-content px-lg-20 navbar-respons" id="navbarScroll">
            <div class="navbar-left-content me-lg-auto d-flex align-items-center gap-20">
                @if(Sentinel::getUser()->user_type == 'staff')
                    <ul class="dashboard-btn d-flex align-items-center gap-lg-20 gap-sm-2">
                        <li>
                            <a href="{{ route('clear.cache') }}"
                                class="d-flex align-items-center button-default default-circle-btn gap-2">
                                <i class="las la-hdd"></i>
                                <span>{{ __('clear_cache') }}</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
            <!-- header Middle -->
            @if(Sentinel::getUser()->user_type == 'staff')
            @php
                $isFree             = env('IS_FREE') == 1;
                $expiredDate        = expired_date();
                $availableCredit    = $available_credit;
            @endphp

            @if($isFree || $expiredDate < 0 || $expiredDate <= 7 || ($availableCredit <= 0 && env('VERIENT') != "us") || ($availableCredit < 100 && env('VERIENT') != "us"))
                    <div class="header__midddle @if(env('IS_FREE') == 1)
                        text-warning
                        @elseif(expired_date() < 0)
                            text-danger
                        @elseif(expired_date() <= 7)
                            text-warning
                        @elseif($available_credit <= 0 && env('VERIENT') != "us")
                            text-danger
                        @elseif($available_credit < 100 && env('VERIENT') != "us")
                            text-warning
                        @endif">
                        <div class="left__side">
                            <!-- Swiper Navigation -->
                            <div class="swiper__control">
                                <div class="swiper__navigation">
                                    <div class="header-swipe-prev swiper-button-prev">
                                        <i class="las la-angle-left"></i>
                                    </div>
                                    <div class="header-swipe-next swiper-button-next">
                                        <i class="las la-angle-right"></i>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                            <div class="swiper header__slider">
                                <div class="swiper-wrapper">
                                    <!-- Upgrade Now Notification -->

                                    @if(env('IS_FREE') == 1)
                                        <div class="swiper-slide slide-upgrade-now">
                                            <div class="slider__content">
                                                <i class="las la-exclamation-circle"></i>
                                                <p>{{ __('upgrade_your_subscription') }}</p>
                                                <div class="right__side">
                                                    <div class="group__item">
                                                        <a href="https://delix.cloud" target="_blank" class="btn sg-btn-primary">
                                                            <i class="las la-exclamation-circle"></i> {{ __('upgrade_subscription') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Expired Notification -->
                                    @if(expired_date() < 0 && env('IS_FREE') != 1)
                                        <div class="swiper-slide slide-expired">
                                            <div class="slider__content">
                                                <i class="las la-exclamation-circle"></i>
                                                <p>{{ __('your_subscription_expired') }}</p>
                                                <div class="right__side">
                                                    <div class="group__item">
                                                        <a href="https://delix.cloud" target="_blank" class="btn sg-btn-primary">
                                                            <i class="las la-exclamation-circle"></i> {{ __('renew_subscription') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Expiry Date Coming Soon Notification -->
                                    @if(expired_date() <= 7 && expired_date() >= 0 && env('IS_FREE') != 1)
                                        <div class="swiper-slide slide-expire-date-coming-soon">
                                            <div class="slider__content">
                                                <i class="las la-exclamation-circle"></i>
                                                <p>{{ __('your_subscription_expiring_soon') }}</p>
                                                <div class="right__side">
                                                    <div class="group__item">
                                                        <a href="https://delix.cloud" target="_blank" class="btn sg-btn-primary">
                                                            <i class="las la-exclamation-circle"></i> {{ __('renew_subscription') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- SMS Credit Notifications -->
                                    @if($available_credit <= 0 && env('VERIENT') != "us")
                                        <div class="swiper-slide slide-sms-credit-end">
                                            <div class="slider__content">
                                                <i class="las la-exclamation-circle"></i>
                                                <p>{{ __('your_sms_credit_end') }}</p>
                                                <div class="right__side">
                                                    <div class="group__item">
                                                        <a href="https://delix.cloud" target="_blank" class="btn sg-btn-primary">
                                                            <i class="las la-exclamation-circle"></i> {{ __('buy_sms_credit') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($available_credit < 100 && env('VERIENT') != "us")
                                        <div class="swiper-slide slide-sms-credit-almost-end">
                                            <div class="slider__content">
                                                <i class="las la-exclamation-circle"></i>
                                                <p>{{ __('your_sms_credit_almost_end') }}</p>
                                                <div class="right__side">
                                                    <div class="group__item">
                                                        <a href="https://delix.cloud" target="_blank" class="btn sg-btn-primary">
                                                            <i class="las la-exclamation-circle"></i> {{ __('buy_sms_credit') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif


            <div class="navbar-right-content">
                <ul class="d-flex align-items-center gap-lg-4 gap-sm-2">
                    <li class="visit-website">
                        <a href="{{ route('home') }}" target="_blank">
                            <i class="las la-globe-americas"></i>
                            <span class="icon-hover">{{ __('visit_website') }}</span>
                        </a>
                    </li>
                    <li class="visit-website dropdown notification">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="las la-bell"></i>
                            <span class="has_notifications">{{ $notificationCount }}</span>
                        </a>
                        @include('backend.layouts.package_subscribe')
                    </li>

                    <li class="select-language dropdown pe-lg-20">
                        @php
                            $active_locale      = 'English';
                            $languages          = app('languages');
                            $locale_language    = $languages->where('locale', app()->getLocale())->first();
                            if ($locale_language) {
                                $active_locale  = $locale_language->name;
                            }
                            $active_locales     = Str::lower($active_locale);
                        @endphp
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __($active_locales) }}
                        </a>
                        <ul class="dropdown-menu popup-card">
                            @foreach ($languages as $lang)
                                @php
                                    $name = Str::lower($lang->name);
                                @endphp
                                <li>
                                    <a class="dropdown-item" href="{{ setLanguageRedirect($lang->locale) }}">
                                        <img src="{{ static_asset($lang->flag ?: 'admin/img/flag/united-kingdom.svg') }}"  alt="United Kingdom">
                                        {{ __($name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="dropdown pe-lg-20">
                        <a href="#" class="dropdown-toggle d-flex gap-12" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img class="user-avater" src="{{ getFileLink('80X80', \Sentinel::getUser()->image_id) }}" class="redious-border">
                            <span
                                class="user-name">{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</span>
                            <span class="active_status"></span>
                        </a>
                        <ul class="dropdown-menu popup-card">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.profile') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.profile') : route('staff.profile')) }}">
                                    <i class="la la-user"></i>
                                    <span>{{ __('profile') }}</span>
                                </a>
                            </li>
                            @if(!blank(Sentinel::getUser()->accounts(Sentinel::getUser()->id)))
                                <li>
                                    <a href="{{route('user.accounts')}}" class="dropdown-item">
                                        <span class="icon"><i class="las la-heading"></i></span>
                                        <span>{{ __('accounts') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.statements') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.statements') : route('staff.payment.logs')) }}" class="dropdown-item">
                                    <span class="icon"><i class="icon las la-wallet"></i></span>
                                    <span>{{ __('payout_logs') }}</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.security-settings') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.security-settings') : route('staff.security-settings')) }}">
                                    <i class="la la-user-shield"></i>
                                    <span>{{ __('change_password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.account-activity') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.account-activity') : route('staff.account-activity')) }}">
                                    <i class="la la-file-alt"></i>
                                    <span>{{ __('login_activity') }}</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.logout') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.logout') : route('logout')) }}">
                                    <i class="la la-sign-out"></i>
                                    <span>{{ __('logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
