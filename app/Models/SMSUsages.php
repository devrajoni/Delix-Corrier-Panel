<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSUsages extends Model
{
    use HasFactory;

    protected $table = 'sms_usages';

    protected $fillable = ['total', 'usages'];
}
