@if ($type == 'user')
    <tr>
        <th scope="row">{{ $item['id'] }}</th>
        <td>{{ $item['first_name'] }}</td>
        <td>{{ $item['last_name'] }}</td>
        @isset($item['email'])
        <td>{{ $item['email'] }}</td>
            @else
            <td class="text-success badge bg-success-subtle">null</td>
        @endisset
        @isset($item['phone_number'])
            <td>{{ $item['phone_number'] }}</td>
        @else
            <td><span class="text-wrap badge text-danger bg-danger-subtle">null</span></td>
        @endisset
        <td>{{ $item['birth_day'] }}</td>
        @isset($item['address'])
            <td>{{ $item['address'] }}</td>
        @else
            <td><span class="text-wrap badge text-warning bg-warning-subtle">Rỗng</span></td>
        @endisset
        @if ($item['verify_email'] == 1)
            <td><span class="text-wrap badge text-success bg-success-subtle">Đã Xác Nhận</span></td>
        @else
            <td><span class="text-wrap badge text-warning bg-warning-subtle">Chưa Xác Nhận</span></td>
        @endif

        @if ($item['verify_number'] == 1)
            <td><span class="text-wrap badge text-success bg-success-subtle">Đã Xác Nhận</span></td>
        @else
            <td><span class="text-wrap badge text-warning bg-warning-subtle">Chưa Xác Nhận</span></td>
        @endif
        <td><span class="text-wrap text-success">{{ $item['coin'] }} Xu</span></td>
        @if ($item['role'] == 1)
            <td><span class="text-wrap badge text-success bg-success-subtle">Quản Lý</span></td>
        @else
            <td><span class="text-wrap badge     text-warning bg-warning-subtle">Người Dùng</span></td>
        @endif

        @if ($item['active_status'] == 1)
            <td><span class="text-wrap badge text-danger bg-danger-subtle">Đã chặn</span></td>
            @else
            <td><span class="text-wrap badge text-success bg-success-subtle">Còn sống</span></td>
        @endif
        
        <td>@datetime($item['created_at'])</td>
        <td>
            <div class="btn btn-danger mb-1" id="ban-user">
                <i class='bx bx-lock font-size-20 text-white' ></i>
            </div>
            
        </td>

    </tr>


@endif


@if ($type == 'category')
    <tr>
        <th scope="row">
            <span class="badge text-success bg-success-subtle">Mới</span>
        </th>
        <td>
            <img width="100px" class="image-product b-2" src="{{ $item->thumbnail }}" alt="">
        </td>
        <td>
            <span class="">
                {{ $item->name }}
            </span>
        </td>
        <td>
            <span class="">
                {{ $time }}
            </span>
        </td>
        <td>
            <span class="btn btn-primary b-2 mb-1" data-category-id="{{ $item->id }}"><i
                    class='bx bx-edit-alt text-white'></i></span>
            <span class="btn btn-danger b-2 mb-1" data-category-id="{{ $item->id }}"><i
                    class='bx bxs-trash text-white'></i></span>
        </td>
    </tr>
@endif


@if ($type == 'product')
    <div class="item">
        <a href="{{ route('user.detailproduct', ['id' => $item->id]) }}" class="text-decoration-none">
            <div class="item-content hover-color b-1 p-2 d-flex align-items-center">
                <div class="product-poster">
                    <img width="80px" class="image-product b-2" src="{{ $item->poster }}" alt="">
                </div>
                <div class="information ml-2">
                    <div class="product-name">
                        <h6 class="text-bold">
                            {{ $item->title }}
                        </h6>
                    </div>
                    <div class="product-price d-flex align-items-center gap-1">
                        <div class="price text-secondary text-decoration-line-through">@formatPrice($item->price)</div>

                        <div class="price-saled text-danger hover-underline">@handlePrice($item->price, $item->sale)</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endif

