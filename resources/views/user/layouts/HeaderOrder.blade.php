@if ($style == 'order')
<div class="header-layout container position-sticky bg-white"  style="top: 55px; z-index:9;">
    <div class="container-content ">
        <div class="items d-flex justify-content-between tabs-header">
            
            <a href="{{ route('user.viewQueueVerify') }}" class="text-decoration-none">
                <div class="p-2 hover-color b-2 font-size-12 text-secondary {{ Route::currentRouteName() === 'user.viewQueueVerify' ? 'active' : '' }}">
                    Chờ thanh toán
                </div>
            </a>

            <a href="{{ route('user.viewQueuePickOrder') }}" class="text-decoration-none">
                <div class="p-2 hover-color b-2 font-size-12 text-secondary {{ Route::currentRouteName() === 'user.viewQueuePickOrder' ? 'active' : '' }}">Chờ lấy hàng</div>
            </a>

            <a href="{{ route('user.viewQueueShipping') }}" class="text-decoration-none">
                <div class="p-2 hover-color b-2 font-size-12 text-secondary {{ Route::currentRouteName() === 'user.viewQueueShipping' ? 'active' : '' }}">Chờ giao hàng</div>
            </a>
            
            
            <a href="{{ route('user.viewHistoryOrder') }}" class="text-decoration-none">
                <div class="p-2 hover-color b-2 font-size-12 text-secondary {{ Route::currentRouteName() === 'user.viewHistoryOrder' ? 'active' : '' }}">Đã mua hàng</div>
            </a>
            
        </div>
        
    </div>
</div>
@endif