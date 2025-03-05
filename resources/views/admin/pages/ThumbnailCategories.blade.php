@extends('admin.layout.app')



@section('content')
<div class="modal fade w-100"  id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content b-2">
        <div class="modal-header">
          <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel" style=" flex:5;">Thêm Nội Dung Danh Mục</h5>
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
                        Thêm ảnh nội dung danh mục
                    </span>
                </div>
            </div>
            <input type="file" name="img-categories" accept="" id="img-categories" hidden>


            <div class="title">
                <label for="title-thumbnail-category" class="text-secondary font-size-14">Tiêu đề</label>
                <input type="text" name="title-thumbnail-category" class="w-100 border-color b-2 outline-0 p-2" placeholder="Nhập tiêu đề nội dung" id="title-thumbnail-category">
            </div>
            <div class="name-categories w-100 ">
                <label for="name-categories" class="text-secondary font-size-14">Nội dung</label>
                <textarea name="name-categories" class="b-2 w-100  border-color outline-0 p-2" id="name-categories" style="height: 100px;" cols="30" rows="10" placeholder="Nhập tên danh mục" required></textarea>
            </div>
            <div class="type-thumbnail-categories">
                <label for="type-thumbnail" class="font-size-14 text-secondary">Kiểu hiển thị nội dung</label>
                <select name="type-thumbnail" id="type-thumbnail" class="b-2 border-color w-100 p-2 outline-0">
                    <option value="">Chọn kiểu hiển thị</option>
                    <option value="1">Trái qua phải</option>
                    <option value="2">Phải qua trái</option>
                    <option value="3">Trên xuống</option>
                    <option value="4">Dưới lên</option>
                </select>
            </div>
            <div class="selection-category">
                <label for="category_id" class="font-size-14 text-secondary">Chọn danh mục</label>
                <select name="categoey_id" id="category_id" class="b-2 border-color w-100 p-2 outline-0">
                    <option value="">Chọn danh mục</option>
                    @foreach ($category as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
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
                    <a href="{{ route('admin.thumbnailCategory') }}" class="text-color">
                        Nội Dung Danh Mục
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
                            <span class="title badge">Thêm Nội Dung Danh Mục</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                            <th scope="col" class="text-white">#</th>
                            <th scope="col" class="text-white">Thuộc danh mục</th>
                             <th scope="col" class="text-white">Ảnh</th>
                             <th scope="col" class="text-white">Tên danh mục</th>
                             <th scope="col" class="text-white">Nội dung</th>
                             <th scope="col" class="text-white">Kiểu hiển thị</th>
                             <th scope="col" class="text-white">Ngày tạo</th>
                             <th scope="col" class="text-white">Hành Động</th>
                          </thead>
                          <tbody class="right" style="display: none">
                          </tbody>
                          <tbody class="told">
                              @if(count($categories) > 0)
                                @foreach ($categories as $key => $item)
                                    <tr data-category-id="{{ $item->id }}">
                                        <th scope="row">
                                            <span class="badge text-success bg-success-subtle">{{ $key + 1 }}</span>
                                        </th>
                                        <td>
                                            {{ $item->category->name }}
                                        </td>
                                        <td>
                                            <img width="100px" class="image-product b-2" src="{{ $item->thumbnail }}" alt="">
                                        </td>
                                        <td>
                                            <span class="">
                                                {{ $item->title }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $item->description }}
                                        </td>
                                        <td>
                                          @if ($item->type == 1)
                                            <span>Trái qua phải</span>
                                              @elseif ( $item->type == 2)

                                            <span>Phải qua trái</span>

                                              @elseif ($item->type == 3)
                                              <span>Trên xuống</span>
                                              @elseif ($item->type == 4)
                                              <span>Dưới lên</span>
                                          @endif  
                                        </td>
                                        <td>
                                            <span class="">
                                                @datetime($item->created_at)
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn btn-primary b-2 mb-1" data-category-id="{{ $item->id }}"><i class='bx bx-edit-alt text-white' ></i></span>
                                            <span class="btn btn-danger b-2 mb-1" id="remove-category" data-category-id="{{ $item->id }}"><i class='bx bxs-trash text-white' ></i></span>
                                        </td>
                                  </tr> 
                                @endforeach
                                  @else
                                <tr>
                                    <span class="text-center text-secondary">Hiện chưa có danh mục nào.</span>
                                </tr>
                              @endif
                          </tbody>
                    </table>
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
            $('#title-thumbnail-category').val("")
            $('#name-categories').val("");
            $('#type-thumbnail').val("");
            $('#category_id').val();
        });

        
            $('#modal-save-categories').click(function(){
                var img = $('#image-preview').attr('src');
                var title = $('#title-thumbnail-category').val();
                var description = $('#name-categories').val();
                var type = $('#type-thumbnail').val();
                var category = $('#category_id').val();

                if(
                    (img && img.length > 0) || (title && title.length > 0)
                    (description && description.length > 0) || (type && type.length > 0)
                    (category && category.length > 0)
                ){
                    $('#modal-erase-categories').click();
                    $('#image-preview').remove();
                    $('#title-thumbnail-category').val("")
                    $('#name-categories').val("");
                    $('#type-thumbnail').val("");
                    $('#category_id').val();

                    $.ajax({
                        url: '{{ route('admin.createContentThumbnailCategory') }}',
                        method: 'POST',
                        data:{
                            image : img,
                            title: title,
                            category_id: category,
                            description: description,
                            type: type,
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

    </script>

   

@endsection