<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'product';
    protected $fillable = [
        'category_id', 'brand_id', 'name','slug','thumbnail','content',
        'description','price_buy','created_by','updated_by','status'
    ];
    protected $appends = ['price_sale'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    //lấy 1 khuyến mãi hợp lệ tại thời điểm hiện tại
    public function sale()
    {
        return $this->hasOne(ProductSale::class, 'product_id', 'id')
                    ->where('date_begin', '<=', now())
                    ->where('date_end', '>', now());
    }

    //lấy tất cả khuyến mãi của sản phẩm
    public function sales()
    {
        return $this->hasMany(ProductSale::class, 'product_id', 'id');
    }

    // 1 sản phẩm có nhiều lần nhập kho
    public function stores()
    {
        return $this->hasMany(ProductStore::class, 'product_id', 'id');
    }

    // Nếu muốn lấy tổng tồn kho 1 sản phẩm
    public function store()
    {
        return $this->hasOne(ProductStore::class, 'product_id', 'id')
                    ->selectRaw('product_id, SUM(qty) as total_qty')
                    ->groupBy('product_id');
    }

    // 🔹 Quan hệ Attribute (nhiều-nhiều qua product_attribute)
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id', 'attribute_id')
                    ->withPivot('value') // lấy thêm cột value trong bảng trung gian
                    ->withTimestamps();
    }

    public function getPriceSaleAttribute()
    {
        return $this->sale?->price_sale;
    }

}

