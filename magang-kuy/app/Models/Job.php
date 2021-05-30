<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'name', 'category', 'company_name', 'company_logo',
        'location', 'about', 'qualifications', 'responsibilities',
    ];

    public function about()
    {
        return $this->hasMany(About::class, 'job_id', 'id');
    }

    public function qualification()
    {
        return $this->hasMany(Qualification::class, 'job_id', 'id');
    }

    public function responsibility()
    {
        return $this->hasMany(Responsibility::class, 'job_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
