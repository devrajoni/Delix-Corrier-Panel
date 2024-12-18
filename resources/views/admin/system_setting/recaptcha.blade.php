@extends('backend.layouts.master')
@section('title', __('re_captcha'))
@section('mainContent')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col col-xxl-8 ">
                <h3 class="section-title">{{ __('re_captcha_setting') }}</h3>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <div class="section-top">
                        <h6>{{ __('re_captcha') }}</h6>
                    </div>
                    <form action="{{ route('google.setup') }}" method="post" class="form">@csrf
                        <div class="row gx-20">
                            <div class="col-12">
                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="is_recaptcha_activated" value="{{ setting('is_recaptcha_activated') == 1 ? 1 : 0 }}">
                                    <label class="form-label" for="is_recaptcha_activated">{{ __('status') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="is_recaptcha_activated" class="sandbox_mode" {{ setting('is_recaptcha_activated') == 1 ? 'checked' : '' }}>
                                        <label for="is_recaptcha_activated"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="recaptcha_site_key"
                                           class="form-label">{{ __('site_key') }}</label>
                                    <input type="text" class="form-control rounded-2" id="recaptcha_site_key"
                                           placeholder="{{ __('enter_site_key') }}" name="recaptcha_site_key" value="@if(isDemoMode())****************** @else {{ setting('recaptcha_site_key') }} @endif">
                                    <div class="nk-block-des text-danger">
                                        <p class="recaptcha_Site_key_error error"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="recaptcha_secret"
                                           class="form-label">{{ __('secret_key') }}</label>
                                    <input type="text" class="form-control rounded-2" id="recaptcha_secret"
                                           placeholder="{{ __('enter_secret_key') }}" name="recaptcha_secret" value="@if(isDemoMode())****************** @else {{ setting('recaptcha_secret') }} @endif">
                                    <div class="nk-block-des text-danger">
                                        <p class="recaptcha_secret_error error"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-start align-items-center">
                                <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                @include('common.loading-btn',['class' => 'btn sg-btn-primary'])
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            $(document).on('change', '#default_storage', function () {
                var storage = $(this).val();
                if (storage == 'aws_s3') {
                    $('.aws_div').removeClass('d-none');
                    $('.wasabi_div').addClass('d-none');
                } else if (storage == 'wasabi') {
                    $('.aws_div').addClass('d-none');
                    $('.wasabi_div').removeClass('d-none');
                } else {
                    $('.aws_div').addClass('d-none');
                    $('.wasabi_div').addClass('d-none');
                }
            });
        });
    </script>
@endpush
