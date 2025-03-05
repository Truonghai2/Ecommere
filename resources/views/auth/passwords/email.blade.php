@extends('welcome')

@section('content')
<div class="container" style="padding: 14.3rem 0 14.3rem 0">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 b-2 shadow" >
                <h4 class="text-center">Lấy lại mật khẩu</h4>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label font-size-14 text-secondary">Email:</label>

                            <div class="col-md-6 w-100">
                                <input id="email" type="email" class="form-control border-color b-3 w-100 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Nhập email của bạn">

                                @error('email')
                                    <span class="invalid-feedback text-center" role="alert">
                                        <strong class="text-center text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 w-100">
                                <button type="submit" class="btn btn-primary w-100 border-color bg-color b-2">
                                   Gửi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
