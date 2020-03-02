<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = [
        'user_id', 'application_id', 'open_latitude', 'open_longitude', 'close_latitude', 'close_longitude',
    ];
}
