<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStore extends Model
{
    use SoftDeletes;

    protected $table = 'product_store';
    protected $fillable = [
        'product_id','price_root','qty',
        'created_by','updated_by','status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
