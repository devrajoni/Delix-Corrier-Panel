<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSCredit extends Model
{
    use HasFactory;

    protected $table    = 'sms_credits';
    protected $fillable = ['quantity', 'type', 'sms_package_id' ];
}
