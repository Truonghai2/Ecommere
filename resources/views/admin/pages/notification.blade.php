@extends('admin.layout.app')

@section('content')

    <div class="container pt-5 pb-5">

        <div class="path pb-5">
            <span class="text-start">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}"  class="text-color">
                        Quản Lý Hệ Thống
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('admin.categories') }}" class="text-color">
                        Quản Lý Thông Báo
                    </a>
                </span>
            </span>
        </div>

        <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header bg-white border-0">
                <div class="row justify-content-between align-items-center">
                    <h4 class="card-title col-lg-5 col-sm-8 text-color text-bold">
                        Danh Sách Thông Báo
                    </h4>
                    <div class="col-lg-5 col-sm-8">
                        <div class="d-flex align-items-center border-1 p-2 b-3 border-color mb-2">
                            <div class="icon"><i class='bx bx-search-alt-2 font-size-20 mt-1 text-color'></i></div>
                            <input type="text" class="border-0 outline-0 ml-2 w-100" name="search-notification" id="search-notification" placeholder="Tìm kiếm">
                        </div>
                        <div class="btn btn-success b-2 d-flex mb-2"  data-bs-toggle="modal" href="#exampleModalToggle" role="button">
                            <div class="icon">
                                <i class="bx bx-plus font-size-24 text-white"></i>
                            </div>
                            <div class="title">
                                <span class="badge">Thêm thông báo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                           <th scope="col" class="text-white">#</th>
                           <th scope="col" class="text-white">ID người dùng</th>
                           <th scope="col" class="text-white">Họ tên</th>
                           <th scope="col" class="text-white">ID sản phẩm</th>
                           <th scope="col" class="text-white">ID danh mục</th>
                           <th scope="col" class="text-white">Kiểu thông báo</th>
                           <th scope="col" class="text-white">Ảnh bìa</th>
                           <th scope="col" class="text-white">Nội dung</th>
                           <th scope="col" class="text-white">Ngày tạo</th>
                           <th scope="col" class="text-white">Hành động</th>
                        </thead>
                        <tbody class="right" style="display: none">
                        </tbody>
                        <tbody class="told">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-5 d-none" id="loading-product-append">
                <div class="loader-item mx-auto"></div>
            </div>
        </div>
    </div>

    <div class="modal fade w-100"  id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content b-2">
            <div class="modal-header">
              <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel" style=" flex:5;">Thêm Danh Mục</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modal-erase-categories" style=" flex:0.2;"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="notification-content" class="font-size-14 text-secondary">Nội dung</label>
                    <textarea name="notification-content" id="notification-content" class="w-100 b-2 outline-0 border-color p-2" cols="30" rows="5" placeholder="Nhập nội dung thông báo"></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="notification-type">Kiểu thông báo</label>
                    <select name="notification-type" class="w-100 border-color outline-0 p-2 b-3" id="notification-type">
                        <option value="">Thông báo đến đối tượng</option>
                        <option value="all">Tất cả người dùng</option>
                        <option value="user">Chỉ định người dùng</option>
                    </select>
                </div >
                <div class="form-group select-user text-wrap mt-2" style="display: none">
                    <div class="title font-size-14 text-secondary">Người dùng</div>
                    <div class="b-3 border-1 w-100 p-2"  id="select-user" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Chọn người dùng </div>
                </div>
                <div class="form-group seleted" style="display: none">
                    <label for="">Người dùng đã chọn</label>
                    <div class="d-flex flex-wrap gap-1" id="add-select-user">

                    </div>
                </div>
            </div>
            <div class="modal-footer p-1">
              <span class="btn btn-secondary b-2" id="modal-erase-categories" data-bs-dismiss="modal" aria-label="Close">Hủy</span>
              <span class="btn btn-primary b-2" id="modal-save-notification">Lưu</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade w-100" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content b-2">
            <div class="modal-header">
                <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabe2" style=" flex:5;">Chọn người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-search-user d-flex p-2 border-1 b-3">
                    <input type="text" class="border-0 outline-0 w-100"  name="search-user" id="search-user" placeholder="Nhập tên người dùng">
                    <div class="icon"><i class='bx bx-search-alt-2 font-size-20 mt-1 text-color'></i></div>
                </div>

                <div class="d-flex flex-wrap mt-3 gap-1" id="add-checkbox">

                </div>
            </div>

            <div class="modal-footer">
                <span class="btn btn-secondary b-2" id="erase-select" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Hủy</span>
                <button class="btn btn-primary b-2" id="save-select" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Ok</button>
            </div>
          </div>
        </div>
      </div>


      <script>
            let userObject = {};
            $('#notification-type').on('change', function(){
                var data = $(this).val();

                if(data == 'user'){
                    $('.select-user').css('display', 'block')
                }
                else{
                    $('.select-user').css('display', 'none')

                }
            })

            $('#select-user').click(function(){
                getUser(true);
            })

            var array = [];
            var page = 1;
            let noMoreUser = false;
            let loading = false;
            function getUser(newPage = false){
                if(newPage){
                    page = 1;
                    noMoreUser = false;
                    oject = {};
                    loading = false;
                }
                if(!noMoreUser && !loading){
                    loading = true;
                    $('#loading-product-append').removeClass('d-none');
                    $.ajax({
                        url:'{{ route('admin.selectUser') }}',
                        method: "GET",
                        data:{
                            page: page,
                        },
                        success:function(res){
                            console.log(res);
                            if(page == 1){
                                var html = ''
                                res.data.forEach(item => {
                                    html += renderUser(item)
                                    userObject[item.id] = item;
                                });
                                $("#add-checkbox").html(html);

                            }
                            else{

                                var html = ''
                                res.data.forEach(item => {
                                    html += renderUser(item)
                                    userObject[item.id] = item;
                                });
                                $("#add-checkbox").append(html);
                            }

                            noMoreUser = page >= res?.last_page;
                            if(!noMoreUser){
                                page++;
                            }
                            loading = false;
                            $('#loading-product-append').addClass('d-none');
                        }
                    })
                }
            }

            function renderUser(item) {
                var content = '';
                if(array.length > 0){
                    array.forEach(element => {
                        if(item.id == element){
                            content = 'checked';
                        }
                    });
                }
                var html = `<div class="block-checkbox">
                        <input type="checkbox" name="check-box-user" ${content} id="${item.id}" value="${item.id}">
                        <label for="${item.id}">${item.first_name} ${item.last_name} - ${item.id}</label>
                    </div>`;

                return html;
            }


            $(document).on('click', 'input[name="check-box-user"]', function() {
                // Lấy giá trị của checkbox hiện tại
                var value = $(this).val();

                if ($(this).is(':checked')) {
                    // Nếu checkbox được chọn, thêm giá trị vào mảng
                    array.push(value);
                } else {
                    // Nếu checkbox không được chọn, loại bỏ giá trị khỏi mảng
                    var index = array.indexOf(value);
                    if (index > -1) {
                        array.splice(index, 1);
                    }
                }

                // Kiểm tra kết quả của mảng
                console.log(array);
            });

            $('#erase-select').click(function(){
                array = [];
                $('#add-select-user').html("");
                $('.seleted').css('display', 'none');
            })

            $('#save-select').click(function(){
                if(array.length > 0){
                    $('.seleted').css('display', 'block');
                    renderSelect();
                }

            })


            function renderSelect(){
                var html = '';
                array.forEach(element => {
                    var item = userObject[element];
                    html += `<div class="block-checkbox d-flex font-size-14 gap-1 b-3 bg-color-2 p-1">
                            <div class="username">
                                ${item.first_name} ${item.last_name}
                            </div>
                            <div class=""> - </div>
                            <div class="id">${item.id}</div>
                            <div class="btn-close font-size-14" id="erase-selet-array" data-id="${item.id}"></div>
                        </div>`;
                });
                $('#add-select-user').html(html);
            }


            $(document).on('click', '#erase-selet-array', function(){
                var id = $(this).data('id').toString();

                var index = array.indexOf(id);
                if (index > -1) {
                    array.splice(index, 1);
                }



                $(this).closest('.block-checkbox').remove();
                if(array.length === 0){
                    $('.seleted').css('display', 'none');

                }
            })


            $('#modal-save-notification').click(function(){
                var text = $('#notification-content').val();
                var type = $('#notification-type').val();
                if(type == 'user'){
                    if(array.length == 0 || text.length == 0){
                        return;
                    }
                }
                else{
                    if(text.length == 0){
                        return;
                    }
                }

                $('#modal-erase-categories').click();
                $.ajax({
                        url: '{{ route('addmin.addNotification') }}',
                        method: "POST",
                        data:{
                            content : text,
                            type: type,
                            array: array,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function(res){
                            console.log(res);
                        }
                    })
            })



            let PageNotifcation = 1;
            let noMoreNotification = false;
            getNotification(true);
            function getNotification(newPage = false){
                if(newPage){
                    PageNotifcation = 1;
                    noMoreNotification = false;
                    loading = false;
                }
                if(!noMoreNotification && !loading){
                    loading = true;
                    $('#loading-product-append').removeClass('d-none');
                    $.ajax({
                        url:'{{ route('admin.getNotification') }}',
                        method: "GET",
                        data:{
                            page: PageNotifcation,
                        },
                        success:function(res){
                            if(PageNotifcation == 1){
                                $('.told').html(res.html);
                            }
                            else{
                                $('.told').append(res.html);
                            }
                            noMoreNotification = PageNotifcation >= res?.last_page;

                            if(!noMoreNotification){
                                PageNotifcation++;
                            }

                            loading = false;
                            $('#loading-product-append').addClass('d-none');
                        }
                    })
                }
            }

            actionOnScrollBottom(window, function(){
                getNotification();
            });


            $(document).on('click', '#btn-remove-notification', function(){
                var id = $(this).data('notification-id');
            })

      </script>


@endsection
