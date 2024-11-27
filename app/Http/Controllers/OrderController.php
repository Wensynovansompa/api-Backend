<?php
namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Fungsi untuk menghapus order
    public function destroy($orderId)
    {
        // Cari order berdasarkan ID
        $order = Order::find($orderId);

        // Jika order tidak ditemukan, kembalikan response error
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        // Periksa apakah user yang menghapus adalah pemilik order (jika diperlukan)
        if ($order->user_id != Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized to delete this order'
            ], 403);
        }

        // Hapus order
        $order->delete();

        // Kembalikan response sukses
        return response()->json([
            'message' => 'Order deleted successfully'
        ], 200);
    }
}
