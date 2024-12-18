<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSHistory extends Model
{
    use HasFactory;
    protected $table = 'sms_histories';
    protected $fillable = [
        'to',
        'message_id',
        'message',
        'count',
        'status',
    ];

}
