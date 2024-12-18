<header class="navbar-dark-v1">
    <div class="header-position">
        <span class="sidebar-toggler">
            <i class="las la-times"></i>
        </span>
        <div class="dashboard-logo d-flex justify-content-center align-items-center py-20">
            <a class="logo" href="{{ route('dashboard') }}">
                @if(env('WHITE_LEVEL') == 1)
                    <img style="width: 100% !important;max-height: 38px;" src="{{ setting('admin_logo') && @is_file_exists(setting('admin_logo')['original_image']) ? get_media(setting('admin_logo')['original_image']) : get_media('images/default/logo/logo_light.png') }}"
                     alt="Logo">
                @else
                    <img style="width: 100% !important;max-height: 38px;" src="{{ get_media('images/default/logo/logo_light.png') }}"
                    alt="Logo">
                @endif
            </a>
        </div>
        <nav class="side-nav">
            <ul>
                <li class="{{ menuActivation(['*/dashboard','dashboard/*'], 'active') }}">
                    <a
                        href="{{ route('dashboard') }}" >
                        <i class="las la-tachometer-alt"></i>
                        <span>{{ __('dashboard') }}</span>
                    </a>
                </li>
                @if (\Sentinel::getUser()->user_type == 'staff' || \Sentinel::getUser()->user_type == 'admin')
                    @if (hasPermission('parcel_read'))
                        <li class="{{ menuActivation(['admin/parcel', 'admin/parcel/*'], 'active') }}">
                            <a href="{{ route('parcel') }}">
                                <i class="las la-box"></i>
                                <span>{{ __('parcels') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('withdraw_read') || hasPermission('bulk_withdraw_read'))
                        <li class="{{ menuActivation(['admin/withdraws', 'admin/withdraw*', 'admin/bulk/withdraw*'], 'active') }}">
                            <a href="#payment-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                                aria-controls="payment-menu"
                                aria-expanded="{{ menuActivation(['admin/withdraws', 'admin/withdraw/*', 'bulk/withdraw', 'admin/bulk/withdraw/*'], 'true', 'false') }}">
                                <i class="las la-wallet"></i>
                                <span>{{ __('payout') }}</span>
                            </a>
                            <ul class="sub-menu collapse {{ menuActivation(['admin/withdraws', 'admin/withdraw/*', 'admin/bulk/withdraw', 'admin/bulk/withdraw/*'], 'show') }} "
                                id="payment-menu">
                                @if (hasPermission('withdraw_read'))
                                    <li>
                                        <a href="{{ route('admin.withdraws') }}"
                                            class="{{ menuActivation(['admin/withdraws', 'admin/withdraw/*'], 'active') }}"><span>{{ __('all_payout') }}</span></a>
                                    </li>
                                @endif
                                @if (hasPermission('bulk_withdraw_read'))
                                    <li>
                                        <a href="{{ route('admin.withdraws.bulk') }}"
                                            class="{{ menuActivation(['admin/bulk/withdraw', 'admin/bulk/withdraw/*'], 'active') }}"><span>{{ __('bulk_payout') }}</span></a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('income_read') ||
                            hasPermission('expense_read') ||
                            hasPermission('account_read') ||
                            hasPermission('fund_transfer_read'))
                        <li
                            class="{{ menuActivation(['admin/incomes', 'admin/income/*', 'admin/expenses', 'admin/expense/*', 'admin/accounts', 'admin/account/*', 'admin/fund-transfer', 'admin/fund-transfer/*'], 'active') }}">
                            <a href="#account-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ menuActivation(['admin/incomes', 'admin/income/*', 'admin/expenses', 'admin/expense/*', 'admin/accounts', 'admin/account/*', 'admin/fund-transfer', 'admin/fund-transfer/*'], 'true', 'false') }}"
                                aria-controls="account-menu">
                                <i class="la la-dollar"></i>
                                <span>{{ __('accounts') }}</span>
                            </a>
                            <ul id="account-menu"
                                class="sub-menu collapse {{ menuActivation(['admin/incomes', 'admin/income/*', 'admin/expenses', 'admin/expense/*', 'admin/expense-create', 'admin/expenses', 'admin/accounts', 'admin/account/*', 'admin/account-statement/*', 'admin/fund-transfer', 'admin/fund-transfer/*', 'admin/credit-from-merchant-create'], 'show') }}">
                                @if (hasPermission('income_read'))
                                    <li>
                                        <a href="{{ route('incomes') }}"
                                            class="{{ menuActivation(['admin/incomes', 'admin/income/*', 'admin/income/*', 'admin/credit-from-merchant-create'], 'active') }}">
                                            <span>{{ __('incomes') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('expense_read'))
                                    <li>
                                        <a href="{{ route('expenses') }}"
                                            class="{{ menuActivation(['admin/expenses', 'admin/expense/*'], 'active') }}">
                                            <span>{{ __('expenses') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('account_read'))
                                    <li>
                                        <a href="{{ route('admin.account') }}"
                                            class="{{ menuActivation(['admin/accounts', 'admin/account/*', 'admin/account-statement/*'], 'active') }}">
                                            <span>{{ __('accounts') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('fund_transfer_read'))
                                    <li>
                                        <a href="{{ route('admin.fund-transfer') }}"
                                            class="{{ menuActivation(['admin/fund-transfer', 'admin/fund-transfer/*'], 'active') }}">
                                            <span>{{ __('fund_transfers') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (hasPermission('merchant_read'))
                        <li
                            class="{{ menuActivation(['admin/merchants', 'admin/merchants/*', 'admin/merchant/*', 'admin/merchant-edit/*', 'admin/merchant-staff/*', 'admin/merchant-staff/personal-info/*', 'admin/merchant-staff-account-activity/*'], 'active') }}">
                            <a href="{{ route('merchant') }}">
                                <i class="la la-shopping-cart"></i>
                                <span>{{ __('merchants') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('deliveryman_read'))
                        <li
                            class="{{ menuActivation(['admin/delivery-man', 'admin/delivery-man/*', 'admin/delivery-man-edit/*', 'admin/delivery-man-create', 'admin/delivery-man-statements/*', 'admin/delivery-man-account-activity/*', 'admin/delivery-man-personal-info/*'], 'active') }}">
                            <a href="{{ route('delivery.man') }}">
                                <i class="la la-biking"></i>
                                <span>{{ __('delivery_man') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('report_read') &&
                            (hasPermission('transaction_history_read') ||
                                (hasPermission('parcels_summary_read') || hasPermission('total_summary_read')) ||
                                hasPermission('income_report_read') ||
                                hasPermission('expense_report_read')))
                        <li
                            class="{{ menuActivation(['admin/reports/*', 'admin/parcels', 'admin/total-summary', 'admin/income-expense', 'admin/merchant-summary', 'admin/search-parcels', 'admin/total-summery-report', 'admin/merchant-summary-report', 'admin/transactions', 'admin/search-income-expense'], 'active') }}">
                            <a href="#report-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ menuActivation(['admin/reports/*', 'admin/parcels', 'admin/total-summary', 'admin/income-expense', 'admin/merchant-summary', 'admin/search-parcels', 'admin/total-summery-report', 'admin/merchant-summary-report', 'admin/transactions', 'admin/search-income-expense'], 'true', 'false') }}"
                                aria-controls="report-menu">
                                <i class="la la-chart-bar"></i>
                                <span>{{ __('reports') }} </span>
                            </a>
                            <ul id="report-menu"
                                class="sub-menu collapse {{ menuActivation(['admin/reports/*', 'admin/parcels', 'admin/total-summary', 'admin/income-expense', 'admin/merchant-summary', 'admin/transactions', 'admin/search-parcels', 'admin/total-summery-report', 'admin/merchant-summary-report', 'admin/search-income-expense'], 'show') }}">
                                @if (hasPermission('transaction_history_read'))
                                    <li id="transaction-history-sub">
                                        <a href="{{ route('admin.transaction_history') }}"
                                            class="{{ menuActivation(['admin/reports/transaction-history*', 'admin/transactions*'], 'active') }}"><span>{{ __('transaction_history') }}</span></a>
                                    </li>
                                @endif
                                @if (hasPermission('parcels_summary_read'))
                                    <li id="parcel-summery-sub">
                                        <a href="{{ route('admin.parcels') }}"
                                            class="{{ menuActivation(['admin/reports/parcels*', 'admin/reports/search-parcels*'], 'active') }}"><span>{{ __('parcels_summary') }}</span></a>
                                    </li>
                                @endif
                                @if (hasPermission('total_summary_read'))
                                    <li>
                                        <a href="{{ route('admin.total_summery') }}"
                                            class="{{ menuActivation(['admin/reports/total-summary*', 'admin/total-summery-report*'], 'active') }}"><span>{{ __('total_summary') }}</span></a>
                                    </li>
                                @endif
                                @if (hasPermission('income_expense_report_read'))
                                    <li id="income-expense-sub">
                                        <a href="{{ route('admin.income.expense') }}"
                                            class="{{ menuActivation(['admin/reports/income-expense*', 'admin/reports/search-income-expense*'], 'active') }}"><span>{{ __('income') . '/' . __('expense') }}</span></a>
                                    </li>
                                @endif
                                @if (hasPermission('merchant_summary_report_read'))
                                    <li id="income-expense-sub">
                                        <a href="{{ route('admin.merchant.summary') }}"
                                            class="{{ menuActivation(['admin/reports/merchant-summary*', 'admin/merchant-summary-report*'], 'active') }}"><span>{{ __('merchant_summary') }}</span></a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
                @if (hasPermission('branch_read'))
                    <li class="{{ menuActivation(['admin/branches', 'admin/branch/*'], 'active') }}">
                        <a href="{{ route('admin.branch') }}">
                            <i class="las la-code-branch"></i>
                            <span>{{ __('branch') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('third_party_read'))
                    <li class="{{ menuActivation(['admin/third-parties', 'admin/third-party/*'], 'active') }}">
                        <a href="{{ route('admin.third-parties') }}">
                            <i class="las la-handshake"></i>
                            <span>{{ __('delivery_partner') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('user_read') || hasPermission('role_read'))
                    <li
                        class="{{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*'], 'active') }}">
                        <a href="#staff-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*', 'admin/user-create'], 'true', 'false') }}"
                            aria-controls="staff-menu">
                            <i class="la la-users"></i>
                            <span>{{ __('user_manage') }}</span>
                        </a>
                        <ul id="staff-menu"
                            class="sub-menu collapse {{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*', 'admin/user-create'], 'show') }}">
                            @if (hasPermission('user_read'))
                                <li>
                                    <a href="{{ route('users') }}"
                                       class="{{ menuActivation(['admin/users', 'admin/user/*'], 'active') }}"><span>{{ __('all_staff') }}</span></a>
                                </li>
                            @endif
                            @if (hasPermission('role_read'))
                                <li>
                                    <a href="{{ route('roles.index') }}"
                                        class="{{ menuActivation(['admin/roles', 'admin/roles/*'], 'active') }}"><span>{{ __('roles') }}</span></a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (hasPermission('sms.history') ||hasPermission('sms_setting_read') || hasPermission('sms_campaign_message_send') || hasPermission('custom_sms_send') || hasPermission('email_template_read') || hasPermission('email_configuration') || hasPermission('sms.credit') || hasPermission('sms.gateway'))
                    <li
                        class="{{ menuActivation(['admin/sms/sms-preference', 'admin/sms/*', 'super-admin/sms/setting','admin/email/server-configuration*', 'admin/email/template*','admin/sms-history*', 'admin/sms-credit*'], 'active') }}">
                        <a href="#notification-setting-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ menuActivation(['admin/sms/sms-preference', 'admin/sms/*', 'super-admin/sms/setting','admin/email/server-configuration*', 'admin/email/template*','admin/sms-history*', 'admin/sms-credit*'], 'true', 'false') }}"
                            aria-controls="notification-setting-menu">
                            <i class="las la-sms"></i>
                            <span>
                                @if(Sentinel::getUser()->is_super_admin == 1)
                                    {{ __('sms_n_email') }}
                                @else
                                    {{ __('sms') }}
                                @endif
                            </span>

                        </a>
                        <ul id="notification-setting-menu"
                            class="sub-menu collapse {{ menuActivation(['admin/sms/sms-preference', 'admin/sms/*', 'super-admin/sms/setting', 'admin/email/server-configuration*', 'admin/email/template*','admin/sms-history*', 'admin/sms-credit*'], 'show') }}">
                            @if (hasPermission('sms.history'))
                            <li><a class="{{ menuActivation(['admin/sms-history*'], 'active') }}"
                                       href="{{ route('sms.history') }}">{{ __('sms_history') }}</a></li>
                            @endif
                            @if (hasPermission('preference'))
                                <li>
                                    <a href="{{ route('sms.preference.setting') }}"
                                        class="{{ menuActivation(['admin/sms/sms-preference'], 'active') }}">
                                        <span>{{ __('sms_setting') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('sms_gateway') && (env('VERIENT') == 'us' || Sentinel::getUser()->is_super_admin == 1))
                                <li><a href="{{ route('sms.setting') }}"
                                    class="{{ menuActivation(['super-admin/sms/setting'], 'active') }}">
                                    <span>{{ __('sms_gateway') }}</span></a>
                                </li>
                            @endif
                            @if (hasPermission('sms.credit') && (env('VERIENT') !== 'us' || Sentinel::getUser()->is_super_admin == 1))
                                <li><a class="{{ menuActivation(['admin/sms-credit*'], 'active') }}"
                                       href="{{ route('sms.credit') }}">{{ __('sms_credit') }}</a></li>
                            @endif
                            @if (hasPermission('email_template_read'))
                                <li><a class="{{ menuActivation('admin/email/template*', 'active') }}"
                                        href="{{ route('email.template') }}">{{ __('email_template') }}</a></li>
                            @endif
                            @if (hasPermission('email_configuration'))
                                <li><a class="{{ menuActivation('admin/email/server-configuration*', 'active') }}"
                                        href="{{ route('email.server-configuration') }}">{{ __('email_configuration') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if((env('BRANDED_WEBSITE') != 0) || (\Sentinel::getUser()->is_super_admin == 1))
                    @if (hasPermission('website.themes') || hasPermission('partner_logo.index') || hasPermission('testimonials.index') || hasPermission('faqs.index') || hasPermission('website.cta') || hasPermission('footer.content') || hasPermission('website_setting.seo') || hasPermission('website_setting.custom_css') || hasPermission('website_setting.custom_js') || hasPermission('website_setting.google_setup') || hasPermission('website_setting.fb_pixel') || hasPermission('pages.index'))
                        <li class="{{ menuActivation(['admin/website/theme-options*', 'admin/website/contact*', 'admin/website/pop-up*', 'admin/website/pricing*', 'admin/website/services*', 'admin/website/statistics*', 'admin/website/menu*', 'admin/website/news-and-events*', 'admin/website/abouts*', 'website/features*', 'admin/website/statistic*', 'admin/website/section-title-subtitle*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'active') }}">
                            <a href="#website-setting" class="dropdown-icon" data-bs-toggle="collapse"
                                aria-expanded="{{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/news-and-events*', 'admin/website/pop-up*', 'admin/website/services*', 'admin/website/statistics*',  'admin/website/abouts*', 'website/features*', 'admin/website/section-title-subtitle*', 'admin/website/statistic*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'active') }}"
                                aria-controls="website-setting">
                                <i class="las la-cog"></i>
                                <span>{{ __('website_settings') }}</span>
                            </a>
                            <ul class="sub-menu collapse {{ menuActivation(['admin/website/theme-options*', 'admin/website/pop-up*', 'admin/website/contact*', 'admin/website/pricing*', 'admin/website/services*', 'admin/website/statistics*', 'admin/website/menu*', 'admin/website/section-title-subtitle*', 'admin/website/statistic*', 'admin/website/news-and-events*', 'admin/website/abouts*', 'admin/website/features*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*','admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting', 'admin/website/pages*'], 'show') }}"
                                id="website-setting">
                                @if (hasPermission('website.themes') || hasPermission('partner_logo.index') || hasPermission('testimonials.index') || hasPermission('faqs.index') || hasPermission('website.cta') || hasPermission('footer.content') || hasPermission('website_setting.seo') || hasPermission('website_setting.custom_css') || hasPermission('website_setting.custom_js') || hasPermission('website_setting.google_setup') || hasPermission('website_setting.fb_pixel'))
                                    <li>
                                        <a class="{{ menuActivation(['admin/website/theme-options*', 'admin/website/menu*', 'admin/website/pop-up*', 'admin/website/contact*', 'admin/website/pricing*', 'admin/website/services*', 'admin/website/statistics*', 'admin/website/section-title-subtitle*', 'admin/website/statistic*', 'admin/website/news-and-events*', 'admin/website/abouts*', 'admin/website/features*', 'admin/website/hero-section*', 'admin/website/partner-logo*', 'admin/website/primary-content-setting*', 'admin/website/useful-link-setting*', 'admin/website/quick-link-setting*', 'admin/website/payment-banner-setting*', 'admin/website/copyright-setting', 'admin/website/story*', 'admin/website/unique-feature*', 'admin/website/feature*', 'admin/website/ai-chat*', 'admin/website/testimonials*', 'admin/website/advantage*', 'admin/website/faqs*', 'admin/website/cta*', 'admin/website-themes', 'admin/website-seo', 'admin/hero-section', 'admin/google-setup', 'admin/custom-js', 'admin/custom-css', 'admin/facebook-pixel', 'admin/header-menu', 'admin/header-footer', 'admin/header-content', 'admin/footer-menu', 'admin/social-link-setting'], 'active') }}"
                                            href="{{ route('admin.theme.options') }}">{{ __('all_setting') }}</a>
                                    </li>
                                @endif

                                @if (hasPermission('pages.index'))
                                    <li><a href="{{ route('pages.index') }}"
                                            class="{{ menuActivation('admin/website/pages*', 'active') }}">{{ __('pages') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
                @if (hasPermission('settings_read'))
                    <li
                        class="{{ menuActivation(['admin/preference-setting', 'super-admin/setting/pusher-notification','super-admin/storage-setting', 'super-admin/cron-setting', 'admin/setting/one-signal-notification', 'admin/custom-notification', 'admin/custom-notification*', 'admin/setting/*', 'admin/otp-setting', 'admin/languages', 'admin/countries', 'admin/payment-method', 'admin/payment-method/*'], 'active') }}">
                        <a href="#setting-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ menuActivation(['admin/payment-method', 'admin/payment-method/*'], 'true', 'false') }}"
                            aria-controls="setting-menu">
                            <i class="las la-cogs"></i>
                            <span>{{ __('system_settings') }}</span>
                        </a>
                        <ul id="setting-menu"
                            class="sub-menu collapse {{ menuActivation(['admin/preference-setting','super-admin/storage-setting', 'super-admin/cron-setting', 'super-admin/setting/pusher-notification', 'admin/setting/one-signal-notification', 'admin/custom-notification', 'admin/custom-notification*', 'admin/otp-setting', 'admin/setting/*', 'admin/languages', 'admin/countries', 'admin/payment-method', 'admin/payment-method/*','super-admin/google-recaptcha'], 'show') }}">

                            @if (hasPermission('general_setting'))
                                <li><a class="{{ menuActivation('admin/setting/system-setting', 'active') }}"
                                        href="{{ route('general.setting') }}">{{ __('general_setting') }}</a></li>
                            @endif
                            @if (hasPermission('storage_setting'))
                                <li><a class="{{ menuActivation('super-admin/storage-setting', 'active') }}"
                                       href="{{ route('storage.setting') }}">{{ __('storage_setting') }}</a></li>
                            @endif
                            @if (hasPermission('language_read'))
                                <li><a class="{{ menuActivation(['admin/languages', 'admin/language/*'], 'active') }}"
                                        href="{{ route('languages.index') }}">{{ __('language_settings') }}</a>
                                </li>
                            @endif

                            @if (hasPermission('payment_method_create'))
                                <li><a class="{{ menuActivation(['admin/setting/payment-method*', 'admin/sms/payment-method/*'], 'active') }}"
                                        href="{{ route('admin.payment.method') }}">{{ __('payout_method') }}</a>
                                </li>
                            @endif

                            @if (hasPermission('preference_setting'))
                                <li><a class="{{ menuActivation(['admin/setting/preference-setting*'], 'active') }}"
                                        href="{{ route('preference.setting') }}">{{ __('preference') }}</a>
                                </li>
                            @endif

                            @if (hasPermission('panel_setting') && env('WHITE_LEVEL') == 1)
                                <li><a class="{{ menuActivation('admin/setting/white-level-setting', 'active') }}"
                                        href="{{ route('admin.white-level.setting') }}">{{ __('white_level_setting') }}</a>
                                </li>
                            @endif
                            @if (hasPermission('country_read'))
                                <li><a class="{{ menuActivation('admin/setting/countries', 'active') }}"
                                        href="{{ route('countries.index') }}">{{ __('country') }}</a></li>
                            @endif
                            @if (hasPermission('charge_setting'))
                                <li>
                                    <a href="{{ route('charges.setting') }}"
                                        class="{{ menuActivation('admin/setting/charges-setting', 'active') }}">
                                        <span>{{ __('default_charge') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('packaging_charge_setting'))
                                <li>
                                    <a href="{{ route('packaging.charge.setting') }}"
                                        class="{{ menuActivation(['admin/setting/packaging-charge-setting'], 'active') }}">
                                        <span>{{ __('packaging_type_and_charges') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (hasPermission('re_captcha_setting'))
                                <li>
                                    <a class="{{ menuActivation('super-admin/google-recaptcha', 'active') }}"
                                        href="{{ route('google.recaptcha') }}">{{ __('re_captcha') }}</a>
                                </li>
                            @endif
                            @if (hasPermission('cron_setting'))
                                <li>
                                    <a class="{{ menuActivation('super-admin/cron-setting', 'active') }}"
                                    href="{{ route('cron.setting') }}">{{ __('cron_job') }}</a>
                                </li>
                            @endif
                            @if (hasPermission('pusher_setting'))
                                <li>
                                    <a class="{{ menuActivation('super-admin/setting/pusher-notification', 'active') }}"
                                        href="{{ route('pusher.notification') }}">{{ __('pusher') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (hasPermission('apikeys.index') && hasPermission('mobile_app'))
                    <li
                        class="{{ menuActivation(['super-admin/apikeys*'], 'active') }}">
                        <a href="{{ route('apikeys.index') }}">
                            <i class="las la-mobile"></i>
                            <span>{{ __('mobile_app_setting') }}</span>
                        </a>
                    </li>
                @endif

                @if (hasPermission('notice_read'))
                    <li class="{{ menuActivation(['admin/notice', 'admin/notice/*'], 'active') }}">
                        <a href="{{ route('notice') }}">
                            <i class="las la-bell"></i>
                            <span>{{ __('notice') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('usage'))
                    <li class="{{ menuActivation(['admin/usage*'], 'active') }}">
                        <a href="{{ route('usage') }}">
                            <i class="las la-chart-line"></i>
                            <span>{{ __('usage') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('system_update') || hasPermission('server_info'))
                    <li class="{{ menuActivation(['super-admin/utility/*'], 'active') }}">
                        <a href="#utility" class="dropdown-icon" data-bs-toggle="collapse"
                            aria-expanded="{{ menuActivation(['super-admin/utility/*'], 'true', 'false') }}"
                            aria-controls="utility">
                            <i class="las la-server"></i>
                            <span>{{ __('utility') }}</span>
                        </a>
                        <ul class="sub-menu collapse {{ menuActivation(['super-admin/utility/*'], 'show') }}"
                            id="utility">
                            @if (hasPermission('system_update'))
                                <li><a class="{{ menuActivation(['super-admin/utility/system-update'], 'active') }}"
                                        href="{{ route('system.update') }}">{{ __('system_update') }}</a></li>
                            @endif
                            @if (hasPermission('server_info'))
                                <li>
                                    <a class="{{ menuActivation(['super-admin/utility/server-info', 'super-admin/utility/system-info', 'super-admin/utility/extension-library', 'super-admin/utility/file-system-permission'], 'active') }}"
                                        href="{{ route('server.info') }}">{{ __('server_information') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="footer_copyright">
            @if(env('IS_FREE') == 1)
                {{-- <a href="#" class="upgrade__btn">{{ __('upgrade_now') }}</a> --}}
            @endif
            <p>{{ setting('admin_panel_copyright_text', app()->getLocale()) }}</p>
            @if(env('WHITE_LEVEL') !='1')
                <p>
                    {!! config('white_level.side_bar_footer_text') !!}
                </p>
            @endif
        </div>
    </div>
</header>
