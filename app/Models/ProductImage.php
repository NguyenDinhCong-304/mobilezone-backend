<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes;

    protected $table = 'product_image';
    protected $fillable = ['product_id','image','alt','title'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}

