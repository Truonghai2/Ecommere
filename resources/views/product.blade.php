@extends('welcome')

@section('content')
    @php
        $number = 0;

        $htmlCategories = '';
        foreach ($product->categories as $item) {
            $htmlCategories .= '<span class="text-color">' . $item->name . '</span>';
            $htmlCategories .= '<span> > </span>';
        }

        // Optionally, you can trim the trailing separator if needed:
        $htmlCategories = rtrim($htmlCategories, '<span> > </span>');

    @endphp
    <div class="container pt-5 pb-5">
        <div class="path pt-4">
            <span class="text-color">
                Trang chủ
            </span>
            <span>
                >
            </span>
            {!! $htmlCategories !!}

            <span class="text-color">
                {{ $product->title }}
            </span>
        </div>


        <div class="card b-2 border-0 mt-4" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-8">
                        @php
                            $number = $product->variations->sum('quantity');
                            $imagesArray = $product->previewImages->toArray();
                            $chunks = array_chunk($imagesArray, 4);
                            $chunkPoster = array_chunk($imagesArray, 1);

                            $swiperHtml = '';
                            $mainPoster = '';
                            $image_preview = '';

                            foreach ($chunkPoster as $key => $chunk) {
                                $swiperHtml .= view('components.ProductComponent', [
                                    'type' => 'swiper',
                                    'chunk' => $chunk,
                                ]);
                                $mainPoster .= view('components.ProductComponent', [
                                    'type' => 'main-poster',
                                    'chunk' => $chunk,
                                ]);
                            }

                            $price_option = '';
                            $price_ship = "";
                            if (count($addresses) > 0 && count($product->variations) > 0) {
                                $price_ship .= view('components.ProductComponent', [
                                    'type' => 'price-ship',
                                    'product' => $product->variations[0],
                                    'key' => 0,
                                ]);
                            }
                            $quantity = '';
                            foreach ($product->variations as $key => $item) {
                                $price_option .= view('components.ProductComponent', [
                                    'type' => 'price-option',
                                    'item' => $item,
                                ]);

                                $quantity .= view('components.ProductComponent', [
                                    'type' => 'quantity-option',
                                    'item' => $item,
                                ]);
                            }
                        @endphp

                        <div class="image-poster mb-3">

                            <div id="carousel-poster" class="carousel slide mb-3" data-bs-ride="carousel">
                                <div class="carousel-inner d-flex">
                                    <div class="carousel-item active">
                                        <img src="{{ $product->poster }}" href="#modal-image-product" data-bs-toggle="modal"
                                            role="button" class="image-product b-2 w-100" alt="">
                                    </div>
                                    {!! $mainPoster !!}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-poster"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-poster"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <div id="carouselExampleControls" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner d-flex">
                                @foreach ($chunks as $key => $chunk)
                                    @include('components.ProductComponent', [
                                        'type' => 'image-preview',
                                        'key' => $key,
                                        'chunk' => $chunk,
                                    ])
                                @endforeach
                            </div>
                            @if (count($product->previewImages) > 4)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 d-flex flex-column">
                        <h5 class="card-title">{{ $product->title }}</h5>

                        <div class="item d-flex align-items-center gap-3">
                            <div class="rating d-flex align-items-center gap-1 ">
                                <div class="number-rating">
                                    <span
                                        class="total-rating text-danger text-decoration-underline font-size-18">{{ $product->total_rate }}</span>
                                </div>
                                <div class="icon">
                                    <span class="font-size-18 text-secondary mt-1">
                                        <i class='bx bxs-star text-danger'></i>
                                        <i class='bx bxs-star text-danger'></i>
                                        <i class='bx bxs-star text-danger'></i>
                                        <i class='bx bxs-star text-danger'></i>
                                        <i class='bx bxs-star text-danger'></i>
                                    </span>
                                </div>
                            </div>
                            <div class="saled">
                                <span
                                    class="text-secondary text-decoration-underline font-size-18">{{ $product->quantity_saled }}</span>
                                <span class="text-secondary text-decoration-underline font-size-18">Đã bán</span>
                            </div>
                            <div class="favourite-product-like like" data-product-id="{{ $product->id }}"
                                id="favourite-products">
                                <span class="font-size-18" data-favourite-id="{{ $product->id }}">
                                    <i class='bx bx-heart text-danger font-size-20'></i>
                                </span>
                                <span class="text-secondary font-size-18" for="favourite-product">Yêu thích</span>
                            </div>
                            <div class="favourited-product-liked liked" data-product-id="{{ $product->id }}"
                                id="favourite-products">
                                <span data-favourite-id="{{ $product->id }}">
                                    <i class='bx bxs-heart text-danger font-size-20'></i>
                                </span>
                                <span class="text-secondary font-size-18" for="favourited-product">Yêu thích</span>
                            </div>
                        </div>

                        <div class="option-product mt-2">
                            <div class="price p-2" style="background-color: #fbfbfb;">
                                @if ($product->option_type == 1)
                                    <span class="text-secondary text-decoration-line-through font-size-24">
                                        @formatPrice($product->price)
                                    </span>
                                    <span class="text-danger font-size-24 ml-2">
                                        @handlePrice($product->price, $product->sale)
                                    </span>
                                    <span class="badge bg-danger ml-2">
                                        - {{ $product->sale }}%
                                    </span>
                                @else
                                    @php
                                        $minPrice = $product->variations->min('price');
                                        $maxPrice = $product->variations->max('price');
                                        $minSale = $product->variations->min('sale');
                                        $maxSale = $product->variations->max('sale');
                                        $avgSale = ($minSale + $maxSale) / 2;
                                    @endphp

                                    <span class="text-secondary font-size-24 fs-6">
                                        @formatPrice($minPrice)
                                    </span>
                                    <span class="text-secondary font-size-24 fs-6"> ~ </span>
                                    <span class="text-secondary font-size-24 fs-6">
                                        @formatPrice($maxPrice)
                                    </span>
                                    <span class="text-danger font-size-24 ml-2 fs-6">
                                        @handlePrice($minPrice, $minSale)
                                    </span>
                                    <span class="text-danger font-size-24 fs-6"> ~ </span>
                                    <span class="text-danger font-size-24 fs-6">
                                        @handlePrice($maxPrice, $maxSale)
                                    </span>
                                    <span class="badge bg-danger ml-2">
                                        - {{ $avgSale }}%
                                    </span>
                                @endif
                            </div>

                            @if ($product->option_type == 0)
                                @foreach ($product->variations as $item)
                                @endforeach
                            @endif
                        </div>

                        <div class="ship mt-2 d-flex gap-1 align-items-center mb-3">
                            <div class="icon">
                                <i class='bx bxs-truck font-size-24 text-success'></i>
                            </div>
                            <div class="title">
                                <span class="text-secondary hover-underline">Vận chuyển</span>
                            </div>
                            <div class="service">
                                {{-- @getService() --}}
                            </div>
                            <div class="ship">
                                @if (count($addresses) > 0)
                                    @if ($product->option_type == 1)
                                        {!! view('components.ProductComponent', ['type' => 'price-ship', 'product' => $product, 'key' => '0']) !!}
                                    @elseif ($product->option_type == 0)
                                        {!! $price_ship !!}
                                    @endif
                                @else
                                    <span class="address-null hover-underline bg-danger text-bold badge"
                                        data-bs-toggle="modal" href="#exampleModalToggle" role="button">Thêm thông tin
                                        địa
                                        chỉ của bạn.</span>
                                @endif
                            </div>
                        </div>

                        <div class="modal fade w-100" id="exampleModalToggle" aria-hidden="true"
                            aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content b-2">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel"
                                            style=" flex:5;">Thêm địa chỉ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close" id="modal-erase-categories" style=" flex:0.2;"></button>
                                    </div>
                                    <form action="{{ route('user.addAddress') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="group-form">
                                                <label for="home_number" class="font-size-14 text-secondary">Số nhà hoặc
                                                    thôn</label>
                                                <textarea class="w-100 border-color b-2 p-2 outline-0" name="home_number" id="home_number" cols="30"
                                                    rows="2" placeholder="Nhập số nhà hoặc thôn..." required></textarea>
                                            </div>

                                            <div class="group-form">
                                                <label for="home_number"
                                                    class="font-size-14 text-secondary">Tỉnh/thành</label>
                                                <select name="provinces" id="provinces"
                                                    class="w-100 border-color p-2 outline-0 b-2" data-loaded="false"
                                                    required>
                                                    <option value="">Chọn tỉnh/thành</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="group-form">
                                                <label for="district"
                                                    class="font-size-14 text-secondary">Quận/huyện</label>
                                                <select name="district" id="district"
                                                    class="w-100 border-color p-2 outline-0 b-2" required>
                                                    <option value="">Chọn quận/huyện</option>
                                                </select>
                                            </div>
                                            <div class="group-form">
                                                <label for="ward"
                                                    class="font-size-14 text-secondary">Xã/phường</label>
                                                <select name="ward" id="ward"
                                                    class="w-100 border-color p-2 outline-0 b-2" required>
                                                    <option value="">Chọn xã/phường</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-1">
                                            <span class="btn btn-secondary b-2" id="modal-erase-categories"
                                                data-bs-dismiss="modal" aria-label="Close">Hủy</span>
                                            <button class="btn btn-primary b-2" type="submit"
                                                id="modal-save-notification">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="product  d-none">
                            <img src="{{ $product->poster }}" width="100px" alt="Product Image" class="product-image">
                        </div>


                        @if ($product->option_type == 1)
                            <div class="input-quantity d-flex align-items-center gap-3 mb-3">
                                <div class="btn-inp-quantity d-flex">
                                    <div class="icon-erase">
                                        <i class='bx bx-minus border-1 font-size-24 p-1 b-50'></i>
                                    </div>
                                    <div class="input">
                                        <input type="text" value="1"
                                            class="outline-0 quantity-product border-0 p-1 text-center"
                                            id="quantity-product" style="width:50px">
                                    </div>
                                    <div class="icon-plus">
                                        <i class="bx bx-plus font-size-24 border-1 p-1 b-50"></i>
                                    </div>
                                </div>

                                <div class="total-product">
                                    <span class="text-secondary"> <span
                                            class="text-secondary">{{ $product->option_type == 1 ? $product->quantity : $number }}</span>
                                        sản phẩm có sẵn</span>
                                </div>
                            </div>
                        @else
                            <div class="layout-select-option position-fixed bg-white w-100 p-3 d-none"
                                style="bottom: 0;
                                    left: 0;
                                    right: 0; z-index:1002;
                                    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
                                    border-top-left-radius: 10px;
                                    border-top-right-radius: 10px;
                                ">
                                <div class="header-layout d-flex">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="poster-product">
                                            <img src="{{ $product->poster }}" href="#modal-image-product"
                                                data-bs-toggle="modal" role="button" width="180px"
                                                class="image-product b-2 poster-proudct" alt="">
                                            @foreach ($product->variations as $item)
                                                <img src="{{ $item->poster }}" width="180px"
                                                    class="image-product b-2 d-none poster-proudct"
                                                    data-variation-id="{{ $item->id }}">
                                            @endforeach
                                        </div>
                                        <div class="information-product">

                                            <div class="price-product mt-2" id="layout-price-product">
                                                <span class="text-secondary font-size-24 fs-6">
                                                    @formatPrice($minPrice)
                                                </span>
                                                <span class="text-secondary font-size-24 fs-6"> ~ </span>
                                                <span class="text-secondary font-size-24 fs-6">
                                                    @formatPrice($maxPrice)
                                                </span>
                                                <span class="text-danger font-size-24 ml-2 fs-6">
                                                    @handlePrice($minPrice, $minSale)
                                                </span>
                                                <span class="text-danger font-size-24 fs-6"> ~ </span>
                                                <span class="text-danger font-size-24 fs-6">
                                                    @handlePrice($maxPrice, $maxSale)
                                                </span>
                                                <span class="badge bg-danger ml-2">
                                                    - {{ $avgSale }}%
                                                </span>
                                            </div>
                                            {!! $price_option !!}
                                            {!! $quantity !!}


                                            <div class="quantity-product-layout mt-2">
                                                <span class="text-secondary">Kho: </span>
                                                <span class="text-secondary">{{ $number }}</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="btn-close" id="btn-close-add-option"></div>
                                </div>

                                <div class="main-layout pt-2 pb-2 mt-3"
                                    style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;">
                                    @if ($product->option_type == 0)
                                        @foreach ($attribute as $attributeName => $attributes)
                                            <div class="attribute d-flex flex-column">
                                                <div class="attribute-name">
                                                    <span
                                                        class="text-secondary hover-underline">{{ $attributeName }}</span>
                                                </div>
                                                <div
                                                    class="attribute-values d-flex justify-content-center flex-wrap gap-2">
                                                    @foreach ($attributes as $attribute)
                                                        <input type="radio" name="option-product-radio"
                                                            id="{{ $attributeName }}-{{ $attribute['variation']->id }}"
                                                            value="{{ $attribute['variation']->id }}">
                                                        <label
                                                            for="{{ $attributeName }}-{{ $attribute['variation']->id }}"
                                                            {{ $attribute['variation']->quantity > 0 ? '' : 'disable' }}>
                                                            <div
                                                                class="value d-flex text-center align-content-center border-label gap-2 mb-2 p-1 b-2">
                                                                <div class="variation">
                                                                    <img class="image-product b-2"
                                                                        src="{{ $attribute['variation']->poster }}"
                                                                        width="40px" alt="Variation Image"
                                                                        class="variation-image">
                                                                </div>
                                                                <div class="option-name">
                                                                    <span>{{ $attribute['attribute_value'] }}</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="quantity-layout d-flex justify-content-between align-items-center p-2">
                                    <div class="title">
                                        <span class="text-secondary">Số Lượng</span>
                                    </div>
                                    <div class="add-quantity">
                                        <div class="input-quantity d-flex align-items-center gap-3 ">
                                            <div class="btn-inp-quantity d-flex">
                                                <div class="icon-erase">
                                                    <i class='bx bx-minus border-1 font-size-24 p-1 b-50'></i>
                                                </div>
                                                <div class="input">
                                                    <input type="text" value="1"
                                                        class="outline-0 quantity-product border-0 p-1 text-center"
                                                        id="quantity-product" max="{{ $product->quantity }}" style="width:50px">
                                                </div>
                                                <div class="icon-plus">
                                                    <i class="bx bx-plus font-size-24 border-1 p-1 b-50"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-layout w-100 pt-2">
                                    <button class="btn btn-danger b-2 w-100 p-2" id="btn-addto-card"
                                        data-product-id="{{ $product->id }}">
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        @endif



                        <div class="btn-shoping d-flex gap-1 p-1" style="">
                            <div class="btn-chat-product w-50">
                                <a href="{{ url('/chatify/1') }}" class="text-decoration-none">
                                    <div class="p-1 w-100 text-center">
                                        <i class='bx bxl-messenger text-danger font-size-24'></i>
                                        <p class="font-size-12 text-danger mb-0 ">
                                            Trò chuyện
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="btn-add-cart w-50">
                                @if (count($product->variations) > 0)
                                    <div class=" w-100 p-1 text-center" id="btn-add-cart"
                                        data-product-id="{{ $product->id }}">
                                        <i class='bx bx-cart-download text-danger font-size-24'></i>
                                        <p class=" font-size-12 text-danger mb-0">Thêm giỏ hàng</p>
                                    </div>
                                @else
                                    <div class=" w-100 p-1 text-center" id="btn-add-cart-no-option"
                                        data-product-id="{{ $product->id }}">
                                        <i class='bx bx-cart-download text-danger font-size-24'></i>
                                        <p class=" font-size-12 text-danger mb-0">Thêm giỏ hàng</p>
                                    </div>
                                @endif
                            </div>
                            <div class="btn-buy-now w-100">
                                <button class="btn btn-danger w-100 h-100">
                                    Mua ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card b-2 border-0 mt-4" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header bg-white border-0 b-2">
                <h6 class="text-bold text-color">
                    Top thịnh hành
                </h6>
            </div>
            <div class="card-body p-1">
                <div class="swiper-container" id="hotContainer">
                    <div class="swiper-wrapper" id="HotTrend">
                    </div>
                </div>
                <div class="swiper-pagination d-none" id="hotPagination"></div>
            </div>
        </div>


        <div class="card b-2 border-0 mt-4" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header bg-white border-0 d-flex gap-2 b-2">
                <h6 class="text-bold  nav-item-product active p-2">
                    Thông số kỹ thuật
                </h6>
                <h6 class="text-bold text-secondary p-2 nav-item-product">
                    Thông tin sản phẩm
                </h6>
            </div>
            <div class="card-body">
                <div class="specifications main-item-product active">
                    <div class="item">
                        <span class="font-size-14">
                            Danh mục:
                        </span>
                        <span class="text-color">
                            Trang chủ
                        </span>
                        <span>
                            >
                        </span>
                        {!! $htmlCategories !!}
                        <span class="text-color">
                            {{ $product->title }}
                        </span>
                    </div>
                    <div class="item">
                        <span class="font-size-14">
                            Nhãn hiệu:
                        </span>
                        <span class="font-size-14">
                            {{ $product->brand }}
                        </span>
                    </div>
                    <div class="item">
                        <span class="font-size-14">
                            Xuất xứ:
                        </span>
                        <span class="font-size-14">
                            Việt Nam
                        </span>
                    </div>
                    <div class="item">
                        <span class="font-size-14">
                            Kích thước:
                        </span>
                        <span class="font-size-14">
                            {{ $product->width }}cm x {{ $product->height }}cm x {{ $product->length }}cm
                        </span>
                    </div>

                    <div class="item">
                        <span class="font-size-14">
                            Trọng lượng:
                        </span>
                        <span class="font-size-14">
                            {{ $product->weight }}gram => {{ $product->weight / 1000 }}kg
                        </span>
                    </div>
                    <div class="item">
                        <span class="font-size-14">
                            Kho hàng:
                        </span>
                        <span class="font-size-14">
                            {{ $product->option_type == 1 ? $product->quantity : $number }} sản phẩm
                        </span>
                    </div>

                    <div class="item">
                        <span class="font-size-14">
                            Bảo hành:
                        </span>
                        <span class="font-size-14">
                            {{ $product->guarantee ?? 12 }} Tháng
                        </span>
                    </div>

                    <div class="item">
                        <span class="font-size-14">
                            Chất liệu:
                        </span>
                        <span class="font-size-14">
                            Ilock cao cấp
                        </span>
                    </div>

                </div>
                <div class="product-information main-item-product">
                    <span class="font-size-14">{{ $product->description }}</span>
                </div>
            </div>
        </div>
        <div class="card b-2 border-0 mt-4" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header bg-white border-0 b-2">
                <h6 class="text-bold text-color">
                    Đề xuất sản phẩm
                </h6>
            </div>
            <div class="card-body">
                <div class="row" id="recommendation-product"></div>
            </div>
        </div>

    </div>


    <div class="modal fade w-100" id="modal-image-product" aria-hidden="true" aria-labelledby="modal-image-productLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2 bg-color-none border-0">
                <div class="swiper-container" id="modal-image-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide swiper-slide-active">
                            <img src="{{ $product->poster }}" alt="">
                        </div>
                        {!! $swiperHtml !!}
                    </div>
                </div>
                <div class="swiper-pagination" id="modal-image-pagination"></div>
            </div>
        </div>
    </div>


    <div class="modal fade w-100" id="modal-menu-address" aria-hidden="true" aria-labelledby="modaladdressLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <div class="modal-title text-center text-bold" style="flex: 5;">
                        Địa chỉ nhận hàng
                    </div>
                    <div class="btn-close" data-bs-dismiss="modal" aria-label="Close"></div>
                </div>
                <div class="modal-body">
                    <div class="add-item-address"></div>

                    <div
                        class="btn-add-address bg-danger-subtle p-2 b-2 gap-2 d-flex align-items-center justify-content-center">
                        <div class="icon mt-1">
                            <i class="bx bx-plus text-danger font-size-24"></i>
                        </div>
                        <div class="title text-danger" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal" data-bs-toggle="modal">
                            Thêm địa chỉ nhận hàng
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="error-message" class="alert alert-danger bg-danger badge text-white b-3"
        style="display:none; position: fixed; top: 50%; left:50%; z-index: 1003; transform: translate(-50%, -50%);"></div>

    <script>
        $('.nav-item-product').on('click', function() {
            $('.nav-item-product').removeClass('active');
            $(this).addClass('active');

            $('.main-item-product').removeClass('active');
            const index = $('.nav-item-product').index(this);
            $('.main-item-product').eq(index).addClass('active');
        });


        document.addEventListener('DOMContentLoaded', function() {
            function handleResize() {
                var btnShoping = document.querySelector('.btn-shoping');
                if (window.innerWidth <= 768) {
                    btnShoping.classList.add('btn-shoping-fixed');
                    eventClick();
                    addCard();
                } else {
                    btnShoping.classList.remove('btn-shoping-fixed');
                }
            }

            // Gọi hàm handleResize khi trang được load và khi cửa sổ được resize
            handleResize();
            window.addEventListener('resize', handleResize);
        });

        function eventClick() {
            $('input[name="option-product-radio"]').change(function() {
                var id = $(this).val();
                console.log(id);
                $('#layout-price-product').addClass('d-none');
                $('.poster-proudct').addClass('d-none');
                $('.quantity-product-layout').addClass('d-none');
                $(`.quantity-product-layout[data-variation-id="${id}"]`).removeClass('d-none');
                $(`.poster-proudct[data-variation-id="${id}"]`).removeClass('d-none');
                $('.quantity-product').val("1");
            })


            $('#btn-close-add-option').click(function() {
                $('.layout-select-option').addClass('d-none');
                $('.layout-select-option').removeClass('d-none-option');
                $('.layout-select-option').addClass('hidden-option');
            })
        }



        function addCard() {
            $('#btn-add-cart').click(function() {
                $('.layout-select-option').removeClass('d-none');
                $('.layout-select-option').removeClass('hidden-option');
                $('.layout-select-option').addClass('d-none-option');
            })

            $('#btn-add-cart-no-option').click(function() {
                var product_id = $(this).data('product-id');

                var quantity = $('.quantity-product').val();


                if (quantity == 0) {
                    showError('Vui lòng chọn số lượng.');
                    return;
                }

                $.ajax({
                    url: '{{ route('user.addcart') }}',
                    method: "POST",
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        animationAddCart()
                    }
                })
            })
            $('#btn-addto-card').click(function() {
                var product_id = $(this).data('product-id');
                var option_id = $('input[name="option-product-radio"]:checked').val();
                var quantity = $('.quantity-product').val();
                console.log(product_id, option_id, quantity);
                if (option_id == undefined) {
                    showError('Vui lòng chọn một tùy chọn.');
                    return;
                }

                if (quantity == 0) {
                    showError('Vui lòng chọn số lượng.');
                    return;
                }

                $.ajax({
                    url: '{{ route('user.addcart') }}',
                    method: "POST",
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        option_id: option_id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(res) {
                        animationAddCart();
                    }
                })
            })
        }


        function animationAddCart() {
            let productImage = $('.product-image');
            let flyImage = productImage.clone().addClass('fly-to-cart');

            $('body').append(flyImage);

            flyImage.css({
                'top': productImage.offset().top,
                'left': productImage.offset().left,
                'width': productImage.width(),
                'height': productImage.height()
            });

            flyImage.animate({
                'top': $('.cart-icon').offset().top,
                'left': $('.cart-icon').offset().left,
                'width': $('.cart-icon').width(),
                'height': $('.cart-icon').height(),
                'opacity': 0
            }, 1000, function() {
                flyImage.remove();
            });
        }


        function showError(message) {
            var errorMessage = $('#error-message');
            errorMessage.text(message);
            errorMessage.show();

            setTimeout(function() {
                errorMessage.hide();
            }, 3000); // Ẩn thông báo sau 3 giây
        }

        $('.icon-plus').click(function() {
            $('.quantity-product').val(Number($('.quantity-product').val()) + 1);
        })

        $('.icon-erase').click(function() {
            if (Number($('.quantity-product').val()) > 0) {
                $('.quantity-product').val(Number($('.quantity-product').val()) - 1);
            }
        })

        $('#provinces').on('change', function() {
            var data = $(this).val();

            var parts = data.split('|');
            var id = parts[0];
            var name = parts[1];


            if (id != null) {
                $.ajax({
                    url: '{{ route('user.getDistrict') }}',
                    method: "GET",
                    data: {
                        provinces_id: id,
                    },
                    success: function(res) {
                        var html = ''

                        console.log(Array.isArray(res.data));
                        console.log(res.data);
                        res.data.forEach(element => {
                            html += renderOptionDistrict(element);
                        });

                        $('#district').append(html);
                    }
                })
            }
        })

        function renderOptionDistrict(data) {
            return `<option value="${data.DistrictID}|${data.DistrictName}">${data.DistrictName}</option>`;
        }


        $('#district').click(function() {
            var data = $(this).val();

            var parts = data.split('|');
            var id = parts[0];
            var name = parts[1];

            if (id != null) {
                $.ajax({
                    url: '{{ route('user.getWard') }}',
                    method: "GET",
                    data: {
                        district_id: id,
                    },
                    success: function(res) {
                        console.log(res);
                        var html = '';
                        res.data.forEach(element => {
                            html += renderOptionWard(element);
                        });

                        $('#ward').append(html);
                    }
                })
            }
        })

        function renderOptionWard(data) {
            return `<option value="${data.WardCode}|${data.WardName}">${data.WardName}</option>`;
        }



        const container = $('.carousel-item');
        $('.carousel-item').click(function() {
            var image = container.find('img').attr('src');

            console.log(image);
        })


        swiper = new Swiper('#modal-image-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            pagination: {
                el: '#modal-image-pagination',
                clickable: true,
            },
            touchEventsTarget: 'container',
            simulateTouch: true,
            loop: true,
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            threshold: 10,
            followFinger: true,
            allowTouchMove: true,
        });
    </script>

    <!-- Link Swiper's JS -->

    <script>
        var swiper;
        var page = 1;
        var noMoreSwiper = false;
        var totalPages = 0;
        // Initialize Swiper
        swiper = new Swiper('#hotContainer', {
            slidesPerView: 1,
            spaceBetween: 10,
            pagination: {
                el: '#hotPagination',
                clickable: true,
            },
            touchEventsTarget: 'container',
            simulateTouch: true,
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            threshold: 10,
            followFinger: true,
            allowTouchMove: true,
        });

        // Fetch items on slide change
        swiper.on('slideChange', function() {
            page = swiper.realIndex + 1; // Update current page based on swiper's real index
            fetchItems(page, 2);
        });

        // Function to fetch items from backend
        function fetchItems(page, limit, newPage = false) {
            if (newPage) {
                page = 1;
                noMoreSwiper = false;
            }
            if (!noMoreSwiper) {
                $.ajax({
                    url: '{{ route('user.getHostTrend') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        page: page,
                        limit: limit,
                    },
                    success: function(response) {
                        var items = response.items;
                        totalPages = response.last_page;
                        if (newPage) {
                            createSwiperSlides(totalPages);
                        }

                        addItemsToSlides(items);

                        noMoreSwiper = (page >= response.last_page);
                        if (!noMoreSwiper) {
                            page++;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
        }

        // Function to create initial slides based on total pages
        function createSwiperSlides(totalPages) {
            var swiperWrapper = $('#HotTrend');
            swiperWrapper.empty(); // Clear existing slides

            for (var i = 1; i <= totalPages; i++) {
                var slide = $('<div>').addClass('swiper-slide d-flex gap-1').attr('data-page', i);
                swiperWrapper.append(slide);
            }

            swiper.update(); // Update Swiper after adding slides
        }

        function addItemsToSlides(items) {
            var swiperSlides = $('.swiper-slide');
            console.log(page);
            var slide = swiperSlides.eq(page - 1);
            $.each(items, function(index, item) {
                slide.append(item);
            });
        }

        // Initial fetch on page load
        fetchItems(page, 2, true); // Fetch first page

        // Handle pagination click event
        $(document).on('click', '.swiper-pagination .swiper-pagination-bullet', function() {
            fetchItems(page, 2);
        });

        // Function to chunk array into smaller arrays
        function chunkArray(arr, size) {
            var chunks = [];
            for (var i = 0; i < arr.length; i += size) {
                chunks.push(arr.slice(i, i + size));
            }
            return chunks;
        }



        var pageRecomd = 1;
        let noMoreRecomd = false;
        getRecommendation(true);

        function getRecommendation(newPage = false) {
            if (newPage) {
                pageRecomd = 1;
                noMoreRecomd = false;
            }
            if (!noMoreRecomd) {
                $.ajax({
                    url: '{{ route('user.recommendationProduct', ['id' => $product->id]) }}',
                    method: "GET",
                    data: {
                        page: pageRecomd,
                    },
                    success: function(res) {

                        if (pageRecomd == 1) {
                            $('#recommendation-product').html(res.product);
                        } else {
                            $('#recommendation-product').append(res.product);
                        }

                        noMoreRecomd = pageRecomd >= res?.last_page;
                        if (!noMoreRecomd) {
                            pageRecomd++;
                        }
                    }
                })
            }
        }

        actionOnScrollBottom(window, function() {
            getRecommendation();
        });

        $('input[name="option-product-radio"]').on('change', function() {
            var data = $(this).val();
            console.log(data);
            $('.price').addClass('d-none');
            $('.price-variations').addClass('d-none');
            $('.handle-address-user').addClass('d-none');
            $(`.price-variations[data-variation-id="${data}"]`).removeClass('d-none');
            $(`.handle-address-user[data-variation-id="${data}"]`).removeClass('d-none');
        })

        var check = $('input[name="option-product-radio"]:checked').val();
        console.log(check);


        $('#favourite-product').click(function() {
            var id = $(this).data('product-id');
        })

        $("#favourite-products").click(function() {
            var id = $(this).data('product-id');


            $.ajax({
                url: '{{ route('user.favourite.product') }}',
                method: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    if (res.status == "like") {
                        $(".favourite-product-like").addClass("d-none");
                        $(".favourited-product-liked").removeClass("d-none");
                    } else {
                        $(".favourited-product-liked").addClass("d-none");
                        $(".favourite-product-like").removeClass("d-none");
                    }
                }
            })
        })


        $.ajax({
            url: '{{ route('user.getFavouriteProduct') }}',
            method: 'GET',
            data: {
                type: "one",
                id: {{ $product->id }},
            },
            success: function(res) {
                if (res.status == "like") {
                    $(".favourite-product-like").addClass("d-none");
                    $(".favourited-product-liked").removeClass("d-none");
                } else {
                    $(".favourited-product-liked").addClass("d-none");
                    $(".favourite-product-like").removeClass("d-none");
                }
            }
        })



        var provinces = @json($provines);

        $('#provinces').on('click', function() {
            if (!$(this).data('loaded')) {
                var options = provinces.map(function(item) {
                    return '<option value="' + item.ProvinceID + '|' + item.ProvinceName + '">' + item
                        .ProvinceName + '</option>';
                });
                $('#provinces').append(options.join('')).data('loaded', true);
            }
        });


        $('.change-address-user').click(function(){
            $.ajax({
                url: '{{ route('user.getAddress') }}',
                method: "GET",
                success:function(res){
                    var html = '';
                    res.address.forEach(element => {
                        html += htmlItemAddress(element);
                    });

                    $('.add-item-address').html(html);
                }
            })
        })

        function htmlItemAddress(object) {
            return `
                <div class="item-address mb-2 d-flex align-items-center gap-2 b-3 p-2 border-label hover-color">
                    <div class="inpt-radio">
                        <input type="radio" name="address-user" id="address-user-${object.id}" ${object.active != 0 ? "checked" : ""}>
                    </div>
                    <div class="title">
                        <label for="address-user-${object.id}">
                             <span class="hover-underline text-wrap">${object.home_number},
                                            ${object.ward_name}, ${object.district_name},
                                            ${object.provinces_name}
                                        </span>
                        </label>
                    </div>
                </div>
            `;

        }
    </script>

@endsection
