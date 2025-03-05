
<footer style="background-color: #101218;" class="{{ 
Route::currentRouteName() === 'user.information' 
|| Route::currentRouteName() === 'user.notificationUser' || Route::currentRouteName() === "user.viewCart" 
|| Route::currentRouteName() === "user.viewPay" || Route::currentRouteName() == 'user.getviewPageFavourite'
|| Route::currentRouteName() == 'user.viewQueueVerify' || Route::currentRouteName() == 'user.viewQueuePickOrder' 
|| Route::currentRouteName() === 'user.viewQueueShipping' || Route::currentRouteName() == 'user.viewHistoryOrder' ? 'd-none' : '' 

}}" id="footer-page">
    <div class="container p-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="row justify-content-between">
                    <div class="col-lg-2 col-sm-8 text-center">
                        <img width="100px" src="{{ asset('img/logo.png') }}" alt="">
                    </div>

                    <div class="col-lg-3 col-sm-8">
                        <div class="title position-relative">
                            <h3 class="badge text-bold font-size-24">Liên Hệ</h3>
                            <div class="list-item d-flex flex-column" >
                                <div class="text-start badge list-unstyled font-size-14 text-wrap">
                                    SĐT:
                                    <a href="tel:+84344885035" class="text-decoration-none hover-underline text-color">(+84) 344 88 5035</a>
                                </div>
                                <div class="text-start badge font-size-14 text-wrap">
                                    Email: 
                                    <a href="mailto:truonghai16122002@gmail.com" class="text-decoration-none hover-underline text-color">
                                        truonghai16122002@gmail.com
                                    </a>
                                </div>

                                <div class=" text-start address badge font-size-16 text-wrap">
                                    Đ/c:
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-8">
                        <div class="title position-relative">
                            <h3 class="badge text-bold font-size-24">Trợ Giúp</h3>
                            <div class="list-item d-flex flex-column" >
                                <div class="text-start badge list-unstyled font-size-16 text-wrap hover-underline">
                                    <a href="{{ url('/chatify/1') }}" class="text-white text-decoration-none">
                                        Nhắn tin với nhân viên tư vấn
                                    </a>
                                </div>
                                <div class="text-start badge font-size-16 text-wrap hover-underline">
                                    Báo lỗi
                                </div>

                                <div class=" text-start address badge font-size-16 text-wrap">
                                    Đ/c:
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-8">
                        <div class="title position-relative">
                            <h3 class="badge text-bold font-size-24">Thông Tin Về Chúng Tôi</h3>
                            <div class="list-item d-flex flex-column" >
                                <div class="text-start badge list-unstyled font-size-16 text-wrap" >
                                    SĐT:
                                    <a href="tel:+84344885035" class="text-decoration-none hover-underline text-color">(+84) 344 88 5035</a>
                                </div>
                                <div class="text-start badge font-size-14 text-wrap">
                                    Email: 
                                    <a href="mailto:truonghai16122002@gmail.com" class="text-decoration-none hover-underline text-color">
                                        truonghai16122002@gmail.com
                                    </a>
                                </div>

                                <div class=" text-start address badge font-size-14 text-wrap">
                                    Đ/c:
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-8 p-2 mt-4" style="border-top:1px solid #ccc;">
                        <div class="item d-flex justify-content-between">
                            <span class="badge text-end">
                                Bản quyền © {{ date('Y') }} Trương Tuấn Hải
                            </span>
                            <span class="payment d-none">
                                <img src="https://websitedemos.net/home-garden-decor-02/wp-content/uploads/sites/1034/2022/02/payment-image.png" alt="">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@if ( Route::currentRouteName() === 'user.information' || 
Route::currentRouteName() === 'user.notificationUser' || Route::currentRouteName() === "user.viewCart" 
|| Route::currentRouteName() === "user.viewPay" || Route::currentRouteName() == 'user.getviewPageFavourite' 
|| Route::currentRouteName() == 'user.viewQueueVerify' || Route::currentRouteName() == 'user.viewQueuePickOrder' 
|| Route::currentRouteName() === 'user.viewQueueShipping' || Route::currentRouteName() == 'user.viewHistoryOrder')
<footer class="pb-3">
    <div class="container mt-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="row justify-content-between">
                    <div class="col-lg-12 col-sm-8 ">
                        <div class="item text-center text-black">
                            <span class="text-center text-secondary">
                                Bản quyền © {{ date('Y') }} Trương Tuấn Hải
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@endif