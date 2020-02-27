<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restrict extends Model
{
    protected $table = 'restricts';

    protected $fillable = [
        'user_id', 'application_id', 'max_possible_hour', 'min_possible_hour', 'max_time_used'
    ];
}
