<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttemptsLog extends Model
{
    protected $table = 'login_attempts_log';
    
    protected $fillable = [
        'username',
        'ip_address',
        'success',
        'reason',
    ];
}
