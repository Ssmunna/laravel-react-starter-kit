<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use Uuid;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'message'
    ];
}