@if ($type == 'notification')
    <tr>
        <th scope="row">{{ $index }}</th>
        @if ($item->user != null)
            <td>
                <span class="">{{ $item->user->id }}</span>
            </td>
            <td>
                <span class="">{{ $item->user->first_name }} {{ $item->user->last_name }}</span>
            </td>
        @else
            <td>
                <span class="badge text-success bg-success-subtle">null</span>
            </td>
            <td>
                <span class="badge text-success bg-success-subtle">null</span>
            </td>
        @endif

        @if ($item->product_id == null)
            <td>
                <span class="badge text-success bg-success-subtle">null</span>
            </td>
        @else
            <td>
                <span class="">{{ $item->product_id }}</span>
            </td>
        @endif

        @if ($item->category_id == null)
            <td>
                <span class="badge text-success bg-success-subtle">null</span>
            </td>
        @else
            <td>
                <span class="">{{ $item->category_id }}</span>
            </td>
        @endif

        @if ($item->type == 1)
            <td>
                <span class="badge  text-warning bg-warning-subtle">Chỉ định người dùng</span>
            </td>
        @elseif($item->type == 0)
            <td>
                <span class="badge  text-success bg-success-subtle">Tất cả người dùng</span>
            </td>
        @else
            <td>
                <span class="badge text-danger bg-danger-subtle">Quản lý</span>
            </td>
        @endif

        @if ($item->poster != null)
            <td>
                <img src="{{ $item->poster }}" width="100px" class="image-product b-2" alt="">
            </td>
        @else
            <td>
                <span class="badge  text-success bg-success-subtle">Mặc định</span>
            </td>
        @endif

        <td><span class="text-wrap">{{ $item->content }}</span></td>
        <td><span class="text-wrap">@datetime($item->created_at)</span></td>
        <td>
            <span class="btn btn-danger b-2" id="btn-remove-notification" data-notification-id="{{ $item->id }}"
                data-bs-toggle="modal" data-bs-target="#modal-confirm-{{ $item->id }}" role="button">Xóa</span>
        </td>


    </tr>

@endif


@if ($type == 'item-product')
    <div class="col-lg-3 col-6 mb-2">
        <a href="{{ route('user.detailproduct', ['id' => $item->id]) }}" class="text-decoration-none">
            <div class="card b-2">
                <img class="card-img-top image-product" loading="lazy" src="{{ $item->poster }}"
                    alt="Card image cap" />
                <div class="card-body d-flex flex-column justify-content-between p-sp-1">
                    <h4 class="card-title font-size-16 clamp-2-lines card-title-responsive">{{ $item->title }}</h4>
                    <div class="label d-flex align-items-center gap-2 mb-1 ">
                        <div class="ship badge d-flex align-items-center gap-2 bg-success-subtle">
                            <div class="icon">
                                <i class='bx bxs-truck text-success'></i>
                            </div>
                            <div class="title  text-success">Miễn phí</div>
                        </div>
                        @if ($item->option_type == 0)
                            @php
                                $minVariationSale = $item->variations->min('sale');
                            @endphp

                            <div class="sale badge bg-danger">
                                <span class="badge text-white">- {{ $minVariationSale }}%</span>
                            </div>
                        @endif
                        @if ($item->sale > 0 && $item->sale != null)
                            <div class="sale badge bg-danger">
                                <span class="badge text-white">- {{ $item->sale }}%</span>
                            </div>
                        @endif
                        
                    </div>

                    <div class="price-product d-flex justify-content-between align-items-md-center">
                        @if ($item->option_type == 1)
                            {{-- <span class="card-link text-secondary text-decoration-line-through me-0 me-md-2 mb-2 mb-md-0">
                                @formatPrice($item->price)
                            </span> --}}
                            <span class="card-link text-danger text-bold">
                                @handlePrice($item->price, $item->sale)
                            </span>
                        @elseif ($item->option_type == 0)
                            @php
                                $minVariationPrice = $item->variations->min('price');
                                $minVariationSale = $item->variations->min('sale');
                            @endphp
                            {{-- <span class="card-link text-secondary text-decoration-line-through me-0 me-md-2 mb-2 mb-md-0">
                                @formatPrice($minVariationPrice)
                            </span> --}}
                            <span class="card-link text-danger text-bold">
                                @handlePrice($minVariationPrice, $minVariationSale)
                            </span>
                        @endif

                        <div class="saled">
                            <span class="text-secondary font-size-14">{{ $item->quantity_saled }} đã bán</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endif


