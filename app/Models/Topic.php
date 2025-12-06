<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    protected $table = 'topic';
    protected $fillable = [
        'name','slug','sort_order','description',
        'created_by','updated_by','status'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'topic_id', 'id');
    }

}
