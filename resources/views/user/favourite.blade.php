@extends('welcome')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-3 text-color" >
            <a href="{{ url('/') }}" class="text-decoration-none">
                <span class=" text-color">Trang chủ /</span>
            </a>
            <a href="{{ route('user.information') }}" class="text-decoration-none">
                <span class=" text-color">Trang cá nhân /</span>
            </a>
            <a href="{{ route('user.getviewPageFavourite') }}" class="text-decoration-none">
                <span class=" text-color">Sản phẩm yêu thích</span>
            </a>
        </div>

        <div class="container-content">
            <div class="card border-0 b-2 shadow mb-3">
                <div class="card-body ">
                    <h5 class="text-color">Sản phẩm yêu thích</h5>
                </div>
                
            </div>

            <div class="card b-2 border-0 shadow mb-4">
                <div class="card-body p-1" id="items-favourite">

                </div>

                <div class="p-5 d-none" id="loading-product">
                    <div class="loader-item mx-auto"></div>
                </div>
            </div>

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


        <div class="list-option-render container"></div>
    </div>

    <script>

        actionOnScrollBottom(window, function() {
            getFavourite();
        });

        var firstItem = []; 
        let page = 1;
        let noMoreFavourite = false;

        getFavourite(true);
        function getFavourite(NewPage = false){
            if(NewPage){
                page = 1;
                noMoreFavourite = false;
            }

            if(!noMoreFavourite){
                $('#loading-product').removeClass('d-none');
                $.ajax({
                    url:'{{ route('user.getProductFavourite') }}',
                    method: 'GET',
                    data:{
                        page: page,
                    },
                    success:function(res){

                        if(page == 1){
                            var html = '';
                            res.data.forEach(element => {
                                firstItem.push(element.product_id);
                                html += htmlItem(element);
                            });
                            $("#items-favourite").html(html);
                            
                        }else{
                            var html = '';
                            res.data.forEach(element => {
                                html += htmlItem(element);
                            });
                            $("#items-favourite").append(html);
                        }

                        noMoreFavourite = page >= res?.last_page;

                        if(!noMoreFavourite){
                            page++;
                        }
                        else{
                            getRecommenDation(firstItem, true);
                        }


                        $('#loading-product').addClass('d-none');

                    }
                })
            }
        }
        

        actionOnScrollBottom(window, function() {
            getRecommenDation(firstItem);
        });


        function htmlItem(product) {
            let priceHTML = '';
            let stockStatusHTML = '';
            let dataOption = '';
            let sale = 0;
            
            if (product.product.option_type === 0 && product.product.variations.length > 0) {
                const variation = product.product.variations[0];
                priceHTML = `
                    <div class="price-saleds text-bold text-danger">
                        ${handlePrice(variation.price, variation.sale)}
                    </div>
                    <div class="text-secondary text-decoration-line-through">
                        ${handlePrice(variation.price, 0)}
                    </div>`;
                stockStatusHTML = variation.quantity > 0
                    ? `<div class="badge text-success bg-success-subtle">Còn hàng</div>`
                    : `<div class="badge text-secondary bg-secondary-subtle">Hết hàng</div>`;

                dataOption = `data-option-id="${variation.id}"`;

                sale = variation.sale;
            } else {
                priceHTML = `
                    <div class="price-saleds text-bold text-danger">
                        ${handlePrice(product.product.price, product.product.sale)}
                    </div>
                    <div class="text-secondary text-decoration-line-through">
                        ${handlePrice(product.product.price, 0)}
                    </div>`;
                stockStatusHTML = product.product.quantity > 0
                    ? `<div class="badge text-success bg-success-subtle">Còn hàng</div>`
                    : `<div class="badge text-secondary bg-secondary-subtle">Hết hàng</div>`;
                dataOption = `data-option-id="null"`;

                sale = product.product.sale;
            }

            return `
                <div class="items mb-3 p-1 b-2" data-product-id="${product.product_id}">
                    <a href="/product/${product.product_id}" class="text-decoration-none">
                        <div class="product d-flex gap-2 mb-2">
                            <div class="first">
                                <div class="product-poster">
                                    <img width="100px" src="${product.product.poster}" class="image-product b-2" alt="">
                                </div>
                            </div>
                            <div class="second d-flex flex-column gap-2 w-100">
                                <div class="product-title card-title-responsive">
                                    ${product.product.title}
                                </div>
                                <div class="">
                                    <span class="badge bg-success"><i class='bx bxs-truck text-white'></i> Miễn phí</span>
                                    <span class="border-success border-1 badge text-success">15 ngày đổi trả</span>
                                    <span class="badge bg-danger">- ${sale}%</span>
                                </div>
                                <div class="price d-flex justify-content-between">
                                    
                                    <div class="second d-flex gap-2 align-items-center">
                                        ${priceHTML}
                                    </div>
                                    <div class="first">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="btn-menu d-flex justify-content-between gap-2 p-1 align-items-center" style="border-top:1px solid #ededed; border-bottom:1px solid #ededed;">
                        <div class="status">
                            ${stockStatusHTML}
                        </div>
                        <div class="">
                            <div class="btn btn-secondary b-2 bg-color-2 border-0 hover-color" data-product-id="${product.product_id}" style="padding:6px 30px 6px 30px; color:black;" id="btn-cancel-favourite" data-favourite-id="${product.id}">Xóa</div>
                            <div class="btn btn-danger b-2" id="buy-now" data-favourite-id="${product.id}" ${dataOption} style="padding:6px 30px 6px 30px;" data-product-id="${product.product_id}">Mua ngay</div>
                        </div>
                    </div>
                </div>`;
        }

        function handlePrice(price, sale) {
            var prices = price - price * sale/ 100;
            return formatPrice(prices);
        }

        function formatPrice(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }


        $(document).on('click', '#btn-cancel-favourite', function(){
            var id = $(this).data('product-id');
            var _this = $(this); // Save the context of `this`


            $.ajax({
                url: '{{ route('user.favourite.product') }}',
                method: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}',
                },
                success: function(res) {
                    _this.closest('.items').remove(); // Use the saved context
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        });

        let reComPage = 1;
        let noMoreRecomm = false;

        function getRecommenDation(id, newPage = false){

            if(newPage){
                reComPage = 1;
                noMoreRecomm = false;
            }

            if(!noMoreRecomm && noMoreFavourite && firstItem != undefined){
                $('#loading-product-append').removeClass('d-none');


                $.ajax({
                    url: `{{ route('product.ManyRecomendationProduct') }}`,
                    method: "GET",
                    data:{
                        ids: id,
                        page: reComPage,
                    },
                    success:function(res){
                        if(reComPage == 1){
                            $('#recommendation-product').html(res.product);
                        }
                        else{
                            $('#recommendation-product').append(res.product);
                        }

                        noMoreRecomm = reComPage >= res?.last_page;
                        if(!noMoreRecomm){
                            reComPage++;
                        } 

                        $('#loading-product-append').addClass('d-none');
                    }
                })
            }
            
        }


        


        $(document).on('click', '#buy-now', function(){
            var product_id = $(this).data('product-id');
            var option_id = $(this).data('option-id');
            if(option_id != null){
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
            }
            else{
                
            }
        })



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
    </script>
@endsection