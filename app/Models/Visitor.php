<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use Uuid;
    protected $fillable = [
        'ip',
    ];
}
