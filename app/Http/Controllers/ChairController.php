<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Chair;
use App\Http\Resources\Chairs as ChairResourceCollection;
use DB;
use Auth;
use Carbon\Carbon;
use App\Order;
use Illuminate\Database\Query\Builder;

class ChairController extends Controller
{
    public function index()
    {
        $criteria = Chair::orderBy('updated_at', 'desc')->paginate(10); 
        return new ChairResourceCollection($criteria);
    }
    public function indexCustomer()
    {
        $criteria = Chair::whereNotIn('chairs.id',function (Builder $query) {
            $query->selectRaw('orders.chair_id')->from('orders')->where('orders.status','=','SUBMIT');})
        ->orderBy('chairs.updated_at', 'DESC')->paginate(10); 
        return new ChairResourceCollection($criteria);
    }

    public function search($keyword)
    {
        $criteria = Chair::select('*')
        ->where('name', 'LIKE', "%".$keyword."%")
        ->orderBy('updated_at', 'desc')
        ->get(); 
        return new ChairResourceCollection($criteria);
    }

    public function store(Request $request)
    {
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        $user = Auth::user();
        DB::beginTransaction();
        try {

            $new_chair = new Chair;
            $new_chair->name = $request->get('name');
            $new_chair->no = $request->get('no');
            $new_chair->created_at = Carbon::now();
            $new_chair->updated_at = Carbon::now();
            $new_chair->save();
            DB::commit();
            $status = 'success';
            $message = 'Store chairs saved and published successfully';
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

    public function update(Request $request)
    {
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        $user = Auth::user();
        DB::beginTransaction();
        try {

            $new_chair = Chair::findOrFail($request->id);
            $new_chair->name = $request->get('name');
            $new_chair->no = $request->get('no');
            $new_chair->updated_at = Carbon::now();
            $new_chair->save();
            DB::commit();
            $status = 'success';
            $message = 'Update chairs saved and published successfully';
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

    public function deletePermanent(Request $request){
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        DB::beginTransaction();
        try {
        $chairs = Chair::findOrFail($request->id);
        
        $chairs->forceDelete();
        
        $status = 'success';
        $message = 'Chair permanently deleted';
      
        
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
}
