@extends('welcome')

@section('content')

    {{-- @dd($items) --}}
    <div class="container">
        <div class="container-content">
            <div class="address-user d-flex justify-content-between align-items-center p-2 mb-3 shadow b-2"
                onclick="getaddressUser()" data-bs-toggle="modal" href="#modal-menu-address" role="button">
                <div class="left d-flex gap-2">
                    <div class="icon">
                        <i class='bx bx-map-pin text-danger font-size-24'></i>
                    </div>
                    <div class="information-address">

                        <div class="text">
                            <span class="text-bold">Địa chỉ nhận hàng</span>
                        </div>
                        <div class="user">
                            <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} | </span>
                            <span
                                class="{{ auth()->user()->phone_number != null ? '' : 'text-danger hover-underline text-bold ' }}"
                                {{ auth()->user()->phone_number != null ? '' : 'data-bs-toggle="modal" href="#addPhonenumber" role="button"' }}>{{ auth()->user()->phone_number     ?? 'Vui lòng thêm số điện thoại' }}</span>
                        </div>
                        <div class="address">
                            @if (count($addresses) == 0)
                                <span class="bg-danger badge ">Vui Lòng thêm địa chỉ trước khi đặt hàng!</span>
                            @else
                                <span class="hover-underline text-wrap change-address-user" data-bs-toggle="modal" href="#modal-menu-address" role="button">
                                    Đ/c: {{ $addresses->last()->home_number }},
                                    {{ $addresses->last()->ward_name }},
                                    {{ $addresses->last()->district_name }},
                                    {{ $addresses->last()->provinces_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="icon-right">
                    <i class='bx bxs-chevron-right font-size-24'></i>
                </div>
            </div>
            <div class="click-add-number-phone d-none" data-bs-toggle="modal" href="#addPhonenumber" role="button">

            </div>

            <div class="item-product p-2 shadow mb-3 b-2">
                <div class="title mb-2">
                    Danh sách sản phẩm
                </div>

                <div class="render-product">
                    {{-- {{ $item['type'] }} --}}
                        @foreach ($product as $collection)
                            @if (count($collection) != 1)
                                <div class="content d-flex w-100 gap-2 mb-2 border-label b-1 p-1">
                                    <div class="poster-product">
                                        <a
                                            href="{{ route('user.detailproduct', ['id' => $collection['id']]) }}">
                                            <img src="{{ $collection['poster'] }}" width="100px"
                                                class="image-product b-2" alt="">
                                        </a>
                                    </div>

                                    <div class="information-product w-100">
                                        <div class="name-product mb-2">
                                            <a href="{{ route('user.detailproduct', ['id' => $collection['id']]) }}"
                                                class="text-decoration-none text-black">
                                                {{ $collection['title'] }}
                                            </a>
                                        </div>
                                        <div class="mb-2">
                                            <span class="badge bg-success"><i class='bx bxs-truck text-white'></i> Miễn phí</span>
                                            <span class="badge bg-danger">giảm {{ $collection['sale'] }}%</span>
                                            <span class="border-1 border-success badge text-success">15 ngày đổi trả</span>
                                        </div>
                                        <div class="price-quantity w-100 d-flex align-collections-center justify-content-between">
                                            <div class="price-card">
                                                <span class="text-danger text-bold">@handlePrice($collection['price'], $collection['sale'])</span>
                                                <span class="text-secondary text-decoration-line-through ml-2">
                                                    @formatPrice($collection['price'])
                                                </span>
                                            </div>
                                            <div class="quantity">
                                                x{{ $collection['cart_quantity'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($collection as $item)
                                    <div class="content d-flex w-100 gap-2 mb-2 border-label b-1 p-1">
                                        <div class="poster-product">
                                            <a
                                                href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id]) }}">
                                                <img src="{{ $item['variation']->poster }}" width="100px"
                                                    class="image-product b-2" alt="">
                                            </a>
                                        </div>

                                        <div class="information-product w-100">
                                            <div class="name-product mb-1">
                                                <a href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id]) }}"
                                                    class="text-decoration-none text-black">
                                                    {{ $item['product_title'] }}
                                                </a>
                                            </div>
                                            <div class="option mb-1">
                                                <div class="bg-color-2 p-1 b-2 option-content" data-variation-id="{{ $item['variation']->id }}"
                                                    data-product-id="{{ $item['variation']->product_id }}">
                                                    <span>Phẩn loại: </span>
                                                    <span>{{ $item['attribute_value'] }}</span>
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                <span class="badge bg-success"><i class='bx bxs-truck text-white'></i> Miễn phí</span>
                                                <span class="badge bg-danger">giảm {{ $item['variation']->sale }}%</span>
                                                <span class="border-1 border-success badge text-success">15 ngày đổi trả</span>
                                            </div>

                                            <div class="price-quantity w-100 d-flex align-items-center justify-content-between">
                                                <div class="price-card">
                                                    <span class="text-danger text-bold">@handlePrice($item['variation']->price, $item['variation']->sale)</span>
                                                    <span class="text-secondary text-decoration-line-through ml-2">
                                                        @formatPrice($item['variation']->price)
                                                    </span>
                                                </div>
                                                <div class="quantity">
                                                    x{{ $item['quantity'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                </div>
            </div>


            <div class="price-ship shadow b-2 p-2 mb-3">



                <div class="shipandtime bg-success-subtle border-success b-1">
                    <div class="render p-2">
                        <div class="content-type-ship">
                            <div class="title mb-2">
                                <span class="">Phương thức vẫn chuyển</span>
                            </div>

                        </div>
                        <div class="price d-flex justify-content-between align-items-center">
                            <div class="title">
                                <span class="text-bold">Nhanh</span>
                                <div class="icon">
                                    <span class="badge bg-success"><i class='bx bxs-truck text-white'></i> Miễn phí</span>
                                </div>
                            </div>
                            <div class="">
                                <span class="text-secondary text-decoration-line-through">{{ $price_ship }}</span>
                                <span class="text-danger text-bold">0đ</span>
                            </div>
                        </div>
                    </div>
                    <div class="timecalculate p-2">

                        <div class="content">
                            Thời gian giao hàng dự kiến: <span class="text-bold">{{ $calculateTimeship }}</span>
                        </div>
                    </div>
                </div>

                <div class="massage-to-admin d-flex justify-content-between pt-2 pb-2">
                    <div class="title text-bold">Nhắn tin</div>
                    <div class="content text-secondary">Để lại lời nhắn</div>
                </div>

                <div class="total-price d-flex justify-content-between pb-2 ">
                    <div class="title text-bold">Tổng số tiền({{ $numberItem }} sản phẩm): </div>
                    <div class="content text-danger text-bold font-size-19 total_price">@formatPrice($total_price)</div>
                </div>
            </div>

            <div class="price-ship shadow b-2 p-2 mb-3 d-flex align-items-center justify-content-between"
                data-bs-toggle="modal" href="#payment" role="button">
                <div class="title d-flex align-items-center gap-1">
                    <div class="icon">
                        <span><i class='bx bx-dollar-circle text-danger font-size-24'></i></span>
                    </div>
                    <div class="">
                        <span class="ml-1">Phương thức thanh toán</span>
                    </div>
                </div>
                <div class="content d-flex align-items-center gap-2">
                    <div class="type-payment text-secondary" data-typepayment-id="0">
                        chọn phương thức thanh toán
                    </div>

                    <div class="select-payment">
                        <i class='bx bxs-chevron-right'></i>
                    </div>
                </div>
                {{-- @getService() --}}
            </div>

            <div class="price-ship shadow b-2 p-2 mb-3">
                <div class="title d-flex gap-1 align-items-center mb-3">
                    <div class="icon">
                        <i class='bx bx-receipt text-warning font-size-24'></i>
                    </div>
                    <div class="">
                        <span class="">Chi tiết thanh toán</span>
                    </div>
                </div>


                <div class="render ">
                    <div class="total-price w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Tổng tiền hàng ({{ $numberItem }} sản phẩm)
                            </span>
                        </div>
                        <div class="price">
                            @formatPrice($total_price)
                        </div>
                    </div>

                    <div class="total-ship w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Tổng tiền phí vận chuyển
                            </span>
                        </div>
                        <div class="price">
                            {{ $price_ship }}
                        </div>
                    </div>
                    <div class="total-sale-price-ship w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Giảm giá phí vận chuyển
                            </span>
                        </div>
                        <div class="price">
                            - {{ $price_ship }}
                        </div>
                    </div>
                    <div class="installment w-100 d-flex align-items-center justify-content-between d-none">
                        <div class="title">
                            <span class="text-secondary">
                                Phí trả góp
                            </span>
                        </div>
                        <div class="price">

                        </div>
                    </div>

                    <div class="total-price w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-bold font-size-18">
                                Tổng thanh toán ({{ $numberItem }} sản phẩm):
                            </span>
                        </div>
                        <div class="price text-danger text-bold font-size-18 total_price">
                            @formatPrice($total_price)
                        </div>
                    </div>
                </div>
            </div>

            <div class="note d-flex bg-white shadow align-items-center p-2 b-2 gap-2">
                <div class="icon">
                    <i class='bx bx-notepad text-warning font-size-24'></i>
                </div>
                <div class="content">
                    Nhấn "Đặt hàng" đồng nghĩa với việc bạn đồng ý tuân theo <span
                        class="hover-underline text-primary">điều
                        khoản</span> của chúng tôi.
                </div>
            </div>

            <div class="position-fixed shadow-top p-2 w-100 bg-white" style="bottom: 0; left: 0; z-index:10;">
                <div class="container d-flex align-items-center gap-2 justify-content-end">
                    <div class="total-price">
                        <div class="title">
                            <span class="text-secondary">
                                Tổng thanh toán ({{ $numberItem }} sản phẩm)
                            </span>
                        </div>
                        <div class="price-new text-bold text-danger text-end total_price">
                            @formatPrice($total_price)
                        </div>
                    </div>
                    <button class="btn btn-danger h-100 w-50" id="btn-create-order">
                        Đặt hàng
                    </button>
                </div>
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
                        <div class="title text-danger" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                            Thêm địa chỉ nhận hàng
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade w-100" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel" style=" flex:5;">Thêm địa
                        chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="modal-erase-categories" style=" flex:0.2;"></button>
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
                            <label for="home_number" class="font-size-14 text-secondary">Tỉnh/thành</label>
                            <select name="provinces" id="provinces" class="w-100 border-color p-2 outline-0 b-2"
                                required>
                                <option value="">Chọn tỉnh/thành</option>
                                @foreach ($provines as $item)
                                    <option value="{{ $item['ProvinceID'] }}|{{ $item['ProvinceName'] }}">
                                        {{ $item['ProvinceName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="group-form">
                            <label for="district" class="font-size-14 text-secondary">Quận/huyện</label>
                            <select name="district" id="district" class="w-100 border-color p-2 outline-0 b-2" required>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                        </div>
                        <div class="group-form">
                            <label for="ward" class="font-size-14 text-secondary">Xã/phường</label>
                            <select name="ward" id="ward" class="w-100 border-color p-2 outline-0 b-2" required>
                                <option value="">Chọn xã/phường</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <span class="btn btn-secondary b-2" id="modal-erase-categories" data-bs-dismiss="modal"
                            aria-label="Close">Hủy</span>
                        <button class="btn btn-primary b-2" type="submit" id="modal-save-notification">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade w-100" id="addPhonenumber" aria-hidden="true" aria-labelledby="addPhonenumberLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <div class="modal-title text-center text-bold" style="flex: 5;">
                        Thêm số điện thoại
                    </div>
                    <div class="btn-close" data-bs-dismiss="modal" aria-label="Close"></div>
                </div>
                <div class="modal-body">
                    <div class="ip-number-phone d-flex align-content-center p-2 b-3 border-color border-1 gap-2">
                        <div class="icon"><i class='bx bxs-phone font-size-24 text-color'></i></div>
                        <div class="inp w-100">
                            <input type="text"  name="phone_number" class="border-0 w-100 outline-0" id="phone_number_user" placeholder="Nhập Số điện thoại">
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button class="btn btn-primary bg-color border-color" id="continue-add-number-phone">Tiếp tục</button>
                </div>
            </div>
        </div>
    </div>


    <div class="accept-success" data-bs-dismiss="modal"  aria-label="Close" data-bs-target="#verify-number-phone" data-bs-toggle="modal"> </div>
    <div class="modal fade w-100" id="verify-number-phone" aria-hidden="true" aria-labelledby="verify-number-phoneLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <div class="modal-title text-center text-bold" style="flex: 5;">
                        Xác minh số điện thoại
                    </div>
                    <div class="btn-close" data-bs-dismiss="modal" aria-label="Close"></div>
                </div>
                <div class="modal-body">
                    <div class="content mb-2">
                        <span class="text-wrap">
                            Mã xác minh số điện thoại của bạn đã được gửi về số điện thoại của bạn. Nếu không nhận được hãy ấn gửi lại. Bạn có tối đa 1 phút để nhập mã xác nhận.
                        </span>
                    </div>
                    <div class="ip-number-phone p-2 b-3 border-color border-1 mb-2">
                        <input type="text" name="code-verify" class="border-0 w-100 outline-0" id="code-verify" placeholder="Nhập mã xác minh">
                    </div>

                    <div class="menu d-flex justify-content-between">
                        <div class="btn-reset d-flex gap-2 align-items-center text-color b-2 p-2 border-label">
                            <div class="icon"><i class='bx bx-reset font-size-24 text-color' ></i></div>
                            <div class="title">
                                Gửi lại
                            </div>
                        </div>

                        <div class="time b-2 p-2 border-label text-secondary">
                            1.00s
                        </div>
                    </div>
                    <div class="text-color text-bold text-center hover-underline">
                        Đây không phải số của bạn?
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button class="btn btn-primary bg-color border-color w-100 b-2" id="verify-phone-number" data-bs-dismiss="modal"  aria-label="Close">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#continue-add-number-phone').click(function() {
            var phoneNumber = $('input[name="phone_number"]').val();

            // Regular expression for Vietnamese phone numbers
            var phoneRegex = /^(?:\+84|0)(?:3[2-9]|5[689]|7[06-9]|8[1-689]|9[0-9])\d{7}$/;

            // Check if the phone number matches the regex
            if (phoneRegex.test(phoneNumber)) {
                $('.accept-success').click();

                $.ajax({
                    url: '{{ route('user.addPhoneNumber') }}',
                    method: "POST",
                    data:{
                        phone: phoneNumber,
                        _token: '{{ csrf_token() }}',
                    },
                    success:function(res){
                        time();
                    }
                })
            } else {
                console.log("Invalid Vietnamese phone number");
                // Display an error message or handle the invalid input
                alert("Please enter a valid Vietnamese phone number.");
            }
        });

        $('#verify-phone-number').click(function(){
            var code = $('input[name="code-verify"]').val();

            if(code.length == 6){
                $.ajax({
                    url: '{{ route('user.VerifyPhoneNumber') }}',
                    method: "POST",
                    data:{
                        code: code,
                        _token: "{{ csrf_token() }}",
                    },
                    success:function(res){
                        console.log(res);
                    }
                })
            }
        })

        function time(){
            let timeLeft = 60; // 60 seconds

                $('.time').text(timeLeft);

                const interval = setInterval(function() {
                    timeLeft--;
                    $('.time').text(timeLeft);

                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        $('.timer').text(0);
                    }
                }, 1000); // 1000 milliseconds = 1 second

        }
    </script>
    <div class="modal fade w-100" id="payment" aria-hidden="true" aria-labelledby="paymentLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2">
                <div class="modal-header">
                    <div class="modal-title text-center text-bold" style="flex: 5;">
                        Phương thức thanh toán
                    </div>
                    <div class="btn-close" data-bs-dismiss="modal" aria-label="Close"></div>
                </div>
                <div class="modal-body">
                    <div class="item">
                        <div class="d-flex align-items-center justify-content-between hover-color b-2">
                            <div class="p-2 b-2 d-flex gap-2 align-items-center type-payment-select"
                                data-bs-dismiss="modal" aria-label="Close" data-select-id="1">
                                Mua hàng trả ngay
                            </div>
                            <div class="price text-danger text-bold">
                                @formatPrice($total_price)
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between hover-color b-2">
                            <div class="p-2  b-2 d-flex gap-2 align-items-center type-payment-select"
                                data-bs-dismiss="modal" aria-label="Close" data-select-id="2">
                                Mua hàng trả góp <span class="bg-success-subtle text-success badge b-1">lãi suất 0%</span>
                            </div>
                            <div class="price text-danger text-bold">
                                @formatPrice($total_price + ($total_price * 8 /100))
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error-message" class="alert alert-danger bg-danger badge text-white b-3"
        style="display:none; position: fixed; top: 50%; left:50%; z-index: 1003; transform: translate(-50%, -50%);"></div>
    <script>
        var id_payment = 0;
        $('.type-payment-select').click(function() {
            var id = $(this).data('select-id');
            var html = $(this).html();
            id_payment = id;
            $('.type-payment').removeClass('text-secondary').addClass('text-danger text-bold').attr(
                'data-typepayment-id', id);
            $('.type-payment').html(html);

            if(id == 2){
                $('.installment').removeClass('d-none');
                $('.installment').find('.price').html(pricePlus());
                updateTotalPrice( {{ $total_price }} * 8 / 100);
            }
            else{
                $('.installment').addClass('d-none');
                updateTotalPrice(0)
            }

        })

        function getaddressUser() {
            $.ajax({
                url: '{{ route('user.getAddress') }}',
                method: 'GET',
                success: function(res) {
                    var html = '';
                    res.address.forEach(element => {
                        html += htmlItemAddress(element);
                    });

                    $('.add-item-address').html(html);

                }
            })
        }

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


        function updateTotalPrice(price){
            var price = formatVND({{ $total_price }} + price ?? 0);
            $('.total_price').html(price);
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
                        // console.log(res);
                        var html = '';
                        res.data.forEach(element => {
                            html += renderOptionWard(element);
                        });

                        $('#ward').append(html);
                    }
                })
            }
        })


        function pricePlus(){
            var total_price = {{ $total_price }};

            return formatVND(total_price * (8/100));
        }

        function formatVND(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        function renderOptionWard(data) {
            return `<option value="${data.WardCode}|${data.WardName}">${data.WardName}</option>`;
        }


        $('#btn-create-order').click(function() {
            if (id_payment == 0) {
                showError("Vui lòng chọn phương thức thanh toán!")
                return;
            }
            var phoneNumber = {{ auth()->user()->phone_number }}
            if(phoneNumber == null){
                showError("Vui lòng thêm số điện thoại!")
                $('.click-add-number-phone').click();
                return;
            }

            $.ajax({
                url: "{{ route('user.createOrder') }}",
                method: "GET",
                data:{
                    type_payment: id_payment,
                    _token: "{{ csrf_token() }}"
                },
                success:function(res){
                    if (res.url) {
                        window.location.href = res.url;
                    }
                }
            })


        })


        function showError(message) {
            var errorMessage = $('#error-message');
            errorMessage.text(message);
            errorMessage.show();

            setTimeout(function() {
                errorMessage.hide();
            }, 3500);
        }
    </script>
@endsection
