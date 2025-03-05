@extends('admin.layout.app')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-5">
            <span class="text-start">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}"  class="text-color">
                        Quản Lý Hệ Thống
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('admin.view.orderProduct') }}" class="text-color">
                        Quản lý đơn hàng
                    </a>
                </span>
            </span>
        </div>

        <div class="card b-2 shadow border-0 d-none">
            <div class="card-body">
                <div id="billContainer"></div>
            </div>
        </div>

        <div class="card b-2 shadow border-0">
            <div class="card-header b-2 bg-white d-flex align-items-center justify-content-between">
                <h5 class="card-title text-color text-bold">Quản lý đơn hàng</h5>
                <div class="row gap-2">
                    <div class="col-md-6 col-8 search-item d-flex align-items-center gap-2 b-3 border-1 p-2">
                        <div class="icon">
                            <i class='bx bx-search-alt-2 '></i>
                        </div>
                        <div class="title">
                            <input type="text" style="background: inherit" name="search-item-order" class="outline-0 border-0 w-100" id="search-item-order" placeholder="Tìm kiếm">
                        </div>
                    </div>
                    <div class="col-md-5 col-8 d-flex align-items-center hover-color bg-color b-2 gap-2">
                        <div class="icon">
                            <i class='bx bxs-file-export text-white'></i>
                        </div>
                        <div class="title badge">Báo cáo</div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                            <tr class="border-0">
                               <th scope="col" class="text-white">ID</th>
                               <th scope="col" class="text-white">Họ tên</th>
                               <th scope="col" class="text-white">Địa chỉ nhận</th>
                               <th scope="col" class="text-white">Số điện thoại</th>
                               <th scope="col" class="text-white">Chi tiết chi phí</th>
                               <th scope="col" class="text-white">Chi phí</th>
                               <th scope="col" class="text-white">Phương thức thanh toán</th>
                               <th scope="col" class="text-white">Chú ý của người dùng</th>
                               <th scope="col" class="text-white">Mã vận chuyển</th>
                               <th scope="col" class="text-white">Trạng thái vận chuyển 
                                    {{-- <select name="" id="">
                                        <option value=""></option>
                                        <option value=""></option>
                                    </select> --}}
                                </th>
                               <th scope="col" class="text-white">Thông tin đơn hàng</th>
                               <th scope="col" class="text-white">Ngày tạo</th>
                               <th scope="col" class="text-white">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="right" style="display: none">
                        </tbody>
                        <tbody class="told">

                        </tbody>
                    </table>
                </div>


                <div class="p-5 d-none" id="loading-product-append">
                    <div class="loader-item mx-auto"></div>
                </div>
            </div>
        </div>

        <div class="modal fade w-100" id="showInformationOrder" tabindex="-1" aria-labelledby="showInformationOrderLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="showInformationOrderToggleLabel" style=" flex:5;">
                            Thông tin đơn hàng
                        </h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex gap-2 align-items-center mb-3">
                            <div class="icon">
                                <i class='bx bx-package font-size-24 text-success'></i>
                            </div>
                            <div class="title">
                                Chi tiết đơn đặt hàng
                            </div>
                        </div>
                        <div class="form-group" id="ordersContainer">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade w-100" id="showExpense" tabindex="-1" aria-labelledby="showExpenseLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="showExpenseToggleLabel" style=" flex:5;">
                            Thông tin chi phí
                        </h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="expenseOrderContainer">

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade w-100" id="confirmModal" style="z-index: 1101;" tabindex="-1"
            aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="confirmationModalLabel" style="flex: 5;">Bạn có chắc chắn muốn hủy không?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="flex: 0.2;"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn hủy đơn hàng này không? Hành động này không thể hoàn tác.</p>
                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">Lý do hủy:</label>
                            <select id="cancelReason" class="form-select  b-2 border-color">
                              <option value="" selected>Chọn lý do hủy</option>
                              <option value="not_needed">Không cần nữa</option>
                              <option value="ordered_wrong">Đặt nhầm sản phẩm</option>
                              <option value="found_cheaper">Tìm được sản phẩm rẻ hơn</option>
                              <option value="other">Lý do khác</option>
                            </select>
                          </div>
                    </div>
                    <div class="modal-footer p-1">
                        <div class="d-flex gap-2 w-100">
                            <div class="btn btn-default bg-color-2 b-2 w-100 hover-color" style="color:black;" data-bs-dismiss="modal"
                            aria-label="Close">Hủy</div>
                            <div class="btn btn-danger b-2 w-100" data-bs-dismiss="modal"
                            aria-label="Close" id="confirmCancel">Xóa</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>



        let page = 1;
        let loading = false;
        let noMoreOrder = false;
        var allOrder = [];
        let orderIdFocus = 0;
        actionOnScrollBottom(window,function(){
            getOrder();
        })
        getOrder(true);

        function getOrder(newPage = false){
            if(newPage){
                page = 1;
                noMoreOrder = false;
                loading = false;
                allOrder = [];
            }

            if(!noMoreOrder && !loading){
                $('#loading-product-append').removeClass('d-none');
                $.ajax({
                    url: "{{ route('admin.get.orderProduct') }}",
                    method: "GET",
                    data:{
                        page: page,
                    },
                    success:function(res){
                        allOrder = allOrder.concat(res.orders);

                        if(page == 1){
                            var html = '';
                            res.orders.forEach(order => {
                                html += htmlItemOrder(order);
                            });

                            $('.told').html(html);
                        }
                        else{
                            var html = '';
                            res.orders.forEach(order => {
                                html += htmlItemOrder(order);
                            });

                            $('.told').append(html);
                        }

                        noMoreOrder = page >= res?.last_page;
                        if(!noMoreOrder){
                            page++;
                        }
                        $('#loading-product-append').addClass('d-none');

                    }
                })
            }
        }

        var array_status = {
            'cancel': 'Đã hủy',
            'picking': 'Đang chuẩn bị hàng',
            'picked': 'Đơn vị giao hàng đã lấy hàng',
            'delivering': 'Đang trên đường vận chuyển',
            'delivery_fail': 'Giao hàng không thành công',
            'delivery_successful': 'Giao hàng thành công',
            'waiting_to_return': 'Chờ trả hàng về',
            'return': 'Trả hàng'
        };

        function htmlItemOrder(order){

            var payment = '';
            if(order.payment_id == 1){
                payment = 'Thanh toán trả ngay';
            }
            else{
                payment = "Thanh toán trả góp";
            }


            return `<tr  data-order-id="${order.id}">
                <td>${order.id}</td>
                <td>${order.to_user_name}</td>
                <td class="font-size-15">${order.to_ward_name}, ${order.to_district_name}, tp ${order.to_province_name}</td>
                <td class="">${order.to_phone_number}</td>
                <td>
                    <div class="d-flex align-items-center hover-underline" id="showExpenseOrder" data-order-id="${order.id}" data-bs-toggle="modal" href="#showExpense" role="button">
                        <div class="icon mt-2">
                            <i class="bx bx-show font-size-24 text-success "></i>
                        </div>
                        <div class="title">
                            <span class="badge text-success bg-success-subtle hover-underline">
                                Xem
                            </span>
                        </div>
                    </div>
                </td>
                <td class="text-danger text-bold">
                    ${handlePrice(order.price_new, 0)}
                </td>
                <td>
                    ${payment}
                </td>
                <td>
                    ${order.content}
                </td>
                <td class="text-success bg-success-subtle badge">
                    ${order.order_code}
                </td>
                <td class="text-danger">
                    ${array_status[order.status_ship]}
                </td>
                <td>
                    <div class="d-flex align-items-center hover-underline" id="clickshowInformationOrder" data-order-id="${order.id}" data-bs-toggle="modal" href="#showInformationOrder" role="button">
                        <div class="icon mt-2">
                            <i class="bx bx-show font-size-24 text-success "></i>
                        </div>
                        <div class="title">
                            <span class="badge text-success bg-success-subtle hover-underline">
                                Xem
                            </span>
                        </div>
                    </div>
                </td>
                <td>${formatDate(order.created_at)}</td>
                <td>
                    <div class="btn btn-close mb-1 p-3 border-0 bg-color-2" id="cancel-order" data-order-code="${order.order_code}" data-user-id="${order.user_id}" data-order-id="${order.id}">
                    </div>
                    <div class="btn btn-default bg-color-2" id="printBill" data-order-code="${order.order_code}">
                        <i class='bx bx-printer font-size-20'></i>
                    </div>
                </td>
            </tr>`;
        }

        function formatPrice(price) {
            if (price != null) {
                return price.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }
            return '';
        }

        function handlePrice(price, sale) {
            const discountedPrice = price - (price * (sale / 100));
            return formatPrice(discountedPrice);
        }

        function formatDate(datetime) {
            return new Date(datetime).toLocaleString();
        }


        $(document).on('click', '#printBill', function(){
            var order_code = $(this).data('order-code');

            if(order_code){
               $.ajax({
                    url: '{{ route('admin.print.billtoA5') }}',
                    method: "GET",
                    data:{
                        order_code: order_code,
                    },
                    success:function(res){
                        $('#billContainer').html('<iframe src="' + res.url + '" style="width:100%; height:600px;"></iframe>');
                    }
               })
            }
        })


        function getOrderById(){
            if(orderIdFocus != 0){
                var order = allOrder.find(o => o.id === orderIdFocus);

                if(order){
                    return order;
                }
                return null;
            }
            return null;
        }

        $(document).on('click', '#clickshowInformationOrder', function(){
            orderIdFocus = $(this).data('order-id');
            var order = getOrderById();

            var items = order.list_item_orders;

            $('#ordersContainer').empty();


            var html = ''
            items.forEach(item => {
                var option = '';
                if(item.option_id != null){
                    option = `<div class="option ">
                                        <span class="b-3 bg-color-2 p-2">
                                            <span>Phân loại: </span>
                                            <span>${item.option_name}</span>
                                        </span>
                                    </div>`;
                }
                html += `
                        <div class="product d-flex gap-2 pb-3 " style="border-bottom: 1px solid #ccc; ">
                            <div class="first">
                                <div class="product-poster">
                                    <img width="100px" src="${item.poster}" class="image-product b-2" alt="">
                                </div>
                            </div>
                            <div class="second d-flex flex-column gap-2 w-100">
                                <div class="product-title">
                                    ${item.name}
                                </div>
                                ${option}
                                <div class="">
                                    <span class="badge bg-success"><i class="bx bxs-truck text-white"></i> Miễn phí</span>
                                    <span class="badge bg-danger">- ${item.sale}%</span>
                                    <span class="border-1 border-success badge text-success">15 ngày đổi trả</span>
                                </div>
                                <div class="price d-flex justify-content-between">
                                    <div class="first d-flex gap-2 align-items-center">
                                        <div class="price-saled text-bold text-danger">
                                            ${handlePrice(item.price, item.sale)}                                      </div>
                                        <div class="text-secondary text-decoration-line-through">
                                            ${handlePrice(item.price, 0)}                                          </div>
                                    </div>
                                    <div class="last">
                                        <div class="quantity">
                                            x${item.quantity}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;
            })

            $('#ordersContainer').html(html);
        })

        $(document).on('click', '#showExpenseOrder', function(){
            orderIdFocus = $(this).data('order-id');

            var order = getOrderById();
            $('#expenseOrderContainer').empty();
            var install_price = '';
            if(order.payment_id == 2){
                install_price = `<div class="installment w-100 d-flex align-items-center justify-content-between d-none">
                        <div class="title">
                            <span class="text-secondary">
                                Phí trả góp
                            </span>
                        </div>
                        <div class="price">
                            ${handlePrice(order.price_install_pay)}
                        </div>
                    </div>`;
            }
            var html = `<div class="price-ship">
                <div class="title d-flex gap-1 align-items-center mb-3">
                    <div class="icon">
                        <i class="bx bx-receipt text-warning font-size-24"></i>
                    </div>
                    <div class="">
                        <span class="">Chi tiết thanh toán</span>
                    </div>
                </div>


                <div class="render">
                    <div class="total-price w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Tổng tiền hàng (${order.list_item_orders.length} sản phẩm)
                            </span>
                        </div>
                        <div class="price">
                            ${handlePrice(order.price_old, 0)}
                        </div>
                    </div>

                    <div class="total-ship w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Tổng tiền phí vận chuyển
                            </span>
                        </div>
                        <div class="price">
                            ${handlePrice(order.price_ship, 0)}
                        </div>
                    </div>
                    <div class="total-sale-price-ship w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-secondary">
                                Giảm giá
                            </span>
                        </div>
                        <div class="price">
                            ${handlePrice(order.price_ship, 0)}
                        </div>
                    </div>

                    ${install_price}

                    <div class="total-price w-100 d-flex align-items-center justify-content-between">
                        <div class="title">
                            <span class="text-bold font-size-18">
                                Tổng thanh toán (${order.list_item_orders.length} sản phẩm):
                            </span>
                        </div>
                        <div class="price text-danger text-bold font-size-18 total_price">
                            ${handlePrice(order.price_new, 0)}                        </div>
                    </div>
                </div>
            </div>`;
            $('#expenseOrderContainer').html(html);
        })

        $('input[name="search-item-order"]').change(function(){
            var data = $(this).val();

        })
        var orderCode = 0;
        var user_id = 0;
        $(document).on('click', '#cancel-order', function(){
            orderIdFocus = $(this).data('order-id');
            orderCode = $(this).data('order-code');
            user_id = $(this).data('user-id');

            // Hiển thị modal
            $('#confirmModal').modal('show');
        });

        // Xử lý khi người dùng nhấn vào nút "Hủy đơn hàng" trong modal
        $(document).on('click', '#confirmCancel', function(){
            var cancelReason = $('#cancelReason').val();
    
            if (cancelReason === "") {
                alert("Vui lòng chọn lý do hủy đơn.");
                return;
            }


            $.ajax({
                url: "{{ route('admin.cancel.orderProduct') }}",
                method: "POST",
                data:{
                    order_id: orderIdFocus,
                    order_code: orderCode,
                    user_id : user_id,
                    reason: cancelReason,
                    _token: "{{ csrf_token() }}",
                },
                success:function(res){

                }
            })

            $('#confirmModal').modal('hide');
        });
    </script>
@endsection
