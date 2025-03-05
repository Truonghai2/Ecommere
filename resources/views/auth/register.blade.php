@extends('welcome')

@section('register')
<div class="container" style="padding: 4.25rem 0 4.25rem 0">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);">
                <div class="card-header bg-white">
                    <h4 class="card-title text-center">
                        Đăng Ký
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="form-group d-flex align-items-center mb-2">
                            <div class="first_name w-100">
                                <label for="first_name" class="font-size-14 text-secondary">Họ:</label>
                                <input class="w-100 b-3 border-color p-2" type="text" name="first_name" id="first_name" placeholder="Họ" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="last_name w-100 ml-1">
                                <label for="last_name" class="font-size-14 text-secondary">Tên:</label>
                                <input class="w-100 b-3 border-color p-2" type="text" name="last_name" id="last_name" placeholder="Tên" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    
                        <div>
                            <label for="type" class="font-size-14 text-secondary">Đăng ký bằng:</label>
                            <select name="type" id="type" class="w-100 border-color b-3 p-2 outline-0" required>
                                <option value="">Chọn kiểu</option>
                                <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Email</option>
                                <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Số điện thoại</option>
                            </select>
                            @error('type')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="form-group text-wrap mb-2 {{ old('type') == 1 ? '' : 'd-none' }}" id="display-email">
                            <label for="email" class="font-size-14 text-secondary">Email:</label>
                            <input class="w-100 b-3 border-color p-2 outline-0" autocomplete="off" type="email" name="email" id="email" placeholder="Nhập email của bạn" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="form-group text-wrap mb-2 {{ old('type') == 2 ? '' : 'd-none' }}" id="display-phone-number">
                            <label for="phone-number" class="font-size-14 text-secondary">Số điện thoại:</label>
                            <input class="w-100 b-3 border-color p-2 outline-0" autocomplete="off" type="text" name="phone_number" id="phone_number" placeholder="Nhập số điện thoại của bạn" value="{{ old('phone_number') }}">
                            @error('phone-number')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="form-group text-wrap">
                            <label for="password" class="font-size-14 text-secondary">Mật khẩu:</label>
                            <input class="w-100 b-3 border-color p-2 outline-0" autocomplete="off" type="password" name="password" id="password" placeholder="Nhập mật khẩu của bạn">
                            @error('password')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="form-group">
                            <label for="gender" class="font-size-14 text-secondary">Giới tính:</label>
                            <div class="item gender d-flex justify-content-center">
                                <input type="radio" name="gender" id="gender-male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                <label for="gender-male" class="b-2  p-2">Nam</label>
                                <input type="radio" name="gender" id="gender-female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <label for="gender-female" class="b-2  p-2">Nữ</label>
                            </div>
                            @error('gender')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="birthday">
                            <label for="birthday" class="font-size-14 text-secondary">Ngày sinh:</label>
                            <div class="item d-flex align-items-center gap-2 text-center justify-content-center">
                                <div class="date">
                                    <select name="date" id="date" class="b-2 border-color p-2">
                                        <option value="">Ngày</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}" {{ old('date') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="month">
                                    <select name="month" id="month" class="b-2 border-color p-2">
                                        <option value="">Tháng</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('month') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="year">
                                    <select name="year" id="year" class="b-2 border-color p-2">
                                        <option value="">Năm</option>
                                        @for ($i = 1950; $i <= 2024; $i++)
                                            <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            @error('date')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                            @error('month')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                            @error('year')
                                <div class="text-danger font-size-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group footer mt-2">
                            <button class="btn btn-primary w-100 b-2 bg-color border-color" type="submit">Đăng Ký</button>
                        </div>
                    
                        <div class="form-group text-center mt-2">
                            Nếu bạn đã có tài khoản thì nhấn vào đây! <a class="text-decoration-none text-primary hover-underline" href="{{ url('/login') }}">Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('select[name="type"]').change(function(){
        var val = $(this).val();
        $("input[name='email']").val('');
        $("input[name='phone_number']").val('');
        if(val){
            if(val == 1){
                $('#display-email').removeClass('d-none');
                $('#display-phone-number').addClass('d-none');
                $("input[name='phone_number']").val('');
            }
            else{
                $('#display-email').addClass('d-none');
                $('#display-phone-number').removeClass('d-none');
                $("input[name='email']").val('');
            }
        }

        else{
            $('#display-email').addClass('d-none');
            $('#display-phone-number').addClass('d-none');
        }
    })
</script>
@endsection
