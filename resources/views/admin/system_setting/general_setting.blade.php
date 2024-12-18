@extends('backend.layouts.master')
@section('title', __('general_setting'))
@section('mainContent')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('system_setting') }}</h3>
                <div class="bg-white redious-border pt-30 p-20 p-sm-30">
                    <div class="section-top">
                        <h6>{{ __('general_setting') }}</h6>
                    </div>
                    <form action="{{ route('general.setting') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="site_lang" value="{{ $lang }}">
                        <div class="row gx-20">
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="system_title" class="form-label">{{ __('system_title') }}</label>
                                <input type="text" name="system_name" class="form-control rounded-2 @error('system_name') is-invalid @enderror" id="system_title"
                                    value="{{ old('system_name', setting('system_name', $lang)) }}">
                                @if ($errors->has('system_name'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('system_name') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="companyName" class="form-label">{{ __('company_name') }}</label>
                                <input type="text" name="company_name" class="form-control rounded-2 @error('company_name') is-invalid @enderror" id="companyName"
                                    value="{{ old('company_name', setting('company_name', $lang)) }}">
                                @if ($errors->has('company_name'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('company_name') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="tagline" class="form-label">{{ __('tagline') }}</label>
                                <input id="tagline" type="text" name="tagline" class="form-control rounded-2 @error('tagline') is-invalid @enderror"
                                    value="{{ old('tagline', setting('tagline', $lang)) }}">
                                @if ($errors->has('tagline'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('tagline') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                @include('backend.common.tel-input', [
                                    'name' => 'phone',
                                    'value' => old('phone', setting('phone')),
                                    'label' => __('phone_number'),
                                    'id' => 'phone_number',
                                    'country_id_field' => 'phone_country_id',
                                    'country_id' =>
                                        setting('phone') && setting('phone_country_id')
                                            ? setting('phone_country_id')
                                            : (setting('default_country') ?:
                                            19),
                                ])
                            </div>

                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="emailAddress" class="form-label">{{ __('email_address') }}</label>
                                <input type="email" class="form-control rounded-2 @error('email_address') is-invalid @enderror" id="emailAddress" name="email_address"
                                    value="{{ old('email_address', setting('email_address')) }}">
                                @if ($errors->has('email_address'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('email_address') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="time_zone" class="form-label">{{ __('time_zone') }}</label>
                                <select class="form-select select2 with_search @error('time_zone') is-invalid @enderror" name="time_zone" id="time_zone">
                                    @foreach ($time_zones as $key => $time_zone)
                                        <option value="{{ $time_zone->id }}"
                                            {{ $time_zone->id == old('time_zone', setting('time_zone')) ? 'selected' : '' }}>
                                            {{ $time_zone->gmt_offset > 0 ? "(UTC +$time_zone->gmt_offset)" . ' ' . $time_zone->timezone : $time_zone->gmt_offset }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('time_zone'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('time_zone') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="defaultLAN" class="form-label">{{ __('default_language') }}</label>
                                <div class="select-type-v2">
                                    <select class="form-select select2 without_search" name="default_language"
                                        id="defaultLAN">
                                        @foreach ($languages as $key => $language)
                                            <option value="{{ $key }}"
                                                {{ $key == old('default_language', setting('default_language')) ? 'selected' : '' }}>
                                                {{ __($language) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('default_language'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('default_language') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="country" class="form-label">{{ __('country') }}</label>
                                <div class="select-type-v2">
                                    <select class="form-select with_search" name="default_country" id="country">
                                        @foreach ($countries as $key => $country)
                                            <option value="{{ $key }}"
                                                {{ $key == old('default_country', setting('default_country')) ? 'selected' : '' }}>
                                                {{ $country }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('default_country'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('default_country') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="currency" class="form-label">{{ __('default_currency') }}</label>
                                <input type="text" class="form-control rounded-2" id="default_currency"
                                    name="default_currency"
                                    value="{{ stringMasking(old('default_currency', setting('default_currency')), '*') }}">
                                @if ($errors->has('default_currency'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('default_currency') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="currency_symbol" class="form-label">{{ __('default_currency_symbol') }}</label>
                                <input type="text" class="form-control rounded-2" id="default_currency_symbol"
                                    name="default_currency_symbol"
                                    value="{{ stringMasking(old('default_currency_symbol', setting('default_currency_symbol')), '*') }}">
                                @if ($errors->has('default_currency_symbol'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('default_currency_symbol') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="default_unit" class="form-label">{{ __('default_unit') }}</label>
                                <input type="text" name="default_unit" class="form-control rounded-2 @error('default_unit') is-invalid @enderror" id="default_unit"
                                    value="{{ old('default_unit', __(setting('default_unit', $lang))) }}">
                                @if ($errors->has('default_unit'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('default_unit') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="default_weight" class="form-label">{{ __('default_weight') }}</label>
                                <div class="select-type-v2">
                                    <select class="form-select with_search" name="default_weight" id="default_weight">
                                        @foreach ($charges as $key => $charge)
                                            <option value="{{ $charge->weight }}"
                                                {{ $charge->weight == old('default_weight', setting('default_weight')) ? 'selected' : '' }}>
                                                {{ $charge->weight }} {{ __(setting('default_unit') )}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('default_weight'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('default_weight') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="default_delivery_area" class="form-label">{{ __('default_delivery_area') }}</label>
                                <div class="select-type-v2">
                                    <select class="form-select with_search" name="default_delivery_area" id="default_delivery_area">
                                        @if (settingHelper('preferences')->where('title', 'same_day')->first()->staff)
                                            <option value="same_day"
                                                {{ old('default_delivery_area') == 'same_day' ? 'selected' : (setting('default_delivery_area') == 'same_day' ? 'selected' : '') }}>
                                                {{ __('same_day') }}</option>
                                        @endif
                                        @if (settingHelper('preferences')->where('title', 'next_day')->first()->staff)
                                            <option value="next_day"
                                                {{ old('default_delivery_area') == 'next_day' ? 'selected' : (setting('default_delivery_area') == 'next_day' ? 'selected' : '') }}>
                                                {{ __('next_day') }}</option>
                                        @endif
                                        @if (settingHelper('preferences')->where('title', 'sub_city')->first()->staff)
                                            <option value="sub_city"
                                                {{ old('default_delivery_area') == 'sub_city' ? 'selected' : (setting('default_delivery_area') == 'sub_city' ? 'selected' : '') }}>
                                                {{ __('sub_city') }}</option>
                                        @endif
                                        @if (settingHelper('preferences')->where('title', 'sub_urban_area')->first()->staff)
                                            <option value="sub_urban_area"
                                                {{ old('default_delivery_area') == 'sub_urban_area' ? 'selected' : (setting('default_delivery_area') == 'sub_urban_area' ? 'selected' : '') }}>
                                                {{ __('sub_urban_area') }}</option>
                                        @endif
                                    </select>
                                    @if ($errors->has('default_delivery_area'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('default_delivery_area') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="facebook" class="form-label">{{ __('facebook') }}</label>
                                <input type="text" name="facebook" class="form-control rounded-2 @error('facebook') is-invalid @enderror" id="facebook"
                                    value="{{ old('facebook', setting('facebook', $lang)) }}">
                                @if ($errors->has('facebook'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('facebook') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="twitter" class="form-label">{{ __('twitter') }}</label>
                                <input type="text" name="twitter" class="form-control rounded-2 @error('twitter') is-invalid @enderror" id="twitter"
                                    value="{{ old('twitter', setting('twitter', $lang)) }}">
                                @if ($errors->has('twitter'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('twitter') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="instagram" class="form-label">{{ __('instagram') }}</label>
                                <input type="text" name="instagram" class="form-control rounded-2 @error('instagram') is-invalid @enderror" id="instagram"
                                    value="{{ old('instagram', setting('instagram', $lang)) }}">
                                @if ($errors->has('instagram'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('instagram') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="linkedin" class="form-label">{{ __('linkedin') }}</label>
                                <input type="text" name="linkedin" class="form-control rounded-2 @error('linkedin') is-invalid @enderror" id="linkedin"
                                    value="{{ old('linkedin', setting('linkedin', $lang)) }}">
                                @if ($errors->has('linkedin'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('linkedin') }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="working_day" class="form-label">{{ __('working_day') }}</label>
                                <input type="text" name="working_day" class="form-control rounded-2 @error('working_day') is-invalid @enderror" id="working_day"
                                    value="{{ old('working_day', setting('working_day', $lang)) }}">
                                @if ($errors->has('working_day'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('working_day') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="closing_day" class="form-label">{{ __('closing_day') }}</label>
                                <input type="text" name="closing_day" class="form-control rounded-2 @error('closing_day') is-invalid @enderror" id="closing_day"
                                    value="{{ old('closing_day', setting('closing_day', $lang)) }}">
                                @if ($errors->has('closing_day'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('closing_day') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="privacy_policy" class="form-label">{{ __('rider_privacy_policy') }}</label>
                                <input type="text" name="rider_privacy_policy" class="form-control rounded-2 @error('rider_privacy_policy') is-invalid @enderror" id="rider_privacy_policy"
                                    value="{{ old('rider_privacy_policy', setting('rider_privacy_policy', $lang)) }}">
                                @if ($errors->has('rider_privacy_policy'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('rider_privacy_policy') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="privacy_policy" class="form-label">{{ __('merchant_privacy_policy') }}</label>
                                <input type="text" name="merchant_privacy_policy" class="form-control rounded-2 @error('merchant_privacy_policy') is-invalid @enderror" id="merchant_privacy_policy"
                                    value="{{ old('merchant_privacy_policy', setting('merchant_privacy_policy', $lang)) }}">
                                @if ($errors->has('merchant_privacy_policy'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('merchant_privacy_policy') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <label for="address" class="form-label">{{ __('address') }}</label>
                                <textarea name="address" id="address" class="form-control rounded-2" cols="2" rows="2">{{ old('address', setting('address')) }}</textarea>
                                @if ($errors->has('address'))
                                    <div class="invalid-feedback help-block">
                                        <p>{{ $errors->first('address') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-30">
                                <button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
                                @include('backend.common.loading-btn', ['class' => 'btn sg-btn-primary'])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ static_asset('admin/js/countries.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#system_commission').trigger('input');
        })
        $(document).on('input', '#system_commission', function(event) {
            if (event.target.value == "") {
                return false;
            }

            if (event.target.value > 100) {
                $(this).val(100);
            }
            let organizationCommission = 100 - parseFloat($(this).val())
            $('#organization_commission').val(organizationCommission);
        })
    </script>
@endpush
