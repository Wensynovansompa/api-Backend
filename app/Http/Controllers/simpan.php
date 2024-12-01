<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\User;
use App\Product;
use App\Order;
use App\OrderProduct;
use App\Http\Resources\Orders as OrderResourceCollection;
use App\Http\Resources\OrderProducts as OrderProductResourceCollection;
use App\Http\Resources\Products as ProductResourceCollection;
use Carbon\Carbon;
use Image;
use File;
use Storage;

class ShopController extends Controller
{

    public function index()
    {

        $criteria = \App\Order::select('orders.*, users.name')
			->JOIN('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status','=','SUBMIT')
            ->orderBy('orders.id', 'desc')
            ->paginate(10);
        return new OrderResourceCollection($criteria);
    }

    public function indexHistory()
    {

        $criteria = \App\Order::select('orders.*, users.name')
			->JOIN('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status','FINISH')->orderBy('orders.id', 'desc')
            ->paginate(10);
        return new OrderResourceCollection($criteria);
    }


    public function searchMyOrder($keyword)
    {
        $user = Auth::user();
        $criteria = \App\Order::select('orders.*, users.name')
			->JOIN('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.invoice_number', 'LIKE', "%" . $keyword . "%")
            ->where('orders.user_id', '=', $user->id)
            ->orderBy('orders.id', 'DESC')
            ->get();

        return new OrderResourceCollection($criteria);
    }

    public function search($keyword)
    {
        $criteria = \App\Order::select('orders.*, users.name')
			->JOIN('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'SUBMIT')    
            ->where('users.name', 'LIKE', "%" . $keyword . "%")
            ->orWhere('orders.invoice_number', 'LIKE', "%" . $keyword . "%")
            
            ->orderBy('orders.id', 'DESC')
            ->get();
        return new OrderResourceCollection($criteria);
    }

    public function searchHistory($keyword)
    {
        $criteria = \App\Order::select('orders.*, users.name')
			->JOIN('users', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'FINISH')   
            ->where('users.name', 'LIKE', "%" . $keyword . "%")
            ->orWhere('orders.invoice_number', 'LIKE', "%" . $keyword . "%")
            
            ->orderBy('orders.id', 'DESC')
            ->get();

        return new OrderResourceCollection($criteria);
    }


   
    public function payment(Request $request)
    {
        $error = 0;
        $status = "error";
        $message = "";
        $data = [];
        $code = 200;
        $user = Auth::user();
       
        if ($user) {
            DB::beginTransaction();
            try {

                $carts = json_decode($request->carts, true);

                // create order
                $order = new Order;
                $order->user_id = $user->id;
                $order->total_bill = 0;
                $order->invoice_number = date('YmdHis');
                $order->status = 'SUBMIT';
                
                if ($order->save()) {
                    $total_price = 0;
                    foreach ($carts as $cart) {
                        $id = (int)$cart['id'];
                        $quantity = (int)$cart['quantity'];
                        $Product = Product::find($id);
                        
                        if ($Product) {
                            if ($Product->stock >= $quantity) {
                                // create Product order
                                $product_order = new OrderProduct;

                            
                                $total_price += $Product->price * $quantity;

                                $product_order->product_id = $Product->id;
                                $product_order->order_id = $order->id;
                                $product_order->quantity = $quantity;
                                $product_order->name_product = $Product->title;
                                $product_order->price_product = $Product->price;
                                $product_order->buying_price = $Product->buying_price;
                                $product_order->buying_price_total = $Product->buying_price * $quantity;
                                    
                                
                                if ($product_order->save()) {
                                    // kurangi stock
                                    $Product->stock = $Product->stock - $quantity;
                                    if ($Product->stock == 0) {
                                        $Product->status = 'DRAFT';
                                    }
                                    $Product->save();
                                }
                            } else {
                                $error++;
                                throw new \Exception($Product->title . ' Out of stock');
                            }
                        } else {
                            $error++;
                            throw new \Exception('Product is not found');
                        }
                    }
                    $chair = json_decode($request->chairId, true);
                    $order->chair_id = $chair;

                    $fileImge= $request->image;
                    $evidence_transfer_path = $fileImge->store('orders', 'public');
                    $file = $fileImge;
                    $destinationPath=storage_path('app/public/' . $evidence_transfer_path);
                    if(!File::isDirectory($destinationPath)){
                        \File::makeDirectory($destinationPath,  0777, true, true);
                    }
                    $img = Image::make($file->path());
                    $img->resize(1000, 1000, function ($const) {
                        $const->aspectRatio();
                    })->save($destinationPath);
                    File::move(storage_path('app/public/' . $evidence_transfer_path), public_path( 'storage/' . $evidence_transfer_path));
                    $order->evidence_of_transfer=$evidence_transfer_path;
                    $order->total_bill = $total_price;
                    
                    if ($order->save()) {
                        if ($error == 0) {
                            DB::commit();
                            $status = 'success';
                            $message = 'Transaction success';
                            $data = [
                                'order_id' => $order->id,
                                'total_bill' => $order->total_bill,
                                'invoice_number' => $order->invoice_number,
                            ];
                        } else {
                            $message = 'There are ' . $error . ' errors';
                        }
                    }
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
                DB::rollback();
            }
        } else {
            $message = "User not found";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function download($id){
        $user = Auth::user();
        $status = "success";
        $message = "download successfully";
        $data = null;
        $code = 200;

        $order = \App\Order::findOrFail($id);
        $data = $user->toArray();
        $filePath = $order->evidence_of_transfer;
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'url' =>$filePath,
            'number' =>$order->invoice_number,
        ], $code);
    }

    public function approved(Request $request){
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        DB::beginTransaction();
        try {
            $order = \App\Order::findOrFail($request->id);
            $order->status = 'FINISH';
            $order->save();
            $status = 'success';
            $message = 'Order approved successfully';


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

    public function deletePermanent(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        DB::beginTransaction();
        try {
            $order = \App\Order::findOrFail($request->id);
            $product_order = OrderProduct::where('order_id', $order->id)->get();

            $order->forceDelete();
            foreach ($product_order as $OrderProduct) {
                $OrderProduct->forceDelete();
            }

            $status = 'success';
            $message = 'Order permanently deleted successfully';


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

    public function reportOrder(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        DB::beginTransaction();
        try {

            $order = \App\Order::findOrFail($request->id);
            if ($order->order_type == 2) {
                $order->status = "PROCESS";
                $order->save();
            }


            $product_order = OrderProduct::where('order_id', $order->id)->get();

            $status = 'success';
            $message = 'Order report successfully';

            DB::commit();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
        $data = $user->toArray();
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $order,
            'product_order' => $product_order,
        ], $code);
    }
}