@if ($type == 'itemCart')
<div class="cart bg-white b-2 p-2 mb-2 border-label d-flex align-items-center gap-2" data-cart-id="{{ $item['cart_id'] }}">

    <div class="checkbox">
        <input type="checkbox" name="checkbox-product" data-product-id="{{ $item['variation']->product_id }}" data-option-id="{{ $item['variation']->id }}"  id="checkbox-product-{{ $item['variation']->id }}">

        <label for="checkbox-product-{{ $item['variation']->id }}" class="checkbox-label"></label>
    </div>

    <div class="content d-flex w-100 gap-2">
        <div class="poster-product">
            <a href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id ]) }}">
                <img src="{{ $item['variation']->poster }}" width="100px" class="image-product b-2" alt="">
            </a>
        </div>

        <div class="information-product w-100">
            <div class="name-product mb-2">
                <a href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id ]) }}" class="text-decoration-none text-black card-title-responsive">
                    {{ $item['product_title'] }}
                </a>
            </div>
            <div class="option mb-2">
                <div class="bg-color-2 p-1 b-2 option-content" data-variation-id="{{ $item['variation']->id }}" data-product-id="{{ $item['variation']->product_id }}">
                    <span class="custom-truncate card-title-responsive">{{ $item['attribute_value'] }}</span>
                    <span><i class='bx bxs-chevron-down'></i></span>
                </div>
            </div>
            <div class="tabs mb-2">
                <span class="bg-danger badge p-1">15 ngày đổi trả</span>
            </div>
            <div class="price-quantity w-100 d-flex align-items-center justify-content-between">
                <div class="price-card">
                    <span class="text-danger text-bold">@handlePrice($item['variation']->price, $item['variation']->sale)</span>
                    <span class="text-secondary text-decoration-line-through ml-1">@formatPrice($item['variation']->price)</span>
                </div>
                <div class="quantity">
                    <div class="add-quantity">
                        <div class="input-quantity d-flex align-items-center gap-3 ">
                            <div class="btn-inp-quantity d-flex" >
                                <div class="icon-erase" data-cart-id="{{ $item['cart_id'] }}">
                                    <i class='bx bx-minus border-1 font-size-20 p-1 b-50'></i>
                                </div>
                                <div class="input">
                                    <input type="text" value="{{ $item['quantity'] }}" data-option-id="{{ $item['variation']->id }}" data-product-id="{{ $item['variation']->product_id }}" data-cart-id="{{ $item['cart_id'] }}"
                                        class="outline-0 quantity-product border-0 p-1 text-center" id="quantity-product"
                                        style="width:50px">
                                </div>
                                <div class="icon-plus" data-cart-id="{{ $item['cart_id'] }}">
                                    <i class="bx bx-plus font-size-20 border-1 p-1 b-50" ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if ($type == 'layout-select-option')

    <div class="layout-select-option container position-fixed bg-white w-100 p-3 d-none-option"
        style="bottom: 0;
                            left: 0;
                            right: 0; z-index:1002;
                            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
                            border-top-left-radius: 10px;
                            border-top-right-radius: 10px;
                            " data-option-id="{{ $option_id }}">
        <div class="header-layout d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="poster-product">

                    @foreach ($product->variations as $item)

                        <img src="{{ $item->poster }}" width="180px"
                            class="image-product b-2 d-none poster-proudct" data-variation-id="{{ $item->id }}">
                    @endforeach
                </div>
                <div class="information-product">
                    <div class="product-name">
                        <h6 class="card-title-responsive">{{ $product->title }}</h6>
                    </div>
                    <div class="price-product mt-2" id="layout-price-product">

                    </div>
                    @foreach ($product->variations as $item)
                        <div class="price-variations p-2 d-none" style="background-color: #fbfbfb;"
                            data-variation-id="{{ $item->id }}">
                            <span
                                class="text-secondary text-decoration-line-through font-size-18">@formatPrice($item->price)</span>
                            <span class="text-danger font-size-18 ml-1">@handlePrice($item->price, $item->sale)</span>
                            <span class="badge bg-danger ml-2">- {{ $item->sale }}%</span>
                        </div>

                        <div class="quantity-product-layout mt-2 d-none" data-variation-id="{{ $item->id }}">
                            <span class="text-secondary">Kho: </span>
                            <span class="text-secondary">{{ $item->quantity }}</span>
                        </div>
                    @endforeach

                    <div class="quantity-product-layout mt-2">
                        <span class="text-secondary">Kho: </span>
                        <span class="text-secondary"></span>
                    </div>

                </div>

            </div>
            <div class="btn-close" data-option-id="{{ $option_id }}" id="btn-close-add-option"></div>
        </div>

        <div class="main-layout pt-2 pb-2 mt-3"
            style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;">
            @if ($product->option_type == 0)
                @foreach ($attribute as $attributeName => $attributes)
                    <div class="attribute d-flex flex-column">
                        <div class="attribute-name">
                            <span class="text-secondary hover-underline">{{ $attributeName }}</span>
                        </div>
                        <div class="attribute-values d-flex justify-content-center flex-wrap gap-2">
                            @foreach ($attributes as $attribute)
                                <input type="radio" name="option-product-radio"
                                    id="{{ $attributeName }}-{{ $attribute['variation']->id }}"
                                    value="{{ $attribute['variation']->id }}" data-option-id="{{ $option_id }}" {{ ($option_id == $attribute['variation']->id) ? 'checked' : '' }}>
                                <label for="{{ $attributeName }}-{{ $attribute['variation']->id }}"
                                    {{ $attribute['variation']->quantity > 0 ? '' : 'disable' }} class="fs-6">
                                    <div
                                        class="value d-flex text-center align-content-center border-label gap-2 mb-2 p-1 b-2 ">
                                        <div class="variation">
                                            <img class="image-product b-2 fs-6 "
                                                src="{{ $attribute['variation']->poster }}" width="40px"
                                                alt="Variation Image" class="variation-image">
                                        </div>
                                        <div class="option-name  card-title-responsive">
                                            <span class="card-title-responsive">{{ $attribute['attribute_value'] }}</span>
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
                        <div class="icon-erase" data-option-id="{{ $option_id }}">
                            <i class='bx bx-minus border-1 font-size-24 p-1 b-50'></i>
                        </div>
                        <div class="input">
                            <input type="text" value="1"
                                class="outline-0 quantity-product border-0 p-1 text-center" name="quantity-product" id="quantity-product"
                                style="width:50px" data-option-id="{{ $option_id }}">
                        </div>
                        <div class="icon-plus" data-option-id="{{ $option_id }}">
                            <i class="bx bx-plus font-size-24 border-1 p-1 b-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-layout w-100 pt-2">
            <button class="btn btn-danger b-2 w-100 p-2 btn-addto-card" id="btn-addto-card" data-option-id="{{ $option_id }}" data-product-id="{{ $product->id }}">
                Thay đổi
            </button>
        </div>
    </div>
