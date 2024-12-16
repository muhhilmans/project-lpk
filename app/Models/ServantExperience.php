<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServantExperience extends Model
{
    use HasUuids;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'user_id',
        'position',
        'type',
        'company',
        'start',
        'end',
        'description'
    ];
}
