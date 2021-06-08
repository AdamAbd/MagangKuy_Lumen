<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    //
    protected $hidden = [
        'id',
        'job_id',
        'created_at',
        'updated_at',
    ];
}
