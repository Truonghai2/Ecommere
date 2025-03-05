@if ($type == "item-notification")
@php
    $url = "";
    if ($item->product_id) {
        $url = route('user.detailproduct',['id' => $item->product_id]);
    }
    
    if($item->order_code){
        $url = route('product.get.detailOrderProduct',['order_code' => $item->order_code]);
    }
    
@endphp
<a href="{{ $url != null ? $url : "#" }}" class="text-decoration-none notification-item" data-notification-id="{{ $item->id }}">

    <div class="item d-flex justify-content-between align-items-center hover-color p-1 b-2 mb-2">
        <div class="first d-flex gap-2 align-items-center">
            <div class="poster">
                <img src="{{ $item->poster != null ? $item->poster : secure_asset('img/logo.png') }}" width="50px" class="image-product b-2" alt="">
            </div>
            <div class="content">
                <div class="content mb-1">{{ $item->content }}</div>
                <div class="time font-size-14 {{ !$item->seen ? "text-color" : "" }}">@datetime($item->created_at)</div>
            </div>
        </div>
    
        <div class="second" id="status-notification">
            @if (!$item->seen)
                <i class='bx bxs-circle font-size-12 text-color'></i>                 
            @endif
        </div>
    </div>
</a>
@endif