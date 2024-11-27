<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CommonImage; 
use App\Http\Resources\CommonImage as CommonImageResource;
use App\Http\Resources\CommonImages as CommonImageResourceCollection;

class CommonImageController extends Controller
{
    public function slider()
    {
        $criteria = CommonImage::orderBy('created_at', 'DESC')
        ->get();
        return response()->json([
		'status' => 'success',
            'message' => 'image',
            'data' => $criteria,
        ]); 
    }
}
