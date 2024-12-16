<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServantSkill extends Model
{
    use HasUuids;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = [
        'user_id',
        'skill',
        'level'
    ];
}
