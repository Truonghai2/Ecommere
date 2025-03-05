@if (Route::currentRouteName() !== 'user.viewPay')

<header class="position-sticky bg-white" id="header" style="top:0px; z-index:10;">

    <div class="container">
        <nav class="navbar p-0 navbar-expand-lg navbar-light bg-white d-flex">
            
            <a class="navbar-brand p-0 " href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" width="50px" style="border-radius:50%;" alt="Logo">
            </a>
            
            <div class="d-flex align-items-center order-lg-2" >
                


                <ul class="navbar-nav flex-row gap-756">
                    <!-- Các mục luôn hiển thị bên ngoài menu -->
                    <li class="nav-item search-sm-dis w-100 " style="display: none;">
                        <div class="search-product p-2 b-3 d-flex align-items-center w-100" href="#modal-search-product" data-bs-toggle="modal" role="button" style="background-color: #F3F6F6; width: 122px;">
                            <div class="icon"><i class='bx bx-search-alt-2 mt-1'></i></div>
                            <div class="inpt ml-1">
                                <span class="text-secondary">Tìm kiếm</span>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('user.viewCart') }}">
                            <i class='bx bxs-cart font-size-24'></i>
                            <span class="badge bg-danger position-absolute b-50 rp-tr" style="top: -4px; right: -3px;" id="update-quantity-cart">0</span>
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ url('chatify') }}">
                            <i class='bx bxl-messenger font-size-24'></i>
                            <span class="badge badge-danger position-absolute b-50 rp-tr" style="top: -10px; right: 0;">0</span>
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('user.notificationUser') }}">
                            <i class='bx bxs-bell font-size-24' ></i>
                            <span class="badge badge-danger bg-danger position-absolute b-50 rp-tr" style="top:  -4px; right: -3px;" id="update-quantity-notification">0</span>
                        </a>
                    </li>
                    @if (Auth::check())
                        <li class="nav-item dropdown {{ Route::currentRouteName() === 'user.information' ? 'd-none' : '' }}" >
                            <a class="nav-link " href="{{ route('user.information') }}" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bxs-user font-size-24' id="menu-user"></i>
                            </a>
                            
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class='bx bxs-user font-size-24'></i>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- Nút toggler cho menu -->
                <button class="navbar-toggler ml-2 border-0 p-0 outline-0" style="box-shadow: none;" id="menu-header" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class='bx bx-menu-alt-right  p-2' style="font-size: 30px;"></i>
                </button>
            </div>
            <div class="collapse navbar-collapse order-lg-1" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-bold {{ Route::currentRouteName() === 'user.getTumbnail' ? 'active' : '' }}" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold {{ request()->routeIs('user.category') && request()->slug == 'all-product' ? 'active' : '' }}" href="{{ route('user.category', ['slug' => 'all-product']) }}">Tất cả sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold {{ request()->routeIs('user.category') && request()->slug == 'may-loc-nuoc' ? 'active' : '' }}" href="{{ route('user.category', ['slug' => 'may-loc-nuoc']) }}">Máy lọc nước</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold {{ request()->routeIs('user.category') && request()->slug == 'xe-dien' ? 'active' : '' }} " href="{{ route('user.category', ['slug' => 'xe-dien']) }}">Xe điện</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold {{ request()->routeIs('user.category') && request()->slug == 'thiet-bi-ve-sinh' ? 'active' : '' }}" href="{{ route('user.category', ['slug' => 'thiet-bi-ve-sinh']) }}">Thiết bị vệ sinh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold" href="">Về chúng tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-bold" href="">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <div class="search-product p-2 b-3 d-flex align-items-center" href="#modal-search-product" data-bs-toggle="modal" role="button" style="background-color: #F3F6F6; width: 122px;">
                            <div class="icon"><i class='bx bx-search-alt-2 mt-1'></i></div>
                            <div class="inpt ml-1">
                                <span class="text-secondary">Tìm kiếm</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<div class="modal fade w-100" id="modal-search-product" aria-hidden="true" aria-labelledby="modal-search-productLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down">
      <div class="modal-content">
            <form action="" class="" method="GET">
                <div class="modal-header p-2 d-flex justify-content-between" style="cursor: pointer">
                    <div class="icon" data-bs-dismiss="modal" aria-label="Close" id="modal-erase-categories"><i class='bx bx-left-arrow-alt font-size-24' ></i></div>
                    <input type="text" name="search" autocomplete="off" class="w-100 b-3 border-color p-2 outline-0 ml-2"  id="search-product" placeholder="Nhập tên sản phẩm bạn muốn tìm">
                    <button class="d-none btn btn-primary b-2 border-color bg-color ml-2"><i class='bx bx-search-alt-2 font-size-24 mt-1 text-white' ></i></button>
                </div>
            </form>
        <div class="modal-body" id="modal-search-product-body">
            <div class="history-search text-center text-secondary font-size-16">
                Hiện chưa có lịch sử tìm kiếm
            </div>
            <div class="new-search"></div>
            <div class="p-5 d-none" id="loading-search-append">
                <div class="loader-item mx-auto"></div>
            </div>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#menu-user').click(function(){
            $('#dropdown-menu-user').toggle();
        });

        $('#menu-header').click(function(){
            $('#navbarNav').toggle();
        })
    });


    $('.search-product').click(function(){

    })
    var text = "";
    $("input[name=search]").on("input", function(){
        text = $(this).val();

        if(text.length > 0){
            $('.history-search').css('display', 'none');
            $('.new-search').css('display', 'block');

            fetchSearch(text, true);
        }
        else{
            $(".new-search").empty();
            $('.history-search').css('display', 'block');
            $('.new-search').css('display', 'none');
        }
    })

    var searchPage = 1;
    let  noMoreSearch = false;
    let loading = false;
        
    actionOnScrollBottomElement("#modal-search-product-body", function(){
        fetchSearch(text);
    })


    var searchTimeout;

    function fetchSearch(data, NewPage = false) {
        if (NewPage) {
            searchPage = 1;
            noMoreSearch = false;
            loading = false;
        }

        if (!noMoreSearch && !loading) {
            loading = true;
            $("#loading-search-append").removeClass('d-none');

            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: '{{ route('user.searchProduct') }}',
                    method: "GET",
                    data: {
                        data: data,
                        page: searchPage,
                    },
                    success: function(res) {
                        console.log(res);

                        if (searchPage == 1) {
                            if (res.total === 0) {
                            } else {
                                $('.new-search').html(res.html);
                            }
                        } else {
                            $('.new-search').append(res.html);
                        }

                        noMoreSearch = searchPage >= res?.last_page;
                        if (!noMoreSearch) {
                            searchPage++;
                        }
                        loading = false;
                        $('#loading-search-append').addClass('d-none');
                    },
                    error: function() {
                        loading = false;
                        $('#loading-search-append').addClass('d-none');
                    }
                });
            }, 250); 
        }
    }



    $.ajax({
        url: '{{ route('user.get.quantityCart') }}',
        method: "GET",
        success:function(res){
            $('#update-quantity-cart').text(res.quantity);
        }
    })

    $.ajax({
        url: '{{ route('notification.get.countNotification') }}',
        method: "GET",
        success:function(res){
            $('#update-quantity-notification').text(res.quantity);
        }
    })


    var lastScrollTop = 0;
    var header = $('#header'); // Thay thế '#header' bằng selector của header bạn muốn ẩn/hiện

    $(window).on('scroll', function() {
        var scrollTop = $(this).scrollTop();

        if (scrollTop > lastScrollTop) {
            // Người dùng vuốt xuống, ẩn header
            header.css('transform', 'translateY(-100%)');
        } else {
            // Người dùng vuốt lên, hiện header
            header.css('transform', 'translateY(0)');
        }

        // Cập nhật lại vị trí cuộn cuối cùng
        lastScrollTop = scrollTop;
    });
    

</script>
    @else
    <header class="position-sticky top-0 p-2" style="z-index:10;">
        <div class="container">
            <nav class="navbar p-0 navbar-expand-lg navbar-light bg-white">
                <a class="navbar-brand p-0 " href="{{ route('user.viewCart') }}">
                    <i class='bx bx-left-arrow-alt text-center' style="font-size: 28px;"></i>
                </a>
                <div class="title text-center">
                    <h6>Thanh Toán</h6>
                </div>
            </nav>
        </div>
    </header>
@endif