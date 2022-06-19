<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Meal;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $reservation = Reservation::where('id', $request->reservation_id)->first();        
        $order = Order::create([
            'table_id' => $reservation->table_id,
            'reservation_id' => $reservation->id,
            'customer_id' => $reservation->customer_id,
            'waiter_id' => $request->waiter_id,
            'total' => 0,
            'paid' => false,
            'date' => Carbon::now()
        ]);
        $total = 0;
        foreach($request->meals_id as $meal_id)
        {
            $meal = Meal::where('id', $meal_id)->first();
            OrderDetail::create([
                'order_id' =>$order->id,
                'meal_id' => $meal_id,
                'amount_to_pay' => $meal->price - $meal->discount
            ]);
            $total += $meal->price - $meal->discount;
        }
        $order->total = $total;
        $order->save();
        return response()->json(['data' => $order], 200);
    }
}