@endif


@if ($type == 'favourite-item')
<div class="items mb-2">

    <div class="product d-flex gap-2 mb-2" >
        <div class="first">
            <div class="product-poster">
                <img width="100px" src="https://lh3.googleusercontent.com/d/17VoL_vN5T-hp86QG_sHd8HrfS65QhWR8" class="image-product b-2" alt="">
            </div>
        </div>
        <div class="second d-flex flex-column gap-3 w-100">
            <div class="product-title">
                {{ $item->product->title }}
            </div>

            <div class="price d-flex justify-content-between">
                <div class="first d-flex gap-2 align-items-center">
                    @if ($item->product->option_type == 0)

                    @php

                        $variation = $item->product->variations;

                    @endphp
                        <div class="price-saled text-bold text-danger">
                            @dd($variation->price)
                            @handlePrice($item->product->variations->price, $item->product->variations->sale)
                        </div>
                        <div class="text-secondary text-decoration-line-through">
                            @formatPrice($item->product->variations->price)
                        </div>
                    @else
                        <div class="price-saled text-bold text-danger">
                            @handlePrice($item->product->price,$item->product->sale)
                        </div>
                        <div class="text-secondary text-decoration-line-through">
                            @formatPrice($item->product->price)
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
    <div class="btn-menu d-flex justify-content-between gap-2 p-1 align-items-center" style="border-top:1px solid #ccc; border-bottom:1px solid #ccc;">
        <div class="status">
            @if ($item->product->option_type == 0)
                @if ($item->product->variations->quantity > 0)
                    <div class="badge text-success bg-success-subtle">Còn hàng</div>
                @else
                    <div class="badge text-secondary bg-secondary-subtle">Hết hàng</div>
                @endif
            @else
                @if ($item->product->quantity > 0)

                    <div class="badge text-success bg-success-subtle">Còn hàng</div>
                @else

                    <div class="badge text-secondary bg-secondary-subtle">Hết hàng</div>
                @endif
            @endif
            <div class="badge text-success bg-success-subtle">Còn hàng</div>
        </div>
        <div class="">
            <div class="btn btn-secondary b-2" data-favourite-id="{{ $item->id }}">Xóa</div>
            <div class="btn btn-danger b-2" data-favourite-id="{{ $item->id }}" data-product-id="{{ $item->product_id }}">Mua ngay</div>
        </div>
    </div>
</div>
@endif

