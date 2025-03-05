@extends('welcome')

@section('content')
    
    <div class="container">
        <div class="content-header mb-3">
            <div class="p-2 d-flex justify-content-between align-item-center">
                <div class="title">
                    <h5>Thông báo</h5>
                </div>
                <div class="type-select-notification">
                    <input type="radio" name="type_select" id="type_select_all" value="0" checked>
                    <label for="type_select_all" class="border-label p-2 b-3">Tất cả</label>
                    <input type="radio" name="type_select" id="type_select_see" value="1">
                    <label for="type_select_see" class="border-label p-2 b-3">Chưa xem</label>
                    
                </div>
            </div>
        </div>


        <div class="content-main">
           
        </div>

        <div class="p-5 d-none" id="loading-product-append">
            <div class="loader-item mx-auto"></div>
        </div>
    </div>

    <script>
        let noMoreNotification = false;
        let page = 1;
        loading = false;
        var type = $('input[name="type_select"]:checked').val();
        fetchNotification(true);
        actionOnScrollBottom(window, function(){
            fetchNotification();
        })

        $('input[name="type_select"]').change(function(){
            type = $(this).val();
            $('.content-main').empty();
            fetchNotification(true);
        })


        function fetchNotification(newPage = false){
            if(newPage){
                page = 1;
                noMoreNotification = false;
                loading = false;
            }

            if(!noMoreNotification && !loading){

                loading = true;
                $("#loading-product-append").removeClass('d-none');

                $.ajax({
                    url: '{{ route('notification.get.user') }}',
                    method: "GET",
                    data:{
                        type: type,
                        page: page,
                    },
                    success:function(res){
                        if(page == 1){
                            $(".content-main").html(res.html);
                        }
                        else{
                            $(".content-main").append(res.html);
                        }

                        noMoreNotification = page >= res?.last_page;

                        if(!noMoreNotification){
                            page++;
                        }
                        $("#loading-product-append").addClass('d-none');

                        loading = false;
                    }
                })
            }
        }


        $(document).on('click', '.notification-item', function(){
            var id = $(this).data('notification-id');
            var self = $(this); // Store the reference to the clicked element

            if(id){
                $.ajax({
                    url:'{{ route('notification.post.markSee') }}',
                    method:"POST",
                    data:{
                        id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success:function(res){
                        self.find('#status-notification').empty(); // Use `self` to refer to the clicked element
                        self.find('.time').removeClass('text-color');
                    }
                });
            }
        })
    </script>
@endsection