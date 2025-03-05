@extends('welcome')

@section('content')
    {!! view('user.layouts.HeaderOrder', ['style' => 'order']) !!}


    <div class="container pt-5 pb-5">
        <div class="path pb-3 text-color" >
            <a href="{{ url('/') }}" class="text-decoration-none">
                <span class=" text-color">Trang chủ /</span>
            </a>
            <a href="{{ route('user.information') }}" class="text-decoration-none">
                <span class=" text-color">Trang cá nhân /</span>
            </a>
            <a href="{{ route('user.viewQueueVerify') }}" class="text-decoration-none">
                <span class=" text-color">Chờ lấy hàng</span>
            </a>
        </div>
        @if (count($products) > 0)
            @foreach ($products as $product)
                <div class="card b-2 shadow border-0 mb-3">
                    <div class="card-header p-2 bg-white">
                        @php
                            $quantity = 0;
                        @endphp
                        @foreach ($product->list_item_orders as $item)

                        @php
                            $quantity += $item->quantity;
                        @endphp
                        <div class="product d-flex gap-2 pb-3 " style="border-bottom: 1px solid #ccc; ">
                            <div class="first">
                                <div class="product-poster">
                                    <img width="100px" src="{{ $item->poster }}" class="image-product b-2" alt="">
                                </div>
                            </div>
                            <div class="second d-flex flex-column gap-2 w-100">
                                <div class="product-title">
                                    {{ $item->name }}
                                </div>
                                @if ($item->option_id != null)
                                    <div class="option ">
                                        <span>Phân loại: </span>
                                        <span>{{ $item->name_option }}</span>
                                    </div>
                                @endif
                                <div class="">
                                    <span class="badge bg-success"><i class='bx bxs-truck text-white'></i> Miễn phí</span>
                                    <span class="badge bg-danger">- {{ $item->sale }}%</span>
                                    <span class="border-1 border-success badge text-success">15 ngày đổi trả</span>
                                </div>
                                <div class="price d-flex justify-content-between">
                                    <div class="first d-flex gap-2 align-items-center">
                                        <div class="price-saled text-bold text-danger">
                                            @handlePrice($item->price,$item->sale)
                                        </div>
                                        <div class="text-secondary text-decoration-line-through">
                                            @formatPrice($item->price)
                                        </div>
                                    </div>
                                    <div class="last">
                                        <div class="quantity">
                                            x{{ $item->quantity }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="status-shipping d-flex justify-content-between hover-color bg-success-subtle item p-1 b-2 mt-1 align-items-center">
                            <div class="first d-flex gap-2">
                                <div class="icon">
                                    <i class='bx bx-package font-size-20 text-success'></i>
                                </div>
                                <a href="{{ route("product.get.detailOrderProduct", ['order_code' => $product->order_code ])}}" class="text-decoration-none">
                                    <div class="title text-success">
                                        Đơn hàng đang được đóng gói
                                    </div>
                                </a>
                            </div>
                            <div class="second">
                                <i class='bx bx-chevron-right font-size-24 text-success' ></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2 d-flex gap-3 align-items-center justify-content-end">
                        <div class="total_price">
                            <div class="title">
                                Thành tiền ({{ count($product->list_item_orders) * $quantity }} sản phẩm):
                            </div>
                            <div class="text-end text-bold text-danger">
                                @formatPrice($product->price_new)
                            </div>
                        </div>
                        <div class="btn btn-secondary bg-color-2 border-0 b-2" id="cancel-order" data-order-code="{{ $product->order_code }}" style="color: black;" data-order-id="{{ $product->id }}">
                            Hủy đơn hàng
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-5">
                <h6 class="text-secondary text-center">Hiện không có đơn hàng nào trong tình trạng chờ.</h6>
            </div>
        @endif


        <div class="recommendation">

            <div class="text-center">
                <h6 class="text-secondary">Có thể bạn sẽ thích</h6>
            </div>


            <div class="row p-1" id="recommendation-product"></div>

            <div class="p-5 d-none" id="loading-product-append">
                <div class="loader-item mx-auto"></div>
            </div>
        </div>

    </div>


    <script>
        let page = 1;
        let lastPage = {{ $last_page }};
        let noMorePick = page >= lastPage;
        var reComPage = 1;
        var noMoreRecomm = false;


        actionOnScrollBottom(window, function() {
            getRecommenDation();
        });
        if (noMorePick) {
            getRecommenDation(true);
        }
        else{
            getItemQueuePayment();
        }

        function getItemQueuePayment(newPage = false){
            if(newPage){
                page = 1;
                noMorePick = 1;
            }
            if(!noMorePick){

            }
        }


        function getRecommenDation(newPage = false) {
            if (newPage) {
                reComPage = 1;
                noMoreRecomm = false;
            }

            if (!noMoreRecomm && noMorePick) {
                $('#loading-product-append').removeClass('d-none');

                $.ajax({
                    url: `{{ route('product.ManyRecomendationProduct') }}`,
                    method: "GET",
                    data: {
                        ids: @json($productIds),
                        page: reComPage,
                    },
                    success: function(res) {
                        if (reComPage == 1) {
                            $('#recommendation-product').html(res.product);
                        } else {
                            $('#recommendation-product').append(res.product);
                        }

                        noMoreRecomm = reComPage >= res?.last_page;
                        if (!noMoreRecomm) {
                            reComPage++;
                        }

                        $('#loading-product-append').addClass('d-none');
                    },
                    error: function(err) {
                        console.error('Error fetching recommendation products:', err);
                        $('#loading-product-append').addClass('d-none');
                    }
                });
            }
        }


        $('#cancel-order').click(function(){
            var order_code = $(this).data('order-code');

            $.ajax({
                url: '{{ route('user.cancelOrderProduct') }}',
                method: "POST",
                data:{
                    order_code: order_code,
                    _token: '{{ csrf_token() }}',
                },
                success:function(res){

                }
            })

        })
    </script>

@endsection
