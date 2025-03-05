@extends('admin.layout.app')

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-3 d-flex justify-content-between">
            <div class="title">
                <h5 class="text-color">Chào Mừng {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}!</h5>
            </div>
            <span class="text-start">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}" class="text-color">
                        Quản Lý Hệ Thống
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ url('/admin') }}" class="text-color">
                        Trang Chủ
                    </a>
                </span>
            </span>


        </div>
        <div class="row justify-content-end mb-3 p-4 pt-0 pb-0">
            <div class="btn btn-default bg-color d-flex gap-2 align-items-center col-sm-1 col-12 b-2 shadow" data-bs-toggle="modal" href="#export" role="button">
                <div class="icon">
                    <i class='bx bxs-file-export text-white'></i>
                </div>
                <div class="title badge">Báo cáo</div>
            </div>
        </div>
        <div class="container-content">
            <div class="row">
                <div class="item col-md-12 mb-3">
                    <div class="card border-0 shadow b-2">
                        <div class="card-header bg-white  b-2 border-0">
                            <h5 class="card-title text-color b-2">Doanh Số Bán Hàng</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow b-2">
                        <div class="card-header card-body bg-white">
                            <h5 class="text-color">
                                Người Dùng
                            </h5>
                        </div>
                        <div class="card-body">
                            TEst
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow b-2">
                        <div class="card-header card-body bg-white">
                            <h5 class="text-color">
                                Người Dùng
                            </h5>
                        </div>
                        <div class="card-body">
                            TEst
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade w-100" id="export" aria-hidden="true" aria-labelledby="exportToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content b-2 shadow border-0">
            <div class="modal-header">
                <h5 class="modal-title text-center text-bold" id="exportToggleLabe2" style=" flex:5;">Báo cáo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <div class="form-group d-flex gap-2 p-2 b-2 hover-color bg-success-subtle item mb-2 report" data-type="revenue-report">
                    <div class="icon">
                        <i class='bx bxs-file-doc font-size-20 text-success'></i>
                    </div>
                    <div class="title text-success">
                        Báo cáo doanh thu hàng tháng (excel)
                    </div>
               </div>

               <div class="form-group d-flex gap-2 p-2 b-2 hover-color bg-success-subtle item mb-2 report" data-type="user-report">
                    <div class="icon">
                        <i class='bx bxs-file-doc font-size-20 text-success'></i>
                    </div>
                    <div class="title text-success">
                        Báo cáo Số lượng người dùng (excel)
                    </div>
                </div>
                <div class="form-group d-flex gap-2 p-2 b-2 hover-color bg-success-subtle item mb-2" data-type="new-user-report">
                    <div class="icon">
                        <i class='bx bxs-file-doc font-size-20 text-success'></i>
                    </div>
                    <div class="title text-success">
                        Báo cáo người dùng mới hàng tháng (excel)
                    </div>
               </div>
                
            </div>
          </div>
        </div>
      </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Doanh số bán hàng',
                        data: @json($totals),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        $('.report').click(function(){
            var type = $(this).data('type');

            $.ajax({
                url: '{{ route('report.get.revenue') }}',
                method:"GET",
                data:{
                    type: type,
                },
                success:function(res){
                    
                }
            })
        })

        
    </script>
@endsection
