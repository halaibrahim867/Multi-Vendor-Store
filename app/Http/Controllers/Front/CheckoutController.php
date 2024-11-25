<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if ($cart->get()->count() == 0){
            return redirect()->route('home');
        }
        return view('front.checkout',[
            'cart'=>$cart,
            'countries'=>Countries::getNames(),
        ]);
    }

    public function store(Request $request,CartRepository $cart)
    {
        $request->validate([

        ]);

        $items = $cart->get()->groupBy('product.store_id')->all();



        DB::beginTransaction();

        try {
            foreach ($items as $store_id => $cart_items) {

                $order = Order::create([
                    'store_id'=>$store_id,
                    'user_id'=>Auth::id(),
                    'payment_method'=>'cod' //cache on delivery
                ]);

                $groupedItems = collect($cart_items)->groupBy('product_id');

                foreach ($groupedItems as $product_id => $group) {
                    $quantity = $group->sum('quantity');
                    $product = $group->first()->product;

                    // Create a single order item for each product
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product_id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $quantity,
                    ]);
                }


                foreach ($request->post('addr') as $type => $address){
                    $address['type'] = $type;

                    $order->addresses()->create($address);
                }

            }
            $cart->empty();
            DB::commit();

        }catch (\Throwable $e){

            DB::rollBack();
            throw $e;
        }
        return redirect()->route('home');

    }
}
