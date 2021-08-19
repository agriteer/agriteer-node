<?php

namespace EmailService;

use Illuminate\Database\Eloquent\Model;

class ErrorLogging extends Model
{
    protected $fillable = ['to', 'from', 'response', 'response_code'];
}
