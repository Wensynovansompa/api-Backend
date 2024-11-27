<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; 

    protected $fillable = [
        'name', 'slug', 'image', 'status'
    ];
    
    /*public function Products()
    {
        return $this->belongsToMany('App\Product', 'product_category', 'category_id', 'product_id');
    }*/

    public function Products(){
        return $this->belongsToMany("App\Product");
    }
    
}
