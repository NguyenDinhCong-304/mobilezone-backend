<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banner';
    protected $fillable = [
        'name', 'image', 'link', 'position', 'sort_order',
        'description', 'created_by', 'updated_by', 'status'
    ];
}
