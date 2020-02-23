<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restrict extends Model
{
    protected $table = 'restricts';

    protected $fillable = [
        'user_id', 'application_id', 'max-possible-hour', 'min-possible-hour', 'max-time-used'
    ];
}
