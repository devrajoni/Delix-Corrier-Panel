<div>
    <div>
        <button type="button" data-text="{{$payment->withdraw_id}}"
        class="copy-to-clipboard btn btn-default text-info mx-0 px-0 border-0" >#{{$payment->withdraw_id}}</button>
    </div>
</div>
<div>
    {{$payment->created_at != ""? date('M d, Y h:i a', strtotime($payment->created_at)):''}}
</div>
<div>
    @if(!blank(@$payment->companyAccount->receipt))
        <a href="{{ getFileLink('80X80', $payment->companyAccount->receipt) }}" target="_blank"> <i class="icon  las la-external-link-alt"></i> {{ __('receipt') }}</a>
    @else
        {{ __('not_available') }}
    @endif
</div>
