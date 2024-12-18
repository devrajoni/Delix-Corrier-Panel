
@php

    $timestamp  = $query->created_at;
    $date       = \Carbon\Carbon::parse($timestamp)->format('Y-m-d');
    $time       = \Carbon\Carbon::parse($timestamp)->format('H:i');
    $date       = \Carbon\Carbon::parse($date);
@endphp
<td class="text-center"><span>{{ $date->format('h:i A') }}</span><br><span>{{ $date->format('d F Y') }}</span></td>


