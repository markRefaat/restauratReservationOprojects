<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $order = Order::with('details')->where('id', $request->order_id)->first();
        if($order)
        {
            $order->paid = true;
            $order->save();
            return response()->json(['data' => $order], 200);
        }
        return response()->json(['data' => false], 200);
    }
}
