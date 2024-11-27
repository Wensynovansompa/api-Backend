<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chair extends Model
{
    
    protected $fillable = [
        'name', 'no', 'created_at', 'updated_at', 'status'
    ]; 
}
