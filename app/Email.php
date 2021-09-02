<?php

namespace EmailService;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['to', 'from', 'response', 'response_code'];
}
