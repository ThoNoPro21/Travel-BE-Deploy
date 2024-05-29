<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //Lấy đơn hàng cá nhân
    public function showOrderByUser(Request $request)
    {
        $order = Order::where('user_id', $request->user()->users_id)->with(['orderDetails.product', 'user',])->get();
        if ($order->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Không có đơn hàng nào !'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Thành công!', 'data' => $order], 200);
        }
    }
    //Tạo đơn hàng
    public function add(Request $request)
    {
        $order = new Order();
        $order->address = $request['address'];
        $order->phone_number = $request['phoneNumber'];
        $order->total_amount = $request['totalAmount'];
        $order->note = $request['note'];
        $order->status = $request->has('status') ? $request['status'] : 0;
        $order->user_id = $request->user()->users_id;
        $order->save();
        $orderID = $order->orders_id;

        if (!empty($request['listProducts'])) {
            foreach ($request['listProducts'] as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->quantity = $product['quantity'];
                $orderDetail->total_amount = $product['total_amount'];
                $orderDetail->order_id = $orderID;
                $orderDetail->product_id = $product['id'];
                $orderDetail->save();
                Cart::where('carts_id', $product['carts_id'])->delete();
            }
        }
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Đặt hàng thành công!']);
    }
}