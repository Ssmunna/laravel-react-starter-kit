<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class BlogSection extends Model
{
    use Uuid;

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'page_name'
    ];
}
