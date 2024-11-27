<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product; 
use App\Category; 
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Products as ProductResourceCollection;
use Illuminate\Support\Facades\Gate;
use DB;
use File;
use Auth;
use Validator;
use App\User;
use Storage;
use Image;
use Carbon\Carbon;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMakanan()
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
        ->where('products.status','PUBLISH')
        ->where('category_product.category_id', 1)
        ->orderBy('products.updated_at', 'desc')->paginate(10); 
        return new ProductResourceCollection($criteria);
    }
    public function indexMinuman()
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
        ->where('products.status','PUBLISH')
        ->where('category_product.category_id',2)
        ->orderBy('products.updated_at', 'desc')->paginate(10); 
        return new ProductResourceCollection($criteria);
    }
    public function indexSnack()
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
        ->where('products.status','PUBLISH')
        ->where('category_product.category_id',3)
        ->orderBy('products.updated_at', 'desc')->paginate(10); 
        return new ProductResourceCollection($criteria);
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMakanan($keyword)
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
            ->where('products.status','PUBLISH')
            ->where('category_product.category_id',1)
            ->where('products.title', 'LIKE', "%".$keyword."%")
            ->orderBy('products.updated_at', 'DESC')
            ->get();        
        return new ProductResourceCollection($criteria);
    }
    public function searchMinuman($keyword)
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
            ->where('products.status','PUBLISH')
            ->where('category_product.category_id',2)
            ->where('products.title', 'LIKE', "%".$keyword."%")
            ->orderBy('products.updated_at', 'DESC')
            ->get();        
        return new ProductResourceCollection($criteria);
    }
    public function searchSnack($keyword)
    {
        $criteria = Product::JOIN('category_product', 'products.id', '=', 'category_product.product_id')
            ->where('products.status','PUBLISH')
            ->where('category_product.category_id',3)
            ->where('products.title', 'LIKE', "%".$keyword."%")
            ->orderBy('products.updated_at', 'DESC')
            ->get();          
        return new ProductResourceCollection($criteria);
    }


   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        $user = Auth::user();

        DB::beginTransaction();
        try {
          
            $new_Product = new \App\Product;
            $listOfProduct = Product::orderBy('id', 'desc')->limit(1)->get();
            
            if(count($listOfProduct)>0){
                $idDesc = 0;
            }else{
                $idDesc = 1;
            }
            foreach($listOfProduct as $product){
                    $idDesc = $product->id +1;
            }
                
            $new_Product->code=$this->generatedCode($idDesc);
            $new_Product->title = $request->get('name');
            $new_Product->description = $request->get('description');
            $new_Product->buying_price = $request->get('buying_price');
            $new_Product->price = (int) round($request->get('price'), 0);
            $new_Product->stock = $request->get('stock');
            
             
        
            if($new_Product->stock==0){
                $new_Product->status = "DRAFT";
            }else{
                $new_Product->status = "PUBLISH";
            }

            $new_Product->slug = str_slug($request->get('name'));

            $new_Product->created_by = \Auth::user()->id;
            $new_Product->user_id = \Auth::user()->id;
            if($request->file('cover')){
                $cover_path = $request->file('cover')->store('product-covers', 'public');

                $file = $request->file("cover");
                $storageDestinationPath=storage_path('app/public/' . $cover_path);
                if (!\File::exists( $storageDestinationPath)) {
                    \File::makeDirectory($storageDestinationPath, 0755, true);
                }
                $img = Image::make($file->path());
                $img->resize(500, 500, function ($const) {
                    $const->aspectRatio();
                })->save($storageDestinationPath);

                File::move(storage_path('app/public/' . $cover_path), public_path( 'storage/' . $cover_path));
            
                $new_Product->cover = $cover_path;
            }
            $new_Product->updated_at =Carbon::now();
            $new_Product->save();

            $new_Product->categories()->attach($request->get('category'));    

        
            DB::commit();
            $status = 'success';
            $message = 'Product successfully saved and published';
              

        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
            $code = 404;
        }
        
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);

    }
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        DB::beginTransaction();
        try {
        $product = \App\Product::findOrFail($request->id);
        $product->code=$request->get('code') ;
        $product->title = $request->get('name');
        $product->description = $request->get('description');
        $product->stock = $request->get('stock');
        $product->buying_price = $request->get('buying_price');
        $product->price = (int) round($request->get('price'), 0);
        
        $new_cover = $request->file('cover');
        $product->slug = str_slug($request->get('name'));

        if($new_cover != null){
            File::delete(public_path( 'storage/' . $product->cover));
            if($product->cover && file_exists(public_path( 'storage/' . $product->cover))){
                File::delete(public_path( 'storage/' . $product->cover));
            }
            
            $new_cover_path = $new_cover->store('product-covers', 'public');

            $file = $request->file("cover");
            $storageDestinationPath=storage_path('app/public/' . $new_cover_path);
            if (!\File::exists( $storageDestinationPath)) {
                \File::makeDirectory($storageDestinationPath, 0755, true);
            }
            $img = Image::make($file->path());
            $img->resize(500, 500, function ($const) {
                $const->aspectRatio();
            })->save($storageDestinationPath);        
            
            File::move(storage_path('app/public/' . $new_cover_path), public_path( 'storage/' . $new_cover_path));
   
            $product->cover = $new_cover_path;
            
        }

        if($product->stock==0){
            $product->status = "DRAFT";
        }else{
            $product->status = "PUBLISH";
        }
        $product->save();

        
        $product->categories()->sync($request->get('category'));

        DB::commit();
        $status = 'success';
        $message = 'Updated product successfully saved and published';
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @param  array  $carts
     * @return \Illuminate\Http\Response
     */
    public function cart(Request $request)
    {
        $carts = json_decode($request->carts, true);
        $product_carts = [];
        foreach($carts as $cart){
            $id = (int)$cart['id'];
            $quantity = (int)$cart['quantity'];
            $Product = Product::find($id);

            if($Product){
                $note = 'unsafe';
                if($Product->stock >= $quantity){
                    $note = 'safe';
                }
                else {
                    $quantity = (int) $Product->stock;
                    $note = 'out of stock'; 
                }
                $product_carts[] = [
                    'id' => $id,
                    'title' => $Product->title,
                    'cover' => $Product->cover,
                    'price' => $Product->price,
                    'quantity' => $quantity,
                    'note' => $note
                ];
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'carts',
            'data' => $product_carts,
        ], 200); 

    }

    public function deletePermanent(Request $request){
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        DB::beginTransaction();
        try {
        $product = \App\Product::withTrashed()->findOrFail($request->id);
        

        File::delete(public_path( 'storage/' . $product->cover));
        $product->categories()->detach();
        $product->orders()->detach();
        $product->forceDelete();
        
        $status = 'success';
        $message = 'Product permanently deleted';
      
        
        DB::commit();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function generatedCode($id){
        $id_string = (string) $id;
    
        if(strlen($id_string)==1){
            $id_string ='0000'.''.$id_string;
        }elseif(strlen($id_string)==2){
            $id_string ='000'.''.$id_string;
        }elseif(strlen($id_string)==3){
            $id_string ='00'.''.$id_string;
        }elseif(strlen($id_string)==4){
            $id_string ='0'.''.$id_string;
        }
        return $id_string;
    } 
}
