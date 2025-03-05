@extends('admin.layout.app')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-5">
            <span class="text-start">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}">
                        Quản Lý Hệ Thống
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('users.index') }}">
                        Quản Lý Người Dùng
                    </a>
                </span>
            </span>
        </div>

        <div class="card b-2 border-0" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-bold">
                    Danh Sách người dùng
                </h5>
                <div class="menu-action d-flex align-items-center gap-3">
                    <div class="search border-1 border-color p-1 b-3 d-flex align-items-center">
                        <div class="icon mt-1"><i class='bx bx-search-alt-2 font-size-20'></i></div>
                        <div class="input ml-1">
                            <input type="text" class="border-0 outline-0" name="search-user" id="search-user" placeholder="Tìm kiếm người dùng">
                        </div>
                    </div>
                    <div class="d-flex align-items-center hover-color bg-color p-2 b-2 gap-2">
                        <div class="icon">
                            <i class='bx bxs-file-export text-white'></i>
                        </div>
                        <div class="title badge">Báo cáo</div>
                    </div>
                    <div class="filter">
                        <i class='bx bx-filter-alt font-size-24' ></i>
                        
                    </div>

                    
                </div>
            </div>
            <div class="card-body ">
                  <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                            <th scope="col" class="text-white">ID</th>
                             <th scope="col" class="text-white">Họ</th>
                             <th scope="col" class="text-white">Tên</th>
                             <th scope="col" class="text-white">Email</th>
                             <th scope="col" class="text-white">SĐT</th>
                             <th scope="col" class="text-white">Ngày Sinh</th>
                             <th scope="col" class="text-white">Địa Chỉ</th>
                             <th scope="col" class="text-white">Xác Nhận Email</th>
                             <th scope="col" class="text-white">Xác Nhận SĐT</th>
                             <th scope="col" class="text-white">Xu</th>
                             <th scope="col" class="text-white">Vai Trò</th>
                             <th scope="col" class="text-white">Trạng Thái</th>
                             <th scope="col" class="text-white">Ngày Tạo</th>
                             <th scope="col" class="text-white">Hành Động</th>

                          </thead>
                          <tbody class="right" style="display: none">
                          </tbody>
                          <tbody class="told">

                          </tbody>
                    </table>
                  </div>
            </div>
        </div>
    </div>

    <script>

            var page = 1;
            let NoMoreUser = false;
            getUser(true);
            function getUser(NewUser = false){
                if(NewUser){
                    page = 1;
                    NoMoreUser = false;
                }
                if(!NoMoreUser){
                    $.ajax({
                        url: '{{ route('getUser') }}',
                        method: 'GET',
                        data:{
                            page: page,
                        },
                        success:function(res){
                            console.log(res);
                            if(page == 1){
                                if(res.total == 0){

                                }
                                else{
                                    $('.told').html(res.html);
                                }
                            }
                            else{
                                $('.told').append(res.html);
                            }


                            NoMoreUser = page >= res?.last_page;
                            if(!NoMoreUser ){
                                page++;
                            }
                        }
                    })
                }
            }

            var debounceTimer;
            $('#search-user').on('input', function(){
                clearTimeout(debounceTimer);
                var data = $(this).val();
                if(data.length > 0){
                    debounceTimer = setTimeout(function(){
                        if(data.length > 0 ){
                            $('.told').toggle();
                            $.ajax({
                                url: '{{ route('search_user') }}',
                                method: 'GET',
                                data: {
                                    keyword: data, // Điều chỉnh để lấy tham số keyword đúng
                                },
                                success: function(res){

                                    $('.right').html(res.html);
                                    $('.right').toggle();
                                },
                                error: function(err){
                                    console.error(err); // Xử lý lỗi ở đây
                                }
                            });
                        }
                    }, 500);
                }

            });

    </script>

@endsection
