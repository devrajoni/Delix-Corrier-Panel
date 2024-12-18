
@if(hasPermission('merchant_update') || hasPermission('merchant_delete') || hasPermission('download_closing_report'))
    <div class="action-card">
        <ul class="d-flex justify-content-center">
            @if(hasPermission('merchant_update'))
                <li>
                    <a href="{{route('merchant.edit', $merchant->id)}}" class="dropdown-item py-2"
                        href="javascript:void(0);">
                        <i class="las la-edit"></i>
                    </a>
                </li>
            @endif
            @if(hasPermission('merchant_delete'))
                <li>
                    <a href="javascript:void(0);"
                        onclick="delete_row('merchant/delete/', {{$merchant->id}})" id="delete-btn"
                        class="dropdown-item py-2">
                        <i class="las la-trash-alt"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif
