<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersControllers extends Controller
{
    public function show(Order $order)
    {
        $delivery=$order->delivery()->select([
            'id',
            'order_id',
            'status',
            DB::raw("ST_Y(current_location) AS lat"),
            DB::raw("ST_X(current_location) AS lng"),
        ])->first();

        return view('front.orders.show',[
            'order'=>$order,
            'delivery'=>$delivery
        ]);
    }
}
