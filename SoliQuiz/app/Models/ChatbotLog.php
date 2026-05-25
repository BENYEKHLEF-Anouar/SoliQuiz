<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    protected $fillable = [
        'session_id',
        'speaker',
        'message',
        'detected_language',
        'ip_address',
        'user_agent',
        'response_time_ms',
    ];
}
