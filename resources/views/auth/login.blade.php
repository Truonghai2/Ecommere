@extends('welcome')

@section('login')
<div class="container " style="padding: 8.17rem 0 8.17rem 0;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card b-2 border-0" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);">
                <div class="card-header bg-white">
                    <h4 class="card-title text-center">
                        Đăng Nhập
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-2 text-wrap">
                            <label for="email" class="col-md-4 col-form-label font-size-14 text-secondary">Tài khoản:</label>

                            <div class="col-md-5 w-100">
                                <input id="email" type="text" class="form-control b-3 border-color @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Nhập email của bạn">

                                @error('email')
                                    <span class="invalid-feedback text-center" role="alert">
                                        <strong class="text-center text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="password" class="col-md-4 col-form-label font-size-14 text-secondary">Mật khẩu</label>

                            <div class="col-md-6 w-100">
                                <input id="password" type="password" class="form-control b-3 border-color outline-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu của bạn">

                                @error('password')
                                    <span class="invalid-feedback text-center" role="alert">
                                        <strong class="text-center text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6 ">
                                <div class="form-check text-start">
                                    <input class="form-check-input d-block" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label " for="remember">
                                        Nhớ đăng nhập lần sau.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 w-100">
                                <button type="submit" class="btn btn-primary border-color bg-color w-100 b-2">
                                    Đăng Nhập
                                </button>
                            </div>
                        </div>
                        <div class="restPassword text-center">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none hover-underline text-black" href="{{ route('password.request') }}">
                                    Quên mật khẩu?
                                </a>
                            @endif
                        </div>
                        <div class="form-group text-center mt-2"> 
                            Nếu bạn chưa có tài khoản thì nhấm vào đấy! <a class="text-decoration-none text-primary hover-underline" href="{{ url('/register') }}">Đăng ký</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
