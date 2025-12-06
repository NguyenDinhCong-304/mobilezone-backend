<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contact';
    protected $fillable = [
        'user_id','name','email','phone','content',
        'reply_id','created_by','updated_by','status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

