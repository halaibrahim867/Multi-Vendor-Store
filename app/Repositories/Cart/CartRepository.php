<?php

namespace App\Repositories\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartRepository
{
    public function get(): Collection ;

    public function add(Product $product,$quatity=1);

    public function update(Product $product,$quatity);

    public function delete($id);

    public function empty();

    public function total(): float;

}

