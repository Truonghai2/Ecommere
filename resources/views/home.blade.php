@extends('welcome')

@section('main-home')
<main>
    <div class="thumbnail" id="htmlContainer"></div>
    <div class="container" style="padding-top:100px;">
        @include('layout.ListCategories')
    </div>
    @foreach ($category_product as $item)
    <div class="categories-product mt-5" style="background-color: #fbfbfb;">
        @if ($item->Thumbnail->isNotEmpty())
            <div class="container p-3">
                @if (count($item->Thumbnail) == 3)
                    <div class="decription-categories mt-4 row  justify-content-between" >
                        @foreach ($item->Thumbnail as $thumbnail)
                            {!! view('layout.ItemTable', ['type' => $thumbnail->type, 'thumbnail' => $thumbnail]) !!}
                        @endforeach
                    </div>
                @else
                    @foreach ($item->Thumbnail as $thumbnail)
                        {!! view('layout.ItemTable', ['type' => $thumbnail->type, 'thumbnail' => $thumbnail]) !!}
                    @endforeach
                @endif
                
            </div>
        @endif
        
    </div>
    <div class="container pt-4">
        <div class="product ">
            <div class="title">
                <h3 class="text-center text-bold">
                    {{ $item->name }}
                </h3>
            </div>

            <div class="list-item pt-3">
                <div class="row justify-content-center">
                    @foreach ($item->takeProduct as $key => $product)

                    @if ($key < 8)
                    <div class="col-lg-3 col-6 mb-3 p-1" >
                        <a href="{{ route('user.detailproduct', ['id' => $product->id]) }}" class="text-decoration-none">
                            <div class="card b-2">
                                <img
                                    class="card-img-top image-product"
                                    src="{{ $product->poster }}"
                                    alt="Card image cap"
                                />
                                <div class="card-body d-flex flex-column justify-content-between p-sp-1">
                                    <h4 class="card-title font-size-16 clamp-2-lines cart-title-responsive">{{ $product->title }}</h4>
                                    <div class="label d-flex align-items-center gap-2 mb-1">
                                        
                                        <div class="sale badge bg-danger"> 
                                            <span class="badge text-white">- {{ ($product->option_type == 0) ? $product->variations[0]->sale ?? 0 : $product->sale ?? 0 }}%</span>
                                        </div>
                                        <div class="">
                                            <span class="badge text-success border-1 border-success">15 ngày đổi trả</span>
                                        </div>
                                        
                                    </div> 
    
                                    <div class="price-product d-flex justify-content-between">
                                        {{-- <span class="card-link text-secondary text-decoration-line-through">
                                            @if ($product->option_type == 0)
                                                @formatPrice($product->variations[0]->price ?? 0) 
                                            @else
                                                @formatPrice($product->price ?? 0)
                                            @endif
                                        </span> --}}
                                        <span class="card-link text-danger">
                                            @if ($product->option_type == 0)
                                                @handlePrice($product->variations[0]->price ?? 0, $product->variations[0]->sale ?? 0) 
                                            @else
                                                @handlePrice($product->price ?? 0, $product->sale ?? 0)
                                            @endif
                                        </span>
                                        <div class="saled">
                                            <span class="text-secondary font-size-14">{{ $product->quantity_saled }} đã bán</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    @endforeach
                    
                </div>
            </div>
        </div>
        {{-- <div class="w-100 text-center border-color p-2" style="border-top: 1px  solid #fff;">
            <a href="{{ route('user.category', ['slug' => $item->slug]) }}" class="text-decoration-none">
                <span class="text-color font-size-18 text-bold">Xem thêm</span>
            </a>
        </div> --}}
    </div>

    @endforeach
    



    <div class="container pt-5 ">

        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-5 mb-3" >
                <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); aspect-ratio: 1 / 1;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon font-size-24">
                            <i class='bx bx-revision' style="font-size:50px;"></i>
                        </div>
                        <h4 class="card-title">Hoàn trả sau 30 ngày</h4>
                        <p class="card-text">Chúng tôi sẵn sàng hoàn trả nếu thiết bị có sự cố, nếu sự cố đó do nhà máy</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-5 mb-3">
                <div class="card border-0 b-2 " style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); aspect-ratio: 1 / 1;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon font-size-24">
                            <i class='bx bxs-package' style="font-size:50px;"></i>
                        </div>
                        <h4 class="card-title">FreeShip</h4>
                        <p class="card-text">Miễn phí vận chuyển</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-5 mb-3" >
                <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); aspect-ratio: 1 / 1;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon font-size-24">
                            <i class='bx bxs-offer' style="font-size:50px;" ></i>
                        </div>
                        <h4 class="card-title text-center">Giá cả tốt nhất thị trường</h4>
                        <p class="card-text">Chúng tôi có những chính sách ưu đãi rất tốt cho người dùng</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-5 mb-3" >
                <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); aspect-ratio: 1 / 1;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon font-size-24">
                            <i class='bx bxs-credit-card' style="font-size:50px;"  ></i>
                        </div>
                        <h4 class="card-title">Chuyển khoản an toàn</h4>
                        <p class="card-text">Chúng tôi sử dụng hệ thống chuyển khoản của các ngân hàng Việt Nam đảm bảo tính bảo mật và an toàn, Không lo mất an toàn khi chuyển khoản.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    
</main>

<script>
    var htmlArray = @json($html); 
    var currentIndex = 0;

    function updateContent() {
        var container = $('#htmlContainer');
        container.html(htmlArray[currentIndex]);
        currentIndex = (currentIndex + 1) % htmlArray.length; 
    }

    setInterval(updateContent, 10000);

    updateContent();
</script>



@endsection
