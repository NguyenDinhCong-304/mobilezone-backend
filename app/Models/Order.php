<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'user_id','name','email','phone','address','note',
        'created_by','updated_by','status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
    public function getTotalAttribute()
    {
        return $this->details->sum(function ($detail) {
            return $detail->price * $detail->qty;
        });
    }
}