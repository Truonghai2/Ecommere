@extends('welcome')


@section('content')
    <div class="container">
        <div class="content-container pb-5">
            <div class="header-cart p-2 d-flex gap-1" style="border-bottom:1px solid #ccc;">
                <h5 class="">Giỏ hàng</h5>
                <span>({{ $check }})</span>
            </div>
            @if ($check > 0)

                <form action="{{ route('user.viewPay') }}" method="POST">
                    @csrf
                    <div class="main-content pt-3 pb-3">

                        @foreach ($product as $key => $item)
                            @if ($item['option'])
                            <div class="cart bg-white b-2 p-2 mb-2 border-label d-flex align-items-center gap-2 w-100" data-cart-id="{{ $item['cart_id'] }}">

                                <div class="checkbox">
                                    <input type="checkbox" name="checkbox-product[]" data-product-id="{{ $item['variation']->product_id }}" data-option-id="{{ $item['variation']->id }}" value="{{ $item['cart_id'] }}" id="checkbox-product-{{ $item['variation']->id }}">

                                    <label for="checkbox-product-{{ $item['variation']->id }}" class="checkbox-label"></label>
                                </div>

                                <div class="content d-flex w-100 gap-2 mx-sp-80">
                                    <div class="poster-product">
                                        <a href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id ]) }}">
                                            <img src="{{ $item['variation']->poster }}" width="100px" class="image-product b-2" alt="">
                                        </a>
                                    </div>

                                    <div class="information-product w-100">
                                        <div class="name-product mb-2">
                                            <a href="{{ route('user.detailproduct', ['id' => $item['variation']->product_id ]) }}" class="text-decoration-none text-black">
                                                {{ $item['product_title'] }}
                                            </a>
                                        </div>
                                        <div class="option mb-2">
                                            <div class="bg-color-2 p-1 b-2 option-content d-flex justify-content-between align-items-center w-100" data-variation-id="{{ $item['variation']->id }}" data-product-id="{{ $item['variation']->product_id }}">
                                                <span class="card-title-responsive d-inline-block text-truncate" 
                                                    style="max-width: 90%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $item['attribute_value'] }}
                                                </span>
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
                            @else
                            <div class="cart bg-white b-2 p-2 mb-2 border-label d-flex align-items-center gap-2" data-cart-id="{{ $item['cart_id'] }}">

                                <div class="checkbox">
                                    <input type="checkbox" name="checkbox-product[]" data-product-id="{{ $item['variation']->id }}" data-option-id="" value="{{ $item['cart_id'] }}" id="checkbox-product-{{ $item['variation']->id }}">

                                    <label for="checkbox-product-{{ $item['variation']->id }}" class="checkbox-label"></label>
                                </div>

                                <div class="content d-flex w-100 gap-2">
                                    <div class="poster-product">
                                        <a href="{{ route('user.detailproduct', ['id' => $item['variation']->id ]) }}">
                                            <img src="{{ $item['variation']->poster }}" width="100px" class="image-product b-2" alt="">
                                        </a>
                                    </div>

                                    <div class="information-product w-100">
                                        <div class="name-product mb-2">
                                            <a href="{{ route('user.detailproduct', ['id' => $item['variation']->id ]) }}" class="text-decoration-none text-black">
                                                {{ $item['product_title'] }}
                                            </a>
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
                                                                <input type="text" value="{{ $item['quantity'] }}" data-option-id="" data-product-id="{{ $item['variation']->id }}" data-cart-id="{{ $item['cart_id'] }}"
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
                        @endforeach

                        <div id="deleteConfirmModal" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog top-50" role="document" style="transform: translateY(-50%);">
                              <div class="modal-content b-2 " >
                                <div class="modal-header">
                                  <h5 class="modal-title text-center" style=" flex:5;">Xác nhận xóa đơn hàng</h5>
                                  <div class="btn-close"  data-bs-dismiss="modal" aria-label="Close" style=" flex:0.2;"></div>
                                </div>
                                <div class="modal-body">
                                  <p>Bạn chắc chắn muốn xóa sản phẩm này ra khỏi giỏ hàng chứ?</p>
                                </div>
                                <div class="modal-footer p-1">
                                    <div class="d-flex gap-2 w-100">
                                        <div class="btn btn-default bg-color-2 b-2 w-100 hover-color" style="color:black;" data-bs-dismiss="modal" aria-label="Close">Hủy</div>
                                        <div class="btn btn-danger b-2 w-100" id="confirm" data-bs-dismiss="modal" aria-label="Close" id="confirmDelete">Xóa</div>
                                    </div>
                                  
                                </div>
                              </div>
                            </div>
                          </div>


                    </div>
                    <div class="btn-shoping  bg-white  gap-2 p-2 position-fixed w-100 shadow-top" style="bottom: 0; left:0; z-index:1000;">
                       <div class="container d-flex justify-content-between align-items-center">
                        <div class="checkbox-product d-flex gap-2 w-25">
                            <input type="checkbox" name="select-all-product" class="d-block font-size-18" width="70px" id="select-all-product">
                            <label for="select-all-product" class="font-size-18 text-secondary border-0">Tất cả</label>
                        </div>
                        <div class="total-price w-50">
                            <div class="title">
                                <span class="text-secondary ">
                                    Tổng thanh toán
                                </span>
                                <span class="price-saled text-danger" id="total-price">0</span>
                            </div>
                            <div class="save-price">
                                <span class="text-secondary">Tiết kiệm</span>
                                <span class="text-danger" id="money-save">0</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger b-2 w-75 pt-2 pb-2 h-100">Thanh toán</button>
                       </div>
                    </div>
                </form>


                {{-- <script>
                    $.ajax({
                        url: ''
                    })

                </script> --}}
                @else
                <div class=""></div>
            @endif
        </div>


        <div class="list-option-render"></div>

    </div>
    <div id="error-message" class="alert alert-danger bg-danger badge text-white b-3" style="display:none; position: fixed; top: 50%; left:50%; z-index: 1003; transform: translate(-50%, -50%);"></div>

    <script>


        $('.icon-erase').click(function(){
            var id = $(this).data('cart-id');
            var data = $(`#quantity-product[data-cart-id="${id}"]`).val();

            if(data > 1){
                data--;
                $(`#quantity-product[data-cart-id="${id}"]`).val(data);
            }
            else{
                $('#deleteConfirmModal').data('cart-id', id).modal('show');
            }
            updateCart(id, data);
        })



        // event dơwn quantity item
        $('.quantity-product').on('change', function(){
            var id = $(this).data('cart-id');
            var data = $(this).val();

            if(data < 1){
                $('#deleteConfirmModal').data('cart-id', id).modal('show');
            }
            updateCart(id, data);
        })



        $('#confirmDelete').click(function() {
            var id = $('#deleteConfirmModal').data('cart-id');
            updateCart(id, 0);
        });


        // event plus quantity item
        $('.icon-plus').click(function(){
            var id = $(this).data('cart-id');
            var data = $(`#quantity-product[data-cart-id="${id}"]`).val();

            data++;

            $(`#quantity-product[data-cart-id="${id}"]`).val(data);
            updateCart(id, data);
        })



        // function call update quantity product in db
        function updateCart(id, quantity) {
            setTimeout(function() {
                $.ajax({
                    url: '{{ route('user.updateCart') }}',
                    method: 'POST',
                    data: {
                        id: id,
                        quantity: Number(quantity),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (quantity < 1) {
                            $(`#quantity-product[data-cart-id="${id}"]`).closest('.cart').remove();
                            $('#deleteConfirmModal').modal('hide');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while removing the item.');
                        $('#deleteConfirmModal').modal('hide');
                    }
                });
            }, 500);
        }


        
        $('input[name="select-all-product"]').change(function(){
            var isChecked = $(this).is(':checked');
            if(isChecked){
                $('input[name="checkbox-product[]"]').prop('checked', true);
                getPice();
            }
            else{
                $('input[name="checkbox-product[]"]').prop('checked', false);
                $('#total-price').html(0);
                $('#money-save').html(0);
            }
        })

        $('input[name="checkbox-product[]"]').change(function(){
            getPice();
        })



        function getPice(){
            var array = [];

            $('input[name="checkbox-product[]"]:checked').each(function() {
                var object = {
                    'option_id': $(this).data('option-id'),
                    'product_id': $(this).data('product-id'),
                    'quantity': $(`.quantity-product[data-product-id="${$(this).data('product-id')}"][data-option-id="${$(this).data('option-id')}"]`).val(),
                };
                array.push(object);
            });

            if(array.length > 0){
                $.ajax({
                    url: '{{ route('user.cartPrice') }}',
                    method: 'GET',
                    data:{
                        array:  JSON.stringify(array),
                    },
                    success:function(res){
                        $('#total-price').html(formatVND(res.totalPrice));
                        $('#money-save').html(formatVND(res.totalSavePrice));
                    }
                })
            }
        }

        function formatVND(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        $('.option-content').click(function(){
            var product_id = $(this).data('product-id');
            var option_id = $(this).data('variation-id');
            var check = $(`.layout-select-option[data-option-id="${option_id}"]`);
            if(check.length < 1){
                $.ajax({
                    url: '{{ route('user.getOptionProduct') }}',
                    method: "GET",
                    data:{
                        product_id: product_id,
                        option_id: option_id,
                    },
                    success:function(res){
                        $('.list-option-render').append(res.html);
                        eventClick();
                    }
                })
            }
            else{
                $('.layout-select-option').removeClass('d-none');
                $('.layout-select-option').addClass('d-none-option');
                $('.layout-select-option').removeClass('hidden-option');
            }

        })



        function eventClick(){


            $('input[name="option-product-radio"]').change(function(){
                var id = $(this).val();
                $('#layout-price-product').addClass('d-none');
                $('.poster-proudct').addClass('d-none');
                $('.quantity-product-layout').addClass('d-none');
                $(`.quantity-product-layout[data-variation-id="${id}"]`).removeClass('d-none');
                $(`.poster-proudct[data-variation-id="${id}"]`).removeClass('d-none');
                $('input[name="quantity-product"]').val("1");
            })


            $('.btn-close').click(function(){
                var id = $(this).data('option-id');
                $(`.layout-select-option[data-option-id="${id}"]`).addClass('d-none');
                $(`.layout-select-option[data-option-id="${id}"]`).removeClass('d-none-option');
                $(`.layout-select-option[data-option-id="${id}"]`).addClass('hidden-option');
            })
            var optionID = $('input[name="option-product-radio"]:checked').val();

            if(optionID != undefined){
                $('.price-product ').addClass('d-none');
                $('.poster-proudct').addClass('d-none');
                $('.quantity-product-layout').addClass('d-none');
                $(`.quantity-product-layout[data-variation-id="${optionID}"]`).removeClass('d-none');
                $(`.poster-proudct[data-variation-id="${optionID}"]`).removeClass('d-none');

                $('.price').addClass('d-none');
                $('.price-variations').addClass('d-none');
                $('.handle-address-user').addClass('d-none');
                $(`.price-variations[data-variation-id="${optionID}"]`).removeClass('d-none');
                $(`.handle-address-user[data-variation-id="${optionID}"]`).removeClass('d-none');
            }


            $('input[name="option-product-radio"]').on('change', function(){
                var data = $(this).val();
                $('.price').addClass('d-none');
                $('.price-variations').addClass('d-none');
                $('.handle-address-user').addClass('d-none');
                $(`.price-variations[data-variation-id="${data}"]`).removeClass('d-none');
                $(`.handle-address-user[data-variation-id="${data}"]`).removeClass('d-none');
            })

            $('.icon-erase').click(function() {
                var id = $(this).data('option-id');
                var inputElement = $(`input[name="quantity-product"][data-option-id="${id}"]`);
                var data = parseInt(inputElement.val(), 10);

                if (data > 1) {
                    data--;
                }
                inputElement.val(data);
            });

            $('.icon-plus').click(function() {
                var id = $(this).data('option-id');
                var inputElement = $(`input[name="quantity-product"][data-option-id="${id}"]`);
                var data = parseInt(inputElement.val(), 10);

                data++;
                inputElement.val(data);
            });



        }

        $(document).on('click', '.btn-addto-card', function(){
            var product_id = $(this).data('product-id');
            var option_id_old = $(this).data('option-id');

            var option_id_new = $(`input[name="option-product-radio"][data-option-id="${option_id_old}"]:checked`).val();

            var quantity = $(`input[name="quantity-product"][data-option-id="${option_id_old}"]`).val();

            if(option_id_old == option_id_new){
                showError("Ồ không! bạn đã chọn lại sản phẩm cũ rồi. Hãy chọn sản phẩm khác!");
                return;
            }
            if(quantity == 0){
                showError("Lỗi! Số lượng không được bằng 0. Vui lòng tăng số lượng!")
                return;
            }

            $.ajax({
                url: '{{ route('user.transCart') }}',
                method: "POST",
                data:{
                    product_id: product_id,
                    option_id_new: option_id_new,
                    option_id_old: option_id_old,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success:function(res){
                    $(`.cart[data-cart-id="${res.cart_old_id}"]`).remove();
                    $(`.cart[data-cart-id="${res.cart_new_id}"]`).remove();
                    $(`.layout-select-option[data-option-id="${option_id_old}"]`).remove();
                    $('.main-content').prepend(res.html);
                }
            })
        })

        function showError(message) {
            var errorMessage = $('#error-message');
            errorMessage.text(message);
            errorMessage.show();

            setTimeout(function() {
                errorMessage.hide();
            }, 3000); // Ẩn thông báo sau 3 giây
        }

    </script>



@endsection
