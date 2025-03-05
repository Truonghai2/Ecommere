@extends('welcome')

@section('content')
<div class="container pt-5 pb-5">
    
    <div class="card b-2 border-0 shadow mb-3">
        <div class="card-header bg-white text-center">
            <div class="header clearfix">
                <h3 class="text-muted">Kết quả giao dịch</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive border-0">
                <table class="table">
                    <tr>
                        <th>Mã đơn hàng:</th>
                        <td>{{ $vnp_TxnRef }}</td>
                    </tr>
                    <tr>
                        <th>Số tiền:</th>
                        <td class="text-danger text-bold">@formatPrice($vnp_Amount/100)</td>
                    </tr>
                    <tr>
                        <th>Nội dung thanh toán:</th>
                        <td>{{ $vnp_OrderInfo }}</td>
                    </tr>
                    <tr>
                        <th>Mã GD Tại VNPAY:</th>
                        <td>{{ $vnp_TransactionNo }}</td>
                    </tr>
                    <tr>
                        <th>Mã Ngân hàng:</th>
                        <td>{{ $vnp_BankCode }}</td>
                    </tr>
                    <tr>
                        <th>Thời gian thanh toán:</th>
                        <td>{{ $vnp_PayDate }}</td>
                    </tr>
                    <tr>
                        <th>Kết quả:</th>
                        <td>
                            @if ($vnp_ResponseCode == '00')
                                <span class="badge text-success bg-success-subtle">Giao dịch thành công</span>
                            @else
                                <span class="text-danger badge bg-danger-subtle">Giao dịch thất bại</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    

    <div class="card b-2 border-0 shadow">
        <div class="card-body">
            @if ($vnp_ResponseCode == '00')
                <a href="{{ url('/') }}" class="w-100">
                    <div class="btn btn-primary bg-color border-color w-100 b-2">
                        Về trang chủ
                    </div>
                </a>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ url('/') }}" class="w-100">
                        <div class="btn btn-primary border-label bg-color-2 w-100 b-2 text-black" style="color: black;">Hủy</div>
                    </a>
                    <a href="{{ route('user.viewCart') }}" class="w-100">
                        <div class="btn btn-danger w-100 b-2">Thực hiện lại</div>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection