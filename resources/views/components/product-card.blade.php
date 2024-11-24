<div class="single-product">
    <div class="product-image">
        <img src="{{ $product->image_url }}" alt="#">
        @if($product->sale_percent)
            <span class="sale-tag">-{{$product->sale_percent}}%</span>
        @endif
        <div class="button">
            <a href="{{route('products.show',$product->slug)}}" class="btn"><i class="lni lni-cart add-to-cart"></i> Add to Cart</a>
        </div>
    </div>
    <div class="product-info">
        <span class="category">{{ $product->category->name }}</span>
        <h4 class="title">
            <a href="{{route('products.show',$product->slug)}}">{{ $product->name }}</a>
        </h4>
        <ul class="review">
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star-filled"></i></li>
            <li><i class="lni lni-star"></i></li>
            <li><span>4.0 Review(s)</span></li>
        </ul>
        <div class="price">
            <span>{{ Currency::format($product->price) }}</span>
            @if($product->compare_price)
                  <span class="discount-price">{{ Currency::format($product->compare_price) }}</span>
            @endif
        </div>
    </div>
</div>
