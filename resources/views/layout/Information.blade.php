@extends('welcome')

@section('content')
    <div class="container">
        <div class="header-container bg-color pt-5 pb-5 mb-5 position-relative">
            <div class="user d-flex gap-2 position-absolute ml-2">
                <div class="avatar">
                    <img src="{{ asset('img/default-avatar.jpg') }}" class="b-50" width="80px" alt="">
                </div>
                <div class="username text-bold mt-3">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </div>
            </div>
        </div>

        <div class="container-item">
            <div class="alert-notification">
                @if (auth()->user()->verify_number == 0 && isset(auth()->user()->phone_number))
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="item d-flex justify-content-between align-items-center">
                                <div class="first d-flex gap-2 align-items-center">
                                    <div class="icon">
                                        <i class='bx bx-envelope font-size-20'></i>
                                    </div>
    
                                    <div class="title">
                                        Vui lòng xác minh số điện thoại của bạn để bảo vệ tài khoản và mua sắn trên hệ thống. <a href="{{ route('verify.phone') }}">Thiết lập ngay</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @elseif(auth()->user()->verify_number == 0 && !isset(auth()->user()->phone_number))

                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="item d-flex justify-content-between align-items-center">
                                <div class="first d-flex gap-2 align-items-center">
                                    <div class="icon">
                                        <i class='bx bx-envelope font-size-20'></i>
                                    </div>
    
                                    <div class="title">
                                        Vui lòng thêm số điện thoại của bạn để bảo vệ tài khoản và mua sắn trên hệ thống. <a href="{{ route('user.view.addPhoneNumber') }}">Thiết lập ngay</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @endif
                
            </div>

            @if (auth()->user()->role == 1)
                <div class=" mb-2">
                    <a class="text-decoration-none text-black p-2 b-2 d-flex justify-content-between hover-color" href="{{ url('admin') }}">
                        <div class="first-item d-flex gap-2  align-items-center">
                            <div class="icon">
                                <i class='bx bx-cog font-size-24 text-success'></i>
                            </div>
                            <div class="title">
                                Quản lý hệ thống
                            </div>
                        </div>
                        <div class="last-item">
                            
                            <div class="icon"><i class='bx bx-chevron-right font-size-24' ></i></div>
                        </div>
                    </a>
                </div>
            @endif
            
            <a href="{{ route('user.viewHistoryOrder') }}" class="text-decoration-none">
                <div class="hitstory-order d-flex justify-content-between hover-color b-2 p-2 mb-2">
                    <div class="first-item d-flex gap-2  align-items-center">
                        <div class="icon">
                            <i class='bx bx-task font-size-24' ></i>
                        </div>
                        <div class="title">
                            Đơn Mua
                        </div>
                    </div>
                    <div class="last-item d-flex gap-2 ">
                        <div class="title text-secondary">
                            Xem lịch sử mua hàng
                        </div>
                        <div class="icon"><i class='bx bx-chevron-right font-size-24' ></i></div>
                    </div>
                </div>
            </a>


            <div class="list-item justify-content-between  d-flex p-2 mb-2">
                <a href="{{ route('user.viewQueueVerify') }}" class="text-decoration-none">
                    <div class="item queue-verify hover-color position-relative p-1 b-2">
                        <div class="icon text-center"><i class='bx bx-wallet font-size-24' ></i></div>
                        <div class="title font-size-14 mt-2">
                            Chờ xác nhận
                        </div>
                        <div class="badge bg-danger position-absolute b-50 top-0 right-0">
                            {{ $queueVerify }}
                        </div>
                    </div>
                </a>
                <a href="{{ route('user.viewQueuePickOrder') }}" class="text-decoration-none">
                    <div class="item hover-color position-relative p-1 b-2">
                        <div class="icon text-center">
                            <i class='bx bx-package font-size-24 text-center'></i>
                        </div>
                        <div class="title font-size-14 mt-2">
                            Chờ lấy hàng
                        </div>
                        <div class="badge bg-danger position-absolute b-50 top-0 right-0">
                            1
                        </div>
                    </div>
                </a>
                <a href="{{ route('user.viewQueueShipping') }}" class="text-decoration-none">
                    <div class="item p-1 hover-color b-2 position-relative p-1 b-2">
                        <div class="icon text-center"><i class='bx bxs-truck font-size-24 text-center' ></i></div>
                        <div class="title font-size-14 mt-2">Chờ giao hàng</div>
    
                        <div class="badge bg-danger position-absolute b-50 top-0 right-0">
                            1
                        </div>
                    </div>
                </a>
                <a href="{{ route('user.viewRatingProduct') }}" class="text-decoration-none">
                    <div class="item hover-color  position-relative p-1 b-2">
                        <div class="icon text-center">
                            <i class='bx bx-star font-size-24 text-center' ></i>
                        </div>
                        <div class="title font-size-14 mt-2">
                            Đánh giá
                        </div>
    
                        <div class="badge bg-danger position-absolute b-50 top-0 right-0">
                            1
                        </div>
                    </div>
                </a>
            </div>

            <a href="{{ route('user.getviewPageFavourite') }}" class="text-decoration-none">
                <div class="farourite p-2 mb-1 d-flex justify-content-between hover-color b-2">
                    <div class="first d-flex align-items-center gap-2">
                        <div class="icon">
                            <i class='bx bx-heart font-size-24 text-danger'></i>
                        </div>
                        <div class="title">
                            Yêu thích
                        </div>
                    </div>
                    <div class="last">
                        <i class='bx bx-chevron-right font-size-24' ></i>
                    </div>
                </div>
            </a>

            <div class="farourite p-2 mb-1 d-flex justify-content-between hover-color b-2">
                <div class="first d-flex align-items-center gap-2">
                    <div class="icon">
                        <i class='bx bx-star font-size-24 text-warning' ></i>
                    </div>
                    <div class="title">
                        Đánh giá của tôi
                    </div>
                </div>
                <div class="last">
                    <i class='bx bx-chevron-right font-size-24' ></i>
                </div>
            </div>


            <a href="{{ route('user.settingAccount') }}" class="text-decoration-none">
                <div class="setting-account d-flex justify-content-between gap-2 p-2 hover-color b-2 mb-1">
                
                    <div class="first d-flex align-items-center gap-2">
                        <div class="icon">
                            <i class='bx bx-user font-size-24' ></i>
                        </div>
                        <div class="title">
                            Thiết lập tài khoản
                        </div>
                    </div>
                    <div class="last">
                        <i class='bx bx-chevron-right font-size-24' ></i>
                    </div>
                </div>
            </a>
            


            <a href="{{ url('/chatify/1') }}" class="text-decoration-none">
                <div class="setting-account d-flex justify-content-between gap-2 p-2 hover-color b-2 mb-1">
                    <div class="first d-flex align-items-center gap-2">
                        <div class="icon">
                            <i class='bx bx-support font-size-24'></i>
                        </div>
                        <div class="title">
                            Trung tâm hỗ trợ 
                        </div>
                    </div>
                    <div class="last">
                        <i class='bx bx-chevron-right font-size-24' ></i>
                    </div>
                </div>
            </a>

            <div class="setting-account d-flex justify-content-between gap-2 p-2 hover-color b-2 mb-1">
                <div class="first d-flex align-items-center gap-2">
                    <div class="icon">
                        <i class='bx bx-book-alt font-size-24'></i>
                    </div>
                    <div class="title">
                        Điều khoản
                    </div>
                </div>
                <div class="last">
                    <i class='bx bx-chevron-right font-size-24' ></i>
                </div>
            </div>


            <div class="setting-account">
                <a href="{{ route('logout') }}" class="text-decoration-none">
                    <div class="first d-flex align-items-center gap-2 justify-content-center bg-danger-subtle p-2 b-2">
                        <div class="icon">
                            <i class='bx bx-log-out font-size-24 text-danger' ></i>
                        </div>
                        <div class="title text-danger ">
                            Đăng xuất
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
