<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $table = 'usages';

    protected $fillable = [
        'user_id', 'application_id', 'date', 'time',
    ];
}
