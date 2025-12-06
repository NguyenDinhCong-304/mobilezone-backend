<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menu';
    protected $fillable = [
        'name','link','type','parent_id','sort_order','table_id',
        'created_by','updated_by','status'
    ];

    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
