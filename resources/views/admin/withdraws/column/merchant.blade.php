<input type="hidden" value="{{$payment->id}}" id="id">
<div>
    <a href="{{route('detail.merchant.personal.info', $payment->merchant->id)}}">
        <div class="user-info-panel d-flex gap-12 align-items-center">
            <div class="user-img">
                <img src="{{ getFileLink('80X80', $payment->merchant->user->image_id) }}">
            </div>
            <div class="user-info">
                <div class="tb-lead text-black">{{$payment->merchant->user->first_name.' '.$payment->merchant->user->last_name .'('.$payment->merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span></div>
                <div>{{$payment->merchant->user->email}}</div>
            </div>
        </div>
    </a>
</div>

