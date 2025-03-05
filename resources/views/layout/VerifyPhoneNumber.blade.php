@extends('welcome')

@section('content')

    <div class="container pt-5 pb-5">

        <div class="card border-0 shadow b-2">
            <div class="card-header text-center bg-white">
                <h5>Xác minh số điện thoại</h5>
            </div>


            <div class="card-body">
                @if ($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                <form action="{{ route('verify.phone.submit') }}" method="POST">
                    @csrf
                    <label for="verification_code">
                        <div class="content mb-2">
                            <span class="text-wrap">
                                Mã xác minh số điện thoại của bạn đã được gửi về số điện thoại của bạn. Nếu không nhận được hãy ấn gửi lại. Bạn có tối đa 1 phút để nhập mã xác nhận.
                            </span>
                        </div>
                    </label>
                    <input type="text" id="verification_code" class="w-100 border-color outline-0 p-2 b-2 mb-3" name="verification_code" autocomplete="off" placeholder="Nhập mã xác nhận" required>
                    <div class="menu d-flex justify-content-between ">
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
                    <div class="text-color text-bold text-center hover-underline mb-2">
                        Đây không phải số của bạn?
                    </div>
                    <button type="submit" class="btn btn-primary border-color bg-color b-2 w-100">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>

    <script>

        time();
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
@endsection