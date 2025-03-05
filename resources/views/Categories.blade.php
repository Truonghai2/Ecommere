

@extends("welcome")

@section('content')
    <div class="container pt-5 pb-5">
        <div class="path">
            <a class="text-decoration-none" href="{{ url('/') }}">
                <span class="text-secondary font-size-14 hover-underline">
                    Trang chủ
                </span>
            </a> /
            <a href="{{ route('user.category', ['slug' => $category->slug]) }}" class="text-decoration-none">
                <span class="text-secondary font-size-14 hover-underline">
                    {{ $category->name }}
                </span>
            </a>
        </div>
        <div class="title mt-3">
            <h3 class="text-color hover-underline">{{ $category->name }}</h3>
        </div>


        <div class="item-product mt-4">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="card b-2 h-auto">
                        <div class="card-header border-0 b-2 bg-white d-flex align-items-center justify-content-between">
                            <div class="title">
                                <h5>Lọc</h5>
                            </div>
                            <div class="card-icon" id="open-filter">
                                <i class='bx bx-chevron-down font-size-24' ></i>
                            </div>
                        </div>
                    </div>
                    <div class="card b-2" id="filter-content">
                        <div class="card-header border-0 b-2 bg-white d-flex align-items-center justify-content-between">
                            <div class="title">
                                <h5 >Lọc</h5>
                            </div>
                            <div class="card-icon" id="hidden-filter">
                                <i class='bx bx-minus font-size-24' ></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="price">
                                <label for="price" class="text-secondary font-size-14">Giá</label>
                                <div id="price-range" class="mt-1"></div>
                                <div class="display-price d-flex align-items-center justify-content-end mt-1 gap-1">
                                    <div class="font-size-14 text-secondary" id="price-min"></div>
                                    <div class="text font-size-14 text-secondary">đến</div>
                                    <div class="font-size-14 text-secondary" id="price-max"></div>
                                </div>
                            </div>
                            <div class="material">
                                <label for="" class="font-size-14 text-secondary">
                                    Chất liệu
                                </label>
                                <div class="material-checkbox">
                                    <input type="checkbox" name="material-checkbox" id="material-plastic" value="plastic">
                                    <label for="material-plastic" class="b-2 p-2 mb-2 border-label">Nhựa</label>

                                    <input type="checkbox" name="material-checkbox" id="material-ceramic" value="ceramic">
                                    <label for="material-ceramic" class="b-2 p-2 mb-2 border-label">
                                        Gốm, Sứ
                                    </label>
                                    <input type="checkbox" name="material-checkbox" id="material-inox-201" value="inox-201">
                                    <label for="material-inox-201" class="b-2 p-2 mb-2 border-label">Inox 201</label>

                                    <input type="checkbox" name="material-checkbox" id="material-inox-304" value="inox-304">
                                    <label for="material-inox-304" class="b-2 p-2 mb-2 border-label">Inox 304</label>

                                    <input type="checkbox" name="material-checkbox" id="material-inox-401" value="inox-401">
                                    <label for="material-inox-401" class="b-2 p-2 mb-2 border-label">Inox 401</label>
                                </div>
                            </div>
                        </div>

                        <div class="card-header border-0 b-2 bg-white">
                            <div class="title">
                                <h5>Sắp xếp</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="sort-price">
                                <label for="" class="font-size-14 text-secondary">Giá</label>

                                <div class="main-sort">
                                    <input type="radio" name="sort-price" id="sort-price-up" value="1" {{ (auth()->user()->sort_price == 1 ) ? 'checked' : '' }}>
                                    <label for="sort-price-up" class="p-2 b-2">
                                        <span><i class='bx bx-up-arrow-alt font-size-20 '></i></span>
                                        <span>Tăng dần</span>
                                    </label>
                                    <input type="radio" name="sort-price" id="sort-price-down" value="2" {{ (auth()->user()->sort_price == 2 ) ? 'checked' : '' }}>
                                    <label for="sort-price-down" class="p-2 b-2">
                                        <span><i class='bx bx-down-arrow-alt font-size-20' ></i></span>
                                        <span>Giảm dần</span>
                                    </label>

                                    <input type="radio" name="sort-price" id="sort-price-default" value="0" {{ (auth()->user()->sort_price === null || auth()->user()->sort_price == 0) ? 'checked' : '' }}>
                                    <label for="sort-price-default" class="p-2 b-2">
                                        <span>Mặc định</span>
                                    </label>
                                </div>
                            </div>

                            <div class="sort-favourite">
                                <label for="" class="font-size-14 text-secondary">
                                    Yêu thích
                                </label>
                                <div class="main-sort">
                                    <input type="radio" name="sort-favourite" id="sort-favourite-up" value="1" {{ (auth()->user()->sort_favourite == 1 ) ? 'checked' : '' }}>
                                    <label for="sort-favourite-up" class="p-2 b-2">
                                        <span><i class='bx bx-up-arrow-alt font-size-20 '></i></span>
                                        <span>Tăng dần</span>
                                    </label>

                                    <input type="radio" name="sort-favourite" id="sort-favourite-down" value="2" {{ (auth()->user()->sort_favourite == 2 ) ? 'checked' : '' }}>
                                    <label for="sort-favourite-down" class="p-2 b-2">
                                        <span><i class='bx bx-down-arrow-alt font-size-20' ></i></span>
                                        <span>Giảm dần</span>
                                    </label>

                                    <input type="radio" name="sort-favourite" id="sort-favourite-default" value="0" {{ (auth()->user()->sort_favourite === null || auth()->user()->sort_favourite == 0) ? 'checked' : '' }}>
                                    <label for="sort-favourite-default" class="p-2 b-2">
                                        <span>Mặc định</span>
                                    </label>
                                </div>
                            </div>

                            <div class="sort-sale">
                                <label for="" class="font-size-14 text-secondary">Giảm giá</label>

                                <div class="main-sort">
                                    <input type="radio" name="sort-sale" id="sort-sale-up" value="1" {{ (auth()->user()->sort_sale == 1 ) ? 'checked' : '' }}>
                                    <label for="sort-sale-up" class="p-2 b-2">
                                        <span><i class='bx bx-up-arrow-alt font-size-20 '></i></span>
                                        <span>Tăng dần</span>
                                    </label>

                                    <input type="radio" name="sort-sale" id="sort-sale-down" value="2" {{ (auth()->user()->sort_sale == 2 ) ? 'checked' : '' }}>
                                    <label for="sort-sale-down" class="p-2 b-2">
                                        <span><i class='bx bx-down-arrow-alt font-size-20' ></i></span>
                                        <span>Giảm dần</span>
                                    </label>

                                    <input type="radio" name="sort-sale" id="sort-sale-default" value="0" {{ (auth()->user()->sort_sale === null || auth()->user()->sort_sale == 0) ? 'checked' : '' }}>
                                    <label for="sort-sale-default" class="p-2 b-2">
                                        <span>Mặc định</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="p-5 " id="loading-product">
                        <div class="loader-item mx-auto"></div>
                    </div>
                    <div class="row p-2" id="add-product">
                        
                    </div>
                    <div class="p-5 d-none" id="loading-product-append">
                        <div class="loader-item mx-auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var priceRange = document.getElementById('price-range');
        var selectedValues = $('input[name="material-checkbox"]:checked').map(function() {
                return $(this).val();
            }).get() ?? [];
        
        noUiSlider.create(priceRange, {
            start: [{{ auth()->user()->start_price }}, {{ auth()->user()->end_price }}], // Initial values
            connect: true,
            range: {
                'min': 0,
                'max': 50000000
            },
            tooltips: [true, true],
            format: {
                to: function (value) {
                    return formatVND(Number(value).toFixed(0));
                },
                from: function (value) {
                    return Number(value.replace(/[^\d.-]/g, ''));
                }
            }
        });

        var priceMin = document.getElementById('price-min');
        var priceMax = document.getElementById('price-max');

        priceRange.noUiSlider.on('update', function (values, handle) {
            if (handle) {
                $('#price-max').html(values[handle]);
            } else {
                $('#price-min').html(values[handle]);
            }
        });



        function formatVND(value) {
            return Number(value).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        }


        priceRange.noUiSlider.on('change', function (values, handle) {
            var startPrice = values[0];
            var endPrice = values[1];

            var sort_price = $('input[name="sort-price"]:checked').val() ?? null;
            var sort_favorite = $('input[name="sort-favourite"]:checked').val() ?? null;
            var sort_sale = $('input[name="sort-sale"]:checked').val() ?? null;

            $('#loading-product').css('display', 'block');
            getProduct(extractNumbersFromString(startPrice), extractNumbersFromString(endPrice), sort_price, sort_favorite, sort_sale, true);
            
        });


        function extractNumbersFromString(inputString) {
            const numbers = inputString.match(/\d+/g);
            return numbers ? numbers.join('') : '';
        }



        var page = 1;
        let noMoreProduct = false;

        getProduct(
            {{ auth()->user()->start_price }},
            {{ auth()->user()->end_price }},
            {{ auth()->user()->sort_price ?? 0 }},
            {{ auth()->user()->sort_favourite ?? 0 }},
            {{ auth()->user()->sort_sale ?? 0 }},
            true
        );

        function getProduct(startPrice, endPrice, sort_price, sort_favorite, sort_sale, newProduct = false){
            if(newProduct){
                page = 1;
                noMoreProduct = false;
            }

            if(!noMoreProduct){
                if(page == 1){
                    $('#add-product').addClass('d-none');
                }
                else{
                    $('#loading-product-append').removeClass('d-none');
                }
                
                $.ajax({
                    url: '{{ route('user.filterProduct') }}',
                    method: 'GET',
                    data:{
                        page: page,
                        slug: '{{ $category->slug }}',
                        min: startPrice,
                        max: endPrice,
                        material: selectedValues ?? [],
                        sort_price: sort_price,
                        sort_favourite: sort_favorite,
                        sort_sale: sort_sale,
                    },
                    success:function(res){
                        if(page == 1){
                            actionOnScrollBottomHome(window, function(){
                                getProduct(
                                    startPrice, 
                                    endPrice,
                                    sort_price, 
                                    sort_favorite, 
                                    sort_sale
                                );
                            });
                            $('#loading-product').css('display', 'none');
                            $('#add-product').removeClass('d-none');
                            $('#add-product').html(res.product);
                        }
                        else{
                            $('#loading-product-append').addClass('d-none');
                            $('#add-product').append(res.product);
                        }

                        noMoreProduct = page >= res?.last_page;

                        if(!noMoreProduct){
                            page++;
                            isFetching = false;
                        }
                    }
                })
            }
        }

        let isFetching = false;

        function actionOnScrollBottomHome(element, callback, footerSelector = 'footer') {
            $(element).on('scroll', () => {
                var footerHeight = $(footerSelector).outerHeight();
                var scrollBottom = $(document).height() - ($(window).scrollTop() + $(window).height() + footerHeight);
                if (scrollBottom < 3 && !isFetching) {
                    isFetching = true;
                    callback();
                }
            });
        }


        $('input[name="sort-price"]').on('change', function(){
            var startPrice = $('#price-min').html();
            var endPrice = $('#price-max').html();

            var sort_price = $(this).val() ?? null;
            var sort_favorite = $('input[name="sort-favourite"]:checked').val() ?? null;
            var sort_sale = $('input[name="sort-sale"]:checked').val() ?? null;
            $('#loading-product').css('display', 'block');
            getProduct(extractNumbersFromString(startPrice), extractNumbersFromString(endPrice), sort_price, sort_favorite, sort_sale, true);
        })

        $('input[name="sort-favourite"]').change(function(){
            var startPrice = $('#price-min').html();
            var endPrice = $('#price-max').html();

            var sort_price = $('input[name="sort-price"]:checked').val() ?? null;
            var sort_favorite = $('input[name="sort-favourite"]:checked').val() ?? null;
            var sort_sale = $(this).val() ?? null;
            $('#loading-product').css('display', 'block');
            getProduct(extractNumbersFromString(startPrice), extractNumbersFromString(endPrice), sort_price, sort_favorite, sort_sale, true);
        })

        $('input[name="sort-sale"]').change(function(){
            var startPrice = $('#price-min').html();
            var endPrice = $('#price-max').html();

            var sort_price = $('input[name="sort-price"]:checked').val() ?? null;
            var sort_favorite = $(this).val() ?? null;
            var sort_sale = $('input[name="sort-sale"]:checked').val() ?? null;
            $('#loading-product').css('display', 'block');
            getProduct(extractNumbersFromString(startPrice), extractNumbersFromString(endPrice), sort_price, sort_favorite, sort_sale, true);
        })
            var openFilter = document.getElementById('open-filter');
            var hiddenFilter = document.getElementById('hidden-filter');
            var filterContent = document.getElementById('filter-content');
            var filterCard = openFilter.parentElement.parentElement;

            openFilter.addEventListener('click', function() {
                filterContent.classList.remove('collapses');
                filterContent.classList.add('expand');
                filterContent.classList.remove('h-0');
                filterCard.style.display = 'none';
            });

            hiddenFilter.addEventListener('click', function() {
                filterContent.classList.remove('expand');
                filterContent.classList.add('collapses');
                filterContent.addEventListener('animationend', function() {
                    filterCard.style.display = 'flex';
                    filterContent.classList.add('h-0'); 
                }, { once: true });
            });
            

        $("input[name=material-checkbox]").change(function(){
            var startPrice = $('#price-min').html();
            var endPrice = $('#price-max').html();

            var sort_price = $('input[name="sort-price"]:checked').val() ?? null;
            var sort_favorite = $('input[name="sort-favourite"]:checked').val() ?? null;
            var sort_sale = $('input[name="sort-sale"]:checked').val() ?? null;

            
            $('#loading-product').css('display', 'block');

            selectedValues = $('input[name="material-checkbox"]:checked').map(function() {
                return $(this).val();
            }).get();


            if(selectedValues.length > 0){
                getProduct(extractNumbersFromString(startPrice), extractNumbersFromString(endPrice), sort_price, sort_favorite, sort_sale, true);
            }
        })
    </script>
@endsection