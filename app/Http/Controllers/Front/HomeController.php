<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->active()
            ->limit(8)
            ->get();
        return view('front.home',compact('products'));
    }
}
