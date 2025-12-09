<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'brands';   // tên bảng

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Quan hệ Brand có nhiều Product
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
