<?php

namespace EmailService;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'to',
        'nameTo',
        'from',
        'nameFrom',
        'subject',
        'message',
        'status'
    ];
}
