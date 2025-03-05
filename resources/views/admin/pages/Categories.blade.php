@extends('admin.layout.app')


@section('content')
<div class="modal fade w-100"  id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content b-2">
        <div class="modal-header">
          <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel" style=" flex:5;">Thêm Danh Mục</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modal-erase-categories" style=" flex:0.2;"></button>
        </div>
        <div class="modal-body">
            <div class="img-preview w-100 pb-3" id="preview" >
                
            </div>
            <div class="btn-add-img border-1 b-2 p-2" id="add-img-categories" style="cursor: pointer">
                <div class="icon text-center">
                    <i class="bx bx-plus font-size-24"></i>
                </div>
                <div class="title text-center">
                    <span class="text-center text-secondary">
                        Thêm ảnh danh mục
                    </span>
                </div>
            </div>
            <input type="file" name="img-categories" accept="" id="img-categories" hidden>

            <div class="name-categories mt-3 w-100 text-center">
                <textarea name="name-categories" class="b-2 w-100 text-center" id="name-categories" style="height: 100px;" cols="30" rows="10" placeholder="Nhập tên danh mục" required></textarea>
            </div>
            <div class="alert-danger p-2 text-center b-2" id="warning-categories" style="display: none;">
                <span class="text-danger text-center">
                    Vui lòng điền đầy đủ thông tin!
                </span>
            </div>
        </div>
        <div class="modal-footer p-1">
          <span class="btn btn-secondary b-2" id="modal-erase-categories" data-bs-dismiss="modal" aria-label="Close">Hủy</span>
          <span class="btn btn-primary b-2" id="modal-save-categories">Lưu</span>
        </div>
      </div>
    </div>
  </div>
    <div class="container pt-5 pb-5">
        <div class="path pb-5">
            <span class="text-start">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}" class="text-color">
                        Quản Lý Hệ Thống
                    </a>
                </span> / 
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('admin.categories') }}" class="text-color">
                        Quản Lý Danh Mục
                    </a>
                </span>
            </span>
        </div>
        
        <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header border-0 bg-white d-flex align-items-center justify-content-between">
                
                <div class="title d-flex align-items-center gap-3">
                    <a href="{{ route('admin.categories') }}">
                        <h5 class="card-title text-bold {{ (Route::currentRouteName() == "admin.categories" ? 'active' : '') }}">
                            Quản Lý Danh Mục
                        </h5>
                    </a>
                    <a href="{{ route('admin.thumbnailCategory') }}">
                        <h5 class="card-title text-bold {{ (Route::currentRouteName() == "admin.thumbnailCategory" ? 'active' : '') }}">
                            Nội Dung Danh Mục
                        </h5>
                    </a>
                </div>
                

                <div class="item">
                    <div class="btn btn-success d-flex align-items-center b-2" data-bs-toggle="modal" href="#exampleModalToggle" role="button">
                        <div class="icon">
                            <i class='bx bx-plus text-white mt-1 font-size-20' ></i>
                        </div>
                        <div class="title">
                            <span class="title badge">Thêm Danh Mục</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                            <th scope="col" class="text-white">#</th>
                
                             <th scope="col" class="text-white">Ảnh</th>
                             <th scope="col" class="text-white">Tên danh mục</th>
                             <th scope="col" class="text-white">slug</th>
                             <th scope="col" class="text-white">Hiện thị</th>
                             <th scope="col" class="text-white">Ngày tạo</th>
                             <th scope="col" class="text-white">Hành Động</th>
                          </thead>
                          <tbody class="right" style="display: none">
                          </tbody>
                          <tbody class="told">
                              @isset($categories)
                                @foreach ($categories as $key => $item)
                                    <tr data-category-id="{{ $item->id }}">
                                    <th scope="row">
                                        <span class="badge text-success bg-success-subtle">{{ $key + 1 }}</span>
                                      </th>
                                      <td>
                                        <img width="100px" class="image-product b-2" src="{{ $item->thumbnail }}" alt="">
                                      </td>
                                      <td>
                                        <span class="">
                                            {{ $item->name }}
                                        </span>
                                      </td>
                                      <td>
                                        {{ $item->slug }}
                                      </td>
                                      <td class="{{ $item->hidden == 0 ? "text-success bg-success-subtle badge" : "text-danger bg-danger-subtle badge" }}">{{ $item->hidden == 0 ? "
                                        Có 
                                      " : "
                                        Không
                                      " }}</td>
                                      <td>
                                        <span class="">
                                            @datetime($item->created_at)
                                        </span>
                                      </td>
                                      <td>
                                        <span class="btn btn-primary b-2 mb-1" data-category-id="{{ $item->id }}"><i class='bx bx-edit-alt text-white' ></i></span>
                                        <span class="btn btn-danger b-2 mb-1" id="remove-category" data-bs-toggle="modal" href="#confirm-remove" role="button" data-category-id="{{ $item->id }}"><i class='bx bxs-trash text-white' ></i></span>
                                      </td>
                                  </tr> 
                                @endforeach
                                  @else
                                <tr>
                                    <td><span class="text-center text-secondary">Hiện chưa có danh mục nào.</span></td>
                                </tr>
                              @endisset
                          </tbody>
                    </table>
                  </div>
            </div>
        </div>
        
    </div>

    <div class="modal fade w-100"  id="confirm-remove" aria-hidden="true" aria-labelledby="confirm-removeToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content b-2">
            <div class="modal-header">
              <h5 class="modal-title text-center text-bold" id="confirm-removeToggleLabel" style=" flex:5;">Xác nhận xóa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modal-erase-categories" style=" flex:0.2;"></button>
            </div>
            <div class="modal-body">
                <span>Bạn có chắc chắn muốn xóa danh mục này không? Nếu đồng ý thì hành động này không thế hoàn tác lại.</span>
            </div>
            <div class="modal-footer p-1">
                <div class="d-flex gap-2 w-100">
                    <div class="btn btn-default bg-color-2 b-2 w-100 hover-color" class="cancel" style="color:black;" data-bs-dismiss="modal"
                    aria-label="Close">Hủy</div>
                    <div class="btn btn-danger b-2 w-100" id="confirm" data-bs-dismiss="modal"
                    aria-label="Close">Lưu</div>
                </div>
            </div>
          </div>
        </div>
      </div>

    <script>

        $('#add-img-categories').click(function(){
            
            $('#img-categories').click();


        })

        $('#img-categories').on('change', function(){
            var file = this.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Hiển thị preview ảnh
                    $('#preview').html('<img src="'+ e.target.result +'" class="b-2 image-product" id="image-preview" width="100%"/>');
                }
                reader.readAsDataURL(file);

            }
        });
        $('#modal-erase-categories').click(function(){
            $('#image-preview').remove();
            $('#name-categories').val("");
        });

        
            $('#modal-save-categories').click(function(){
                var img = $('#image-preview').attr('src');
                var text = $('#name-categories').val();

                if((img && img.length > 0) || (text && text.length > 0)){
                    $('#modal-erase-categories').click();
                    $('#image-preview').remove();
                    $('#name-categories').val("");


                    $.ajax({
                        url: '{{ route('addcategory') }}',
                        method: 'POST',
                        data:{
                            image : img,
                            name: text,
                            _token: '{{ csrf_token() }}'
                        },
                        success:function(res){
                            console.log(res);
                        }
                    })
                } else {
                    $('#warning-categories').toggle();
                }
            });
    
        $(document).on('click', '#remove-category', function(){
            var id = $(this).data('category-id');

            $.ajax({
                url:'{{ route('admin.removeCategory') }}',
                method: "POST",
                data:{
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success:function(res){
                    $(this).closest('tr').remove();
                }
            })
        })

        var category_id = undefined;


        $('#remove-category').click(function(){
            category_id = $(this).data('category-id');

        })

        $('#confirm').click(function(){
            var $button = $(this); // Capture the reference to the button

            if(category_id){
                $.ajax({
                    url: "{{ route('admin.post.removeCategory') }}",
                    method: "POST",
                    data:{
                        category_id: category_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function(res){
                        // Use the captured reference to find and remove the row
                        $button.closest('tr').remove();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error); // Handle errors if needed
                    }
                });
            }
        });

    </script>

   
@endsection