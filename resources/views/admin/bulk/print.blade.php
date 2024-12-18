<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="description"
          content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <link rel="shortcut icon" href="{{ static_asset('admin/')}}/images/favicon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ static_asset('admin/')}}/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
          href="{{ static_asset('admin/')}}/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ static_asset('admin/')}}/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ static_asset('admin/')}}/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ static_asset('admin/')}}/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ static_asset('admin/')}}/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('admin/')}}/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ static_asset('admin/css/app.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ static_asset('admin/css/responsive.min.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/custom.css">

    <title class="text-end">{{__('parcel').' '.__('details').' '.__('print')}} | {{setting('system_name')}}</title>
    <style>
        body {
            background-color: #ffffff !important;
            font-family: 'jost', sans-serif !important;
        }

        tr {
            vertical-align: middle;
        }
        tbody td {
            color: #7e7f92 !important;
        }

        .card.assign-delivery .page-title {
            font-weight: 400;
            font-size: 18px;
            line-height: 26px;
            color: #000000;
            margin-bottom: 0;
        }
        .assign-delivery .page-header {
            margin-top: 0 !important;
        }
        .assign-delivery .table td {
            line-height: 18px;
        }
        .signature-section .border {
            width: 100%;
            margin-bottom: 5px;
            display: block;
        }

        .cod,
        .signature {
            line-height: 20px;
        }

    </style>
</head>

<body class="nk-body npc-default has-sidebar mt-3">
    <div class="nk-app-root">
        <div class="nk-main print">
            <div class="nk-content">
                <div class="nk-content-inner">
                    <div>
                        <div class="row g-gs">
                            <div class="col-xxl-12 col-sm-12 col-md-12">
                                <div class="card assign-delivery m-0">
                                    <div class="justify-content-between d-inline-flex page-header p-3 pb-0">
                                        <div class="right-content d-inline-flex gap-2 align-items-center">
                                            <img src="{{ getFileLink('80X80',setting('admin_mini_logo')) }}" alt="logo"
                                                class="img-fluid mr-3 image w-auto">
                                            <div class="parcel-details d-block">
                                                <h3 class="page-title">{{__('courier')}} hj</h3>
                                                {{ setting('address') }}
                                                {{ setting('phone') }}
                                            </div>
                                        </div>
                                        <p class="page-title">{{ __('date').' '.date('d.m.Y') }}</p>
                                    </div>

                                    <div class="mt-3">
                                        <div class="d-inline-flex print-table-parcels">
                                            <table class="table table-striped">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th scope="col" class="pl-0">#</th>
                                                        <th scope="col" class="pl-0">{{ __('merchant') }}</th>
                                                        <th scope="col" class="pl-0">{{ __('barcode') }}</th>
                                                        <th scope="col" class="pl-0">{{ __('id').'/'.__('c_phone') }}</th>
                                                        <th scope="col" class="pl-0">{{ __('customer_name') }}</th>
                                                        <th scope="col" class="pl-0">{{ __('COD') }}</th>
                                                        <th scope="col" class="pl-0">{{ __('customer_address') }}</th>
                                                        <th scope="col" class="pl-0">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($parcels as $key => $parcel)
                                                    <tr>
                                                        <td scope="col" width="3%">{{ $key+1 }}</td>
                                                        <td scope="col"
                                                            width="12%">{{ (@$parcel->merchant_id == 1802 && $parcel->user->user_type == 'merchant_staff' ) ? \Illuminate\Support\Str::limit($parcel->user->first_name.' '.$parcel->user->last_name, 15, $end='..') : \Illuminate\Support\Str::limit($parcel->merchant->company, 15, $end='..') }}
                                                            <br> {{ $parcel->pickup_shop_phone_number }}</td>
                                                        <td scope="col" width="14%"><img class="image img-fluid"
                                                                                        src="data:image/svg;base64,{{ DNS1D::getBarcodePNG($parcel->parcel_no , 'C93',1,33) }}"
                                                                                      alt="barcode"/></td>
                                                        <td scope="col" width="10%">{{ $parcel->parcel_no }}
                                                            <br> {{ $parcel->customer_phone_number }}</td>
                                                        <td scope="col" width="15%">{{ $parcel->customer_name }}</td>
                                                        <td scope="col" width="6%">{{ $parcel->price }}</td>
                                                        <td scope="col" width="15%">{{ $parcel->customer_address }}</td>
                                                        <td scope="col" width="5%"><input type="checkbox"><br/></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="font-weight-bold mt-2 p-2">
                                            {{ __('total_cod') }}
                                            : {{ format_price(array_sum(array_column($parcels, 'price'))) }}
                                        </div>
                                    </div>
                                </div>
                                @if(isset($receive) && $receive == 'pickup')
                                    <div class="card m-0 mt-3 p-3 pt-4 pb-2" >
                                        <div class="signature-section justify-content-between d-flex">
                                            <div class="signature d-block">
                                                <div class="border"></div>
                                                <p>{{ __('merchant') }}</p>
                                                {{ $merchant }}
                                            </div>
                                            <div class="cod d-block text-right">
                                                <div class="border "></div>
                                                {{ __('pickup_man') }}<br>
                                                {{ $delivery_man }}
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($receive))
                                    <div class="card m-0 mt-3 p-3 pt-4 pb-2" >
                                        <div class="signature-section justify-content-between d-flex">
                                            <div class="signature d-block">
                                                <div class="border"></div>
                                                <p>{{ __('delivered_by') }}</p>
                                                {{ $delivery_man }}
                                            </div>
                                            <div class="cod d-block text-right">
                                                <div class="border "></div>
                                                {{ __('received_by') }}<br>
                                                {{ \Sentinel::getUser()->first_name.' '.\Sentinel::getUser()->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card m-0 mt-3 p-3 pt-4 pb-2" >
                                        <div class="signature-section justify-content-between d-flex">
                                            <div class="signature d-block">
                                                <div class="border"></div>
                                                <p>{{ __('process_by') }}</p>
                                                {{ \Sentinel::getUser()->first_name.' '.\Sentinel::getUser()->last_name }}
                                            </div>
                                            <div class="cod d-block text-right">
                                                <div class="border "></div>
                                                {{ __('received_by') }}<br>
                                                {{ $delivery_man }}<br>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>
<script src="{{ static_asset('admin/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>
</body>
</html>
