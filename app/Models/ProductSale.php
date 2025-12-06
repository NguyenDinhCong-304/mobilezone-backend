<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSale extends Model
{
    use SoftDeletes;

    protected $table = 'product_sale';
    protected $fillable = [
        'name','product_id','price_sale','date_begin','date_end',
        'created_by','updated_by','status'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}

