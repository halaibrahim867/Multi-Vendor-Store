<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeductProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order= $event->order;
        foreach ($order->products as $product){ //using relationship many to many
            $product->decrement('quantity',$product->order_item->quantity);
        }

         /*foreach (Cart::get() as $item){
             Product::where('id','=' ,$item->product_id)
                    ->update([
                        'quantity'=>DB::raw("quantity - {$item->quantity}")
                    ]);
         }*/
    }
}
