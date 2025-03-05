@extends('welcome')

@section('content')

    <div class="container pt-5 pb-5">

        <div class="card border-0 shadow b-2">
            <div class="card-header text-center bg-white">
                <h5>Thêm số điện thoại</h5>
            </div>


            <div class="card-body">
                @if ($errors->any())
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                <form action="{{ route('user.post.PhoneNumber') }}" method="POST">
                    @csrf
                    <label for="phone_number" class="font-size-14 text-secondary">Số điện thoại:</label>
                    <input type="text" id="phone_number" class="w-100 border-color outline-0 p-2 b-2 mb-3" name="phone" autocomplete="off" placeholder="Nhập số điện thoại" required>
                    
                    
                    <button type="submit" class="btn btn-primary border-color bg-color b-2 w-100">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
@endsection