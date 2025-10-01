<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use Uuid;
    protected $fillable = [
        'title',
        'description',
        'image',
        'page_name'
    ];
}
