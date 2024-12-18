@extends('backend.layouts.master')
@section('title')
{{__('sms_history')}} {{__('lists')}}
@endsection
@section('mainContent')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <section class="oftions">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-6 col-md-6">
                                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                                        <div class="analytics clr-1">
                                            <div class="analytics-icon">
                                                <i class="las la-credit-card"></i>
                                            </div>


                                            <div class="analytics-content">
                                                <h4>{{ (setting('total_credit') != "" ? setting('total_credit')  : 0) - (setting('total_usages') != "" ? setting('total_usages') : 0) }}</h4>
                                                <p>{{__('available_credit')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-6 col-md-6">
                                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                                        <div class="analytics clr-3">
                                            <div class="analytics-icon">
                                                <i class="las la-wallet"></i>
                                            </div>

                                            <div class="analytics-content">
                                                <h4>{{ $last_credit_added }}</h4>
                                                <p>{{__('last_credit_added')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-6 col-md-6">
                                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                                        <div class="analytics clr-4">
                                            <div class="analytics-icon">
                                                <i class="las la-check-circle"></i>
                                            </div>
                                            <div class="analytics-content">

                                                <h4>{{ $sms_delivered }}</h4>
                                                <p>{{__('sms_delivered')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-xl-6 col-md-6">
                                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                                        <div class="analytics clr-2">
                                            <div class="analytics-icon">
                                                <i class="las la-ban"></i>
                                            </div>
                                            <div class="analytics-content">
                                                <h4>{{ $sms_rejected }}</h4>
                                                <p>{{__('sms_rejected')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="default-list-table table-responsive yajra-dataTable">
                                                {{ $dataTable->table(['class' => 'dt-responsive table'], true) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
