@extends('backend.layouts.master')

@section('title')
    {{__('usage').' '.__('lists')}}
@endsection

@section('mainContent')
    <div class="container-fluid">
        <div class="row gx-20 d-flex justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="section-title">{{ __('usages_report') }}</h3>
                    </div>
                </div>
                <section class="oftions">
                    <div class="container-fluid">
                        <div class="p-20 p-sm-30 pt-sm-30">
                            <div class="row">
                                <div class="default-list-table  yajra-dataTable">
                                        <div id="dataTableBuilder_wrapper" class="dataTables_wrapper">
                                            <table class="dt-responsive table dataTable" id="dataTableBuilder">
                                                <thead>
                                                    <tr role="row">
                                                        <th title="#" class="sorting_disabled" rowspan="1" colspan="1" style="width: 80% !important;" aria-label="#">{{ __('title') }}</th>

                                                        <th title="Status" class="sorting_disabled" rowspan="1" colspan="1" aria-label="Status"">{{ __('count') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('active_merchant') }}</td>
                                                        <td>{{ $active_merchant }}</td>
                                                    </tr>
                                                    <tr id="2" role="row" class="odd">
                                                        <td>{{ __('inactive_merchant') }}</td>
                                                        <td>{{ $inactive_merchant }}</td>
                                                    </tr>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('parcel') }}</td>
                                                        <td>{{ $parcel }}</td>
                                                    </tr>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('delivery_man') }}</td>
                                                        <td>{{ $rider }}</td>
                                                    </tr>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('branch') }}</td>
                                                        <td>{{ $branch }}</td>
                                                    </tr>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('staff') }}</td>
                                                        <td>{{ $staff }}</td>
                                                    </tr>
                                                    <tr id="1" role="row" class="odd">
                                                        <td>{{ __('delivery_partner') }}</td>
                                                        <td>{{ $delivery_partner }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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

