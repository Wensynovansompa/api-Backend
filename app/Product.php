<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'title', 'slug', 'description', 'author', 'publisher',
        'cover', 'price', 'weight', 'stock', 'status'
    ]; 

    public function categories()
    {
        return $this->belongsToMany('App\Category'); //, 'product_category', 'product_id', 'category_id');
    }

    public function orders(){
        return $this->belongsToMany('App\Order');
    }
}
