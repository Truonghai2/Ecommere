@if ($type == 'swiper')
    <div class="swiper-slide">
        @foreach ($chunk as $image)
            <img src="{{ $image['image'] }}" alt="...">
        @endforeach
    </div>
@endif

@if ($type == 'main-poster')
    <div class="carousel-item">
        @foreach ($chunk as $image)
            <img src="{{ $image['image'] }}" href="#modal-image-product" data-bs-toggle="modal" role="button"
                class="image-product b-2 w-100" alt="...">
        @endforeach
    </div>
@endif

@if ($type == 'image-preview')
    <div class="carousel-item @if ($key == 0) active @endif d-flex gap-1" style="width:100px;">
        @foreach ($chunk as $image)
            <img src="{{ $image['image'] }}" class="d-block w-100 image-product b-2" alt="...">
        @endforeach
    </div>
@endif



@if ($type == 'price-option')
    <div class="price-variations p-2 d-none" style="background-color: #fbfbfb;" data-variation-id="{{ $item->id }}">
        <span class="text-secondary text-decoration-line-through font-size-24">
            @formatPrice($item->price)
        </span>
        <span class="text-danger font-size-24 ml-2">
            @handlePrice($item->price, $item->sale)
        </span>
        <span class="badge bg-danger ml-2">
            - {{ $item->sale }}%
        </span>
    </div>
@endif

@if ($type == "quantity-option")
<div class="quantity-product-layout mt-2 d-none" data-variation-id="{{ $item->id }}">
    <span class="text-secondary">Kho: </span>
    <span class="text-secondary">{{ $item->quantity }}</span>
</div>
@endif

@if ($type == 'price-ship')

<div class="handle-address-user {{ $key > 0 ? 'd-none' : '' }}" data-variation-id="{{ $product->id }}">
    @if($addresses->isNotEmpty())
        <span class="hover-underline text-wrap change-address-user" data-bs-toggle="modal" href="#modal-menu-address" role="button">
            Đ/c: {{ $addresses->last()->home_number }},
            {{ $addresses->last()->ward_name }},
            {{ $addresses->last()->district_name }},
            {{ $addresses->last()->provinces_name }}
        </span>
    @endif

    <div class="price-ship">
        <span class="text-secondary text-decoration-line-through">
            @if ($product->weight >= 30000)
                @HandlePriceShipListProduct(
                    $addresses->last()->district_id,
                    $addresses->last()->ward_id,
                    [['name' => $product->title, 'quantity' => 1, 'width' => $product->width, 'height' => $product->height, 'length' => $product->length, 'weight' => $product->weight]],
                    $product->price - ($product->price * $product->sale) / 100,
                    $product->weight
                )
            @else
                @HandlePriceShip(
                    $addresses->last()->district_id,
                    $addresses->last()->ward_id,
                    [['name' => $product->title, 'quantity' => 1, 'width' => $product->width, 'height' => $product->height, 'length' => $product->length, 'weight' => $product->weight]],
                    $product->price - ($product->price * $product->sale) / 100
                )
            @endif
        </span>
        <span class="text-danger hover-underline ml-1">0đ</span>
        <span class="badge bg-success ml-1">Miễn phí</span>
    </div>
</div>
@endif