@if ($type == 'admin.ItemProduct')
<tr>
    <th scope="row">{{ $item->id }}</th>
    <td>
        <img width="100px" class="image-product b-2"
            src="{{ $item->poster }}"
            alt="">
    </td>
    <td>
        <span class="">
            {{ $item->title }}
        </span>
    </td>
    <td>
        @formatPrice($item->price)
    </td>
    <td>
        {{ $item->sale }}%
    </td>
    <td>
        @handlePrice($item->price, $item->sale)
    </td>
    <td>
        <div class="d-flex align-items-center hover-underline" data-bs-toggle="modal"
            href="#modal-information-{{ $item->id }}" role="button">
            <div class="icon mt-2">
                <i class='bx bx-show font-size-24 text-success '></i>
            </div>
            <div class="title">
                <span class="badge text-success bg-success-subtle hover-underline">
                    Xem
                </span>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center hover-underline" href="#modal-image-product-{{ $item->id }}"
            data-bs-toggle="modal" role="button">
            <div class="icon mt-2">
                <i class='bx bx-show font-size-24 text-success '></i>
            </div>
            <div class="title">
                <span class="badge text-success bg-success-subtle hover-underline">
                    Xem
                </span>
            </div>
        </div>
    </td>
    <td>
        @foreach ($item->categories as $category)
            <div class="">
                {{ $category->name }}
            </div>
        @endforeach
    </td>
    <td>
        {{ $item->quantity }}
    </td>

    <td>
        {{ $item->quantity_saled }}
    </td>
    <td>
        {{ $item->total_rate }}
    </td>
    <td>
        @datetime($item->created_at)
    </td>
    <td>
        <span class="btn btn-primary b-2 mb-1"><i class='bx bx-edit-alt text-white' data-product-id="{{ $item->id }}"></i></span>
        <span class="btn btn-danger b-2 mb-1"><i class='bx bxs-trash text-white' data-product-id="{{ $item->id }}"></i></span>
    </td>

    <div class="modal fade w-100" id="modal-information-{{ $item->id }}" aria-hidden="true"
        aria-labelledby="modal-informationLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <h5 class="modal-title text-center text-bold" id="modal-informationLabel-{{ $item->id }}"
                        style=" flex:5;">Thông tin sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" id="modal-erase-categories"
                        style=" flex:0.2;"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer p-1">
                    <span class="btn btn-primary b-2" id="modal-save-categories"
                        data-bs-dismiss="modal" aria-label="Close">Ok</span>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade w-100" id="modal-image-product-{{ $item->id }}" aria-hidden="true"
        aria-labelledby="modal-image-productLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <h5 class="modal-title text-center text-bold"
                        id="modal-image-productLabel" style=" flex:5;">Ảnh đính kèm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" id="modal-erase-categories"
                        style=" flex:0.2;"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer p-1">
                    <span class="btn btn-primary b-2" id="modal-save-categories"
                        data-bs-dismiss="modal" aria-label="Close">Ok</span>
                </div>
            </div>
        </div>
    </div>
</tr>
@endif


@if ($type == '2' && isset($thumbnail))


<div class="decription-categories  row align-items-center justify-content-between" data-aos="fade-right">
    <div class="col-lg-4 col-sm-8" >
        <img class="b-2 w-100" src="{{ $thumbnail->thumbnail }}" alt="">
    </div>
    <div class="col-lg-6">
        <div class="title">
            <h4 class="text-bold text-center">{{ $thumbnail->title }}</h4>
        </div>
        <div class="decription font-size-14">
            {{ $thumbnail->description }}
        </div>
    </div>
</div>
@elseif($type == '1' && isset($thumbnail))
<div class="decription-categories mt-4 row align-items-center justify-content-between" data-aos="fade-left">
        
    <div class="col-lg-6" >
        <div class="title">
            <h4 class="text-bold text-center">{{ $thumbnail->title }}</h4>
        </div>
        <div class="decription font-size-14">
            {{ $thumbnail->description }}
        </div>
    </div>

    <div class="col-lg-4 col-sm-8">
        <img class="b-2 w-100" src="{{ $thumbnail->thumbnail }}" alt="">
    </div>
</div>
@elseif ($type == '3' && isset($thumbnail))
<div class="col-lg-4 col-sm-8 " data-aos="fade-down">
    <div class="title ">
        <h4 class="text-center text-bold">{{ $thumbnail->title }}</h4>
    </div>
    <div class="decription text-wrap">
        <span class="font-size14">
            {{ $thumbnail->description }}
        </span>
    </div>
    <img class="b-2 w-100 mt-3 mb-3" src="{{ $thumbnail->thumbnail }}" alt="">
    
</div>
@elseif ($type == '4' && isset($thumbnail))

<div class="col-lg-4 col-sm-8 " data-aos="fade-up">
    <img class="b-2 w-100" src="{{ $thumbnail->thumbnail }}" alt="">
    <div class="title mt-3">
        <h4 class="text-center text-bold">{{ $thumbnail->title }}</h4>
    </div>
    <div class="decription text-wrap">
        <span class="font-size14">
            {{ $thumbnail->description }}
        </span>
    </div>
</div>
@endif


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