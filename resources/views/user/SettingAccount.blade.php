@extends('welcome')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-3 text-color" >
            <a href="{{ url('/') }}" class="text-decoration-none">
                <span class=" text-color">Trang chủ /</span>
            </a>
            <a href="{{ route('user.information') }}" class="text-decoration-none">
                <span class=" text-color">Trang cá nhân /</span>
            </a>
            <a href="{{ route('user.getviewPageFavourite') }}" class="text-decoration-none">
                <span class=" text-color">Thiết lập tài khoản</span>
            </a>
        </div>
        <div class="container-items">
            <div class="card b-2 shadow border-0 mb-3">
                <div class="card-header bg-white border-0">
                    <div class="card-title">
                        <h5>Thông tin cá nhân</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="item">
                        <label for="user-first-name" class="font-size-14 text-secondary">
                            Họ:
                        </label>
                        <input type="text" class="border-color w-100 outline-0 p-2 b-2 input-information" disabled value="{{ auth()->user()->first_name }}" name="user-first-name" id="user-first-name" >
                    </div>
                    <div class="item">
                        <label for="user-last-name" class="font-size-14 text-secondary">
                            Tên:
                        </label>
                        <input type="text" class="border-color w-100 outline-0 p-2 b-2 input-information" disabled value="{{ auth()->user()->last_name }}" name="user-last-name" id="user-last-name" >
                    </div>

                    <div class="item">
                        <label for="user-email" class="font-size-14 text-secondary">
                            Email:
                        </label>
                        <input type="text" class="border-color w-100 outline-0 p-2 b-2 input-information" disabled value="{{ auth()->user()->email }}" name="user-email" id="user-email" >
                    </div>

                    <div class="item mb-2">
                        <label for="user-phone" class="font-size-14 text-secondary">
                            Số điện thoại:
                        </label>
                        <input type="text" class="border-color w-100 outline-0 p-2 b-2 input-information" disabled value="{{ auth()->user()->phone_number }}" name="user-phone" id="user-phone" >
                    </div>

                    <div class="item btn-edit">
                        <button class="btn btn-primary bg-color border-color w-100 b-2">Chỉnh sửa thông tin</button>
                    </div>
                    <div class="item menu-btn d-none">
                        <button id="btn-cancel-edit" class="btn btn-secondary w-100 mb-2 b-2 bg-color-2" style="color:black;">Hủy</button>
                        <button id="btn-save-edit" class="btn btn-danger w-100 b-2 ">Lưu</button>
                    </div>
                </div>
            </div>



            <div class="card b-2 border-0 shadow mb-3">
                <div class="card-header bg-white border-0">
                    <div class="card-title">
                        <h5>Địa chỉ</h5>
                    </div>
                </div>
                <div class="card-body">
                    @foreach (auth()->user()->getAddress as $item)
                        <div class="items p-2 border-label b-2 mb-2">

                            <span>{{ $item->home_number }}, {{ $item->ward_name }}, {{ $item->district_name }}, {{ $item->provinces_name }}</span>
                            @if ($item->active == 1)
                                <div class="p-2 bg-danger badge">Mặc định</div>
                            @endif
                        </div>
                    @endforeach

                    <div class="item">
                        <button class="btn btn-primary bg-color border-color w-100 b-2">Thêm địa chỉ</button>
                    </div>
                </div>
            </div>


            <div class="card b-2 border-0 shadow">
                <div class="card-body">
                    <div class="card-title">
                        <h5>Đổi mật khẩu</h5>
                    </div>

                    <div class="item">
                        <button class="btn btn-primary bg-color border-color w-100 b-2">Đổi mật khẩu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error-message" class="alert alert-danger bg-danger badge text-white b-3" style="display:none; position: fixed; top: 50%; left:50%; z-index: 1003; transform: translate(-50%, -50%);"></div>

    <script>
        $('.btn-edit').click(function(){
            $('.input-information').prop('disabled', false);
            $(this).addClass('d-none');
            $('.menu-btn').removeClass('d-none');
        });

        $('#btn-cancel-edit').click(function(){
            var data = defaultData();

            setItemInformation(data);
            $('.btn-edit').removeClass('d-none');
            $('.menu-btn').addClass('d-none');
        })

        $('#btn-save-edit').click(function(){
            var data = getItemInformation();
            var dataDefault = defaultData();

            if (areObjectsEqual(data, dataDefault)) {
                showError('Vui lòng thay đổi giá trị trước khi lưu dữ liệu!')
                return;
            }

            $.ajax({
                url: '{{ route('user.editInformation') }}',
                method: "POST",
                data:{
                    data: data,
                    _token: '{{ csrf_token() }}',
                },
                success:function(res){
                    setItemInformation(data);
                }
            })
        })

        function defaultData(){
            return {
                'firstname' : '{{ auth()->user()->first_name }}',
                'lastname' : '{{ auth()->user()->last_name }}',
                'email' : '{{ auth()->user()->email }}',
                'phone' : '{{ auth()->user()->phone_number }}',
            };
        }

        function setItemInformation(data){
            $("#user-first-name").val(data.firstname);
            $('#user-last-name').val(data.lastname);
            $('#user-email').val(data.email);
            $('#user-phone').val(data.phone);
        }

        function getItemInformation(){
            return {
                'firstName': $("#user-first-name").val(),
                'lastName': $('#user-last-name').val(),
                'email': $('#user-email').val(),
                'phone': $('#user-phone').val()
            };
        }

        function showError(message) {
            var errorMessage = $('#error-message');
            errorMessage.text(message);
            errorMessage.show();

            setTimeout(function() {
                errorMessage.hide();
            }, 3000); // Ẩn thông báo sau 3 giây
        }

        function normalizeKeys(obj) {
            const normalized = {};
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    normalized[key.toLowerCase()] = obj[key];
                }
            }
            return normalized;
        }

        function areObjectsEqual(obj1, obj2) {
            const normalizedObj1 = normalizeKeys(obj1);
            const normalizedObj2 = normalizeKeys(obj2);
            return JSON.stringify(normalizedObj1) === JSON.stringify(normalizedObj2);
        }
    </script>
@endsection