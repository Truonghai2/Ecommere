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
                    <a href="{{ route('admin.products') }}">
                        Quản Lý Sản Phẩm
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('admin.categories') }}">
                        Thêm Sản Phẩm
                    </a>
                </span>
            </span>
        </div>
        <div class="card b-2 border-0" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);">
            <div class="card-header p-2 bg-white border-0">
                <h5 class="card-title text-bold">Thêm Sản Phẩm</h5>
            </div>
            <div class="card-body">
                <div class="card-group">
                    <div class="number-inp">
                        <label for="quantity-add" class="font-size-14 text-secondary">Số lượng</label>
                        <input class="w-100 b-2 border-color p-2 outline-0" type="number" min="0" value="0"
                            max="100" name="quantity-add" id="quantity-add"
                            placeholder="Nhập số lượng sản phẩm cần thêm">
                    </div>
                </div>
            </div>
        </div>


        <form id="form-add-product">
            @csrf
            <div class="list mt-3" id="card-list-item">

            </div>
            <div class="card border-0 b-2 mt-3" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);">
                <div class="card-body text-end w-100" id="btn-save-product">
                    <button class="btn btn-primary bg-color b-2 border-color w-100" type="submit">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        var category = @json($category);
        $('#quantity-add').change(function() {
            var number = $(this).val();
            var html = '';
            for (var i = 1; i <= number; i++) {
                html += htmlAddProduct(i);
            }

            $('#card-list-item').html(html);

        })

        function renderCategories() {
            var html = '';
            category.forEach(item => {
                html += `
                    <option value="${item.id}">${item.name}</option>
                `;
            });
            return html;
        }

        function htmlAddProduct(index) {

            var html = `
            <div class="card card-product b-2 border-0 mt-3" data-item-id="${index}" id="card-product"  style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25);"> 
            <div class="card-body">
                <h6 class="card-title">Sản Phẩm ${index}</h6>
                <div class="product-add">
                    <div class="product-name">
                        <label for="product-name" class="font-size-14 text-secondary">Tên sản phẩm</label>
                        <input type="text" maxlength="250" name="product-name[${index}]" class="w-100 border-color b-2 p-2 outline-0" id="product-name" placeholder="Nhập tên sản phẩm" >
                    </div>
                    <div class="product-poster mt-3">
                        <div class="preview-poster" id="perview-poster" data-item-id="${index}">

                        </div>
                        <div class="add-image b-2 border-1 p-3 mt-3" data-item-id="${index}" style="cursor: pointer">
                            <div class="icon text-center"><i class="bx bx-plus font-size-24"></i></div>
                            <div class="title text-center text-secondary">Thêm ảnh bìa</div>
                        </div>
                        <input type="file" name="image-poster[${index}]" data-item-id="${index}" id="image-poster" hidden >
                    </div>
                    <div class="product-categories mt-3">
                        <label for="product-categories" class="text-secondary font-size-14">Danh mục</label>
                        <select name="product-categories[${index}]" class="b-2 w-100 p-2 outline-0 border-color" id="product-categories" >
                            <option value="">Chọn danh mục</option>
                            ${renderCategories()}
                        </select>
                    </div>
                    <div class="product-information mt-3">
                        <label for="product-decription" class="font-size-14 text-secondary">Thông tin sản phẩm</label>
                        <textarea name="product-decription[${index}]" id="product-decription" class="w-100 outline-0 border-color b-2 p-2" cols="30" rows="5" placeholder="Nhập thông tin sản phẩm" ></textarea>
                    </div>
                    <div class="product-poster">
                        <div class="perview-poster d-flex gap-1" style="flex-wrap:wrap;" id="preview-container" data-item-id="${index}">

                        </div>
                        <div class="add-image-thumbnail b-2 border-1 p-3 mt-3" data-item-id="${index}" style="cursor: pointer">
                            <div class="icon text-center"><i class="bx bx-plus font-size-24"></i></div>
                            <div class="title text-center text-secondary ">Thêm ảnh đính kèm</div>
                        </div>
                        <input type="file" name="image-poster[${index}]" data-item-id="${index}" id="image-thumbnail" hidden multiple >
                    </div>

                    <div class="product-brand mt-3">
                        <label for="product-brand" class="text-secondary font-size-14">Thương hiệu</label>
                        <input type="text" class="w-100 b-2 border-color p-2 outline-0" name="product-brand[${index}]" id="product-brand" placeholder="Nhập tên thương hiệu" >
                    </div>

                    <div class="product-make mt-3">
                        <label for="product-make" class="text-secondary font-size-14">Nơi sản suất</label>
                        <input type="text" class="w-100 b-2 border-color p-2 outline-0" name="product-make[${index}]" id="product-make" placeholder="Nhập nơi sản suất" >
                    </div>

                    <div class="product-guarantee mt-3">
                            <label for="product-guarantee" class="text-secondary font-size-14">Bảo hành (đơn vị: tháng)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-guarantee[${index}]"  id="product-guarantee" placeholder="Nhập thời gian bảo hành" >
                        </div>
                    <div class="product-options mt-3">
                        <label for="product-options" class="font-size-14 text-secondary">Lựa chọn đi kèm</label>
                        <select name="product-options[${index}]" data-item-id="${index}" id="product-options" class="w-100 b-2 p-2 border-color outline-0">
                            <option value="">Sản phẩm có lựa chọn đi kèm không?</option>    
                            <option value="0">Có</option>    
                            <option value="1">Không</option>    
                        </select>
                    </div>

                    <div class="yes-option" style="display:none;" data-item-id="${index}">
                        <div class="number-option">
                            <label for="number-option" class="text-secondary font-size-14">Số lượng lựa chọn cần thêm</label>
                            <input type="number" step="1" value="0" name="number-option[${index}]" id="number-option" max="100" min="0" data-item-id="${index}" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập số lượng lựa chọn cần thêm" >
                        </div>

                        <div class="container" id="menu-option" data-item-id="${index}">
                            
                        </div>
                    </div>

                    <div class="no-option" style="display:none;" data-item-id="${index}">
                        <div class="product-price mt-3">
                            <label for="product-price" class="text-secondary font-size-14">Giá trước giảm (đơn vị: đồng)</label>

                            <input type="text" name="product-price[${index}]" id="product-price" data-item-id="${index}" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập giá sản phẩm" >
                        </div>

                        <div class="product-sale mt-3">
                            <label for="product-sale" class="text-secondary font-size-14">% giảm giá (đơn vị: %)</label>

                            <input type="text" name="product-sale[${index}]" id="product-sale" data-item-id="${index}" min="0" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập % giảm giá" >
                        </div>

                        <div class="product-saled mt-3">
                            <label for="product-saled" class="text-secondary font-size-14">Giá đã giảm (được tính tự động từ giá đã giảm = giá trước giảm - % giảm giá) (đơn vị: đồng)</label>

                            <input type="text" name="product-saled[${index}]" id="product-saled" data-item-id="${index}" min="0" class="w-100 outline-0 border-color b-2 p-2" disabled placeholder="Nhập % giảm giá">
                        </div>

                        <div class="quantity mt-3">
                            <label for="product-quantity" class="text-secondary font-size-14">Tồn kho (đơn vị: sản phẩm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-quantity[${index}]" id="product-quantity" placeholder="Nhập số lượng tồn kho" >
                        </div>

                        
                        <div class="product-material mt-3">
                            <label for="product-material" class="text-secondary font-size-14">Chất liệu</label>
                            <input type="text" class="w-100 b-2 border-color p-2 outline-0" name="product-material[${index}]" id="product-material" placeholder="Nhập chất liệu sản phẩm" >
                        </div>
                        
                        <div class="product-weight mt-3">
                            <label for="product-weight" class="text-secondary font-size-14">Trọng lượng (đơn vị: gram)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-weight[${index}]" id="product-weight" placeholder="Nhập trọng lượng sản phẩm" >
                        </div>
                        <div class="product-width mt-3">
                            <label for="product-width" class="text-secondary font-size-14">Chiều rộng (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-width[${index}]" id="product-width" placeholder="Nhập chiều rộng sản phẩm" >
                        </div>
                        <div class="product-height mt-3">
                            <label for="product-height" class="text-secondary font-size-14">Chiều cao (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-height[${index}]" id="product-height" placeholder="Nhập chiều cao sản phẩm" >
                        </div>
                        <div class="product-length mt-3">
                            <label for="product-length" class="text-secondary font-size-14">Chiều dài (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-length[${index}]" id="product-length" placeholder="Nhập chiều dài sản phẩm" >
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
            return html;
        }

        var prices = [];

        $(document).on('input', '#product-price', function() {
            var index = $(this).data('item-id');
            var option_id = $(this).data('option-id');

            if (option_id == null) {
                prices[index] = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
                var newPrice = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
                formatAndSetPrice(prices[index], '#product-price[data-item-id="' + index + '"]');
                $('#product-saled[data-item-id="' + index + '"]').val(formatVND(newPrice));
            } else {
                if (!prices[index]) {
                    prices[index] = [];
                }
                prices[index][option_id] = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
                var newPrice = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
                formatAndSetPrice(prices[index][option_id], '#product-price[data-item-id="' + index +
                    '"][data-option-id="' + option_id + '"]');
                $('#product-saled[data-item-id="' + index + '"][data-option-id="' + option_id + '"]').val(formatVND(
                    newPrice));
            }
        });

        $(document).on('input', '#product-sale', function() {
            var option_id = $(this).data('option-id');
            var index = $(this).data('item-id');

            if (option_id == null) {
                var salePercentage = parseInt($(this).val()) || 0;
                var newPrice = (prices[index] - (prices[index] * salePercentage / 100));
                $('#product-saled[data-item-id="' + index + '"]').val(formatVND(newPrice));
            } else {
                var salePercentage = parseInt($(this).val()) || 0;
                var newPrice = (prices[index][option_id] - (prices[index][option_id] * salePercentage / 100));
                $('#product-saled[data-item-id="' + index + '"][data-option-id="' + option_id + '"]').val(formatVND(
                    newPrice));
            }
        });

        function formatVND(value) {
            return value.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });
        }

        function formatAndSetPrice(value, selector) {
            if (value) {
                $(selector).val(value.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }));
            }
        }

        var poster_id = 0;
        $(document).on('click', '.add-image', function() {
            $('#image-poster').click();
            poster_id = $(this).data('item-id');
        })

        $(document).on('change', '#image-poster', function() {

            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Hiển thị preview ảnh
                    $('.preview-poster[data-item-id="' + poster_id + '"]').html('<img src="' + e.target.result +
                        '" class="b-2 image-product" data-item-id="' + poster_id +
                        '" id="image-preview" width="100%"/>');
                }
                reader.readAsDataURL(file);

            }
        });

        var image_id = 0;
        $(document).on('click', '.add-image-thumbnail', function() {
            $('#image-thumbnail').click();
            image_id = $(this).data('item-id');
        })



        $(document).on('change', '#image-thumbnail', function() {
            var files = this.files;
            var fileList = [];



            for (var i = 0; i < files.length; i++) {
                fileList.push(files[i]);
            }

            fileList.forEach(function(file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var imgWrapper = $('<div>').attr('data-item-id', image_id).addClass('img-wrapper')
                        .css({
                            position: 'relative',
                            display: 'inline-block'
                        });
                    var img = $('<img>')
                        .attr('src', e.target.result)
                        .addClass('b-2 image-product')
                        .attr('data-item-id', image_id)
                        .css({
                            width: '100px',
                            height: '100px'
                        });

                    var closeButton = $('<div>')
                        .addClass('btn-close bg-c p-1')
                        .css({
                            position: 'absolute',
                            top: '5px',
                            right: '5px',
                            border: 'none',
                            borderRadius: '50%',
                            width: '20px',
                            height: '20px',
                            cursor: 'pointer'
                        })
                        .on('click', function() {
                            $(this).closest('.img-wrapper').remove();
                        });

                    imgWrapper.append(img).append(closeButton);

                    console.log(image_id)
                    $('.perview-poster[data-item-id="' + image_id + '"]').append(imgWrapper);
                }

                reader.readAsDataURL(file);
            });
        });

        $('#form-add-product').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            var data = [];

            $('.card-product').each(function() {
                var itemId = $(this).data('item-id');
                var images = [];

                $(this).find('.img-wrapper').each(function() {
                    var imgSrc = $(this).find('.image-product[data-item-id="' + itemId + '"]').attr(
                        'src');
                    if (imgSrc) {
                        images.push(imgSrc);
                    }
                });

                var type = $(`select[name="product-options[${itemId}]"]`).val();
                var itemData = {
                    product_name: $(this).find('input[name="product-name[' + itemId + ']"]').val(),
                    product_poster: $(this).find('#image-preview[data-item-id="' + itemId + '"]').attr(
                        'src'),
                    product_categories: $(this).find('select[name="product-categories[' + itemId +
                        ']"]').val(),
                    product_description: $(this).find('textarea[name="product-decription[' + itemId +
                        ']"]').val(),
                    product_type: type,
                    product_images: images,
                    product_brand: $(this).find('input[name="product-brand[' + itemId + ']"]').val(),
                    product_make: $(this).find(`input[name="product-make[${itemId}]"]`).val(),
                    product_guarantee: $(this).find(`input[name="product-guarantee[${itemId}]"]`).val(),
                };
                if (type == 1) {
                    itemData.product_price = extractNumber($(this).find('input[name="product-price[' +
                        itemId + ']"]').val());
                    itemData.product_sale = $(this).find('input[name="product-sale[' + itemId + ']"]')
                        .val();
                    itemData.product_quantity = $(this).find('input[name="product-quantity[' + itemId +
                        ']"]').val();
                    itemData.product_weight = $(this).find('input[name="product-weight[' + itemId + ']"]')
                        .val();
                    itemData.product_width = $(this).find('input[name="product-width[' + itemId + ']"]')
                        .val();
                    itemData.product_height = $(this).find('input[name="product-height[' + itemId + ']"]')
                        .val();
                    itemData.product_length = $(this).find('input[name="product-length[' + itemId + ']"]')
                        .val();
                    itemData.product_material = $(this).find(`input[name="product-material[${itemId}]"]`)
                        .val();
                } else if (type == 0) {
                    var options = [];
                    $(this).find('.option').each(function() {
                        var option_id = $(this).data('option-id');
                        var itemObject = {
                            product_option_type: $(this).find(`input[name="option-type[${itemId}][${option_id}]"]`).val(),
                            product_option_poster: $(this).find(
                                `#image-option-preview[data-item-id="${itemId}"][data-option-id="${option_id}"]`
                            ).attr('src'),
                            product_option_name: $(this).find(
                                `input[name="option-name[${itemId}][${option_id}]"]`).val(),
                            product_material: $(this).find(
                                    `input[name="product-material[${itemId}][${option_id}]"]`)
                                .val(),
                            product_price: extractNumber($(this).find(
                                    `input[name="product-price[${itemId}][${option_id}]"]`)
                                .val()),
                            product_sale: $(this).find(
                                    `input[name="product-sale[${itemId}][${option_id}]"]`)
                                .val(),
                            product_quantity: $(this).find(
                                    `input[name="product-quantity[${itemId}][${option_id}]"]`)
                                .val(),
                            product_weight: $(this).find(
                                    `input[name="product-weight[${itemId}][${option_id}]"]`)
                                .val(),
                            product_width: $(this).find(
                                    `input[name="product-width[${itemId}][${option_id}]"]`)
                                .val(),
                            product_height: $(this).find(
                                    `input[name="product-height[${itemId}][${option_id}]"]`)
                                .val(),
                            product_length: $(this).find(
                                    `input[name="product-length[${itemId}][${option_id}]"]`)
                                .val(),
                        };

                        if (isValidOption(itemObject)) {
                            options.push(itemObject);
                        }
                    });
                    itemData.options = options;
                }

                data.push(itemData);

            });





            var array = cleanProducts(data);

            if (array.length > 0) {
                $.ajax({
                    url: '{{ route('admin.addItemProduct') }}',
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        products: array
                    }),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        console.log(res);
                    }
                })
            }
        });


        function extractNumber(priceString) {
            var numberString = priceString.replace(/[^\d]/g, '');
            return parseInt(numberString, 10); // Chuyển đổi chuỗi thành số nguyên
        }


        function isValidOption(option) {
            const requiredFields = ['product_option_poster', 'product_option_name', 'product_material', 'product_price',
                'product_sale', 'product_quantity', 'product_weight', 'product_width', 'product_height',
                'product_length'
            ];
            for (let field of requiredFields) {
                if (!option[field] || (field.includes('price') && isNaN(option[field]))) {
                    return false;
                }
            }
            return true;
        }

        function isValidProduct(itemData) {
            const requiredFields = ['product_name', 'product_categories', 'product_description', 'product_make',
                'product_brand', 'product_poster', 'product_guarantee'
            ];

            for (let field of requiredFields) {
                if (!itemData[field] || (Array.isArray(itemData[field]) && itemData[field].length === 0)) {
                    return false;
                }
            }

            if (itemData.product_type == 1) {
                const type0Fields = ['product_price', 'product_sale', 'product_quantity', 'product_weight', 'product_width',
                    'product_height', 'product_length'
                ];
                for (let field of type0Fields) {
                    if (itemData[field] === undefined || itemData[field] === null || itemData[field] === '' || isNaN(
                            itemData[field])) {
                        return false;
                    }
                }
            } else if (itemData.product_type == 0) {
                if (!itemData.options || itemData.options.length === 0) {
                    return false;
                }

                const requiredFields = ['product_option_poster', 'product_option_name', 'product_material', 'product_price',
                    'product_sale', 'product_quantity', 'product_weight', 'product_width', 'product_height',
                    'product_length'
                ];
                for (let option of itemData.options) {
                    for (let field of requiredFields) {
                        if (!option[field] || (field.includes('price') && isNaN(option[field]))) {
                            return false;
                        }
                    }
                }
            }

            return true;
        }

        function cleanProducts(products) {
            return products.filter(isValidProduct);
        }


        $(document).on('change', '#number-option', function() {
            var index = $(this).data('item-id');
            var data = $(this).val();
            var html = '';

            for (var i = 1; i <= data; i++) {
                html += htmlOption(index, i);
            }

            $(`#menu-option[data-item-id="${index}"]`).html(html);
        })

        function htmlOption(index, number) {
            return `
                <div class="option" data-item-id="${index}" data-option-id="${number}">
                    <label for=""  class="text-secondary font-size-18 text-bold mt-4">Lựa chọn ${number}</label>

                    <div class="option-type position-relative">
                        <label for="option-type" class="text-secondary font-size-14">Phân loại lựa chọn</label>
                        <input type="text" name="option-type[${index}][${number}]" id="option-type" data-item-id="${index}" data-option-id="${number}" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập kiểu lựa chọn" >

                        <div class="menu d-none position-absolute w-100 bg-white b-2 p-2 shadow" id="dropdown-option" data-item-id="${index}" data-option-id="${number}">
                            ${populateDropdown(attributess, index,  number)}               
                        </div>
                        
                    </div>
                    <div class="option-name">
                        <label for="option-name" class="text-secondary font-size-14">Tên lựa chọn</label>
                        <input type="text" name="option-name[${index}][${number}]" id="option-name" data-item-id="${index}" data-option-id="${number}" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập tên lựa chọn" >
                    </div>
                    <div class="product-poster mt-3">
                        <div class="preview-option-poster" id="preview-option-poster" data-item-id="${index}" data-option-id="${number}">

                        </div>
                        <div class="add-image-option b-2 border-1 p-3 mt-3" data-item-id="${index}" data-option-id="${number}" style="cursor: pointer">
                            <div class="icon text-center"><i class="bx bx-plus font-size-24"></i></div>
                            <div class="title text-center text-secondary">Thêm ảnh bìa</div>
                        </div>
                        <input type="file" name="image-option-poster[${index}][${number}]" data-item-id="${index}" data-option-id="${number}" id="image-option-poster" hidden >
                    </div>
                    <div class="product-price mt-3">
                            <label for="product-price" class="text-secondary font-size-14">Giá trước giảm (đơn vị: đồng)</label>

                            <input type="text" name="product-price[${index}][${number}]" id="product-price" data-item-id="${index}" data-option-id="${number}" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập giá sản phẩm" >
                        </div>

                        <div class="product-sale mt-3">
                            <label for="product-sale" class="text-secondary font-size-14">% giảm giá (đơn vị: %)</label>

                            <input type="text" name="product-sale[${index}][${number}]" id="product-sale" data-item-id="${index}" data-option-id="${number}" min="0" class="w-100 outline-0 border-color b-2 p-2" placeholder="Nhập % giảm giá" >
                        </div>

                        <div class="product-saled mt-3">
                            <label for="product-saled" class="text-secondary font-size-14">Giá đã giảm (được tính tự động từ giá đã giảm = giá trước giảm - % giảm giá) (đơn vị: đồng)</label>

                            <input type="text" name="product-saled[${index}][${number}]" id="product-saled" data-item-id="${index}" data-option-id="${number}" min="0" class="w-100 outline-0 border-color b-2 p-2" disabled placeholder="Nhập % giảm giá">
                        </div>

                        <div class="quantity mt-3">
                            <label for="product-quantity" class="text-secondary font-size-14">Tồn kho (đơn vị: sản phẩm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-quantity[${index}][${number}]" data-option-id="${number}" id="product-quantity" placeholder="Nhập số lượng tồn kho" >
                        </div>

                        
                        <div class="product-material mt-3">
                            <label for="product-material" class="text-secondary font-size-14">Chất liệu</label>
                            <input type="text" class="w-100 b-2 border-color p-2 outline-0" name="product-material[${index}][${number}]" data-option-id="${number}" id="product-material" placeholder="Nhập chất liệu sản phẩm" >
                        </div>
                        

                        <div class="product-weight mt-3">
                            <label for="product-weight" class="text-secondary font-size-14">Trọng lượng (đơn vị: gram)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-weight[${index}][${number}]" data-option-id="${number}" id="product-weight" placeholder="Nhập trọng lượng sản phẩm" >
                        </div>
                        <div class="product-width mt-3">
                            <label for="product-width" class="text-secondary font-size-14">Chiều rộng (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-width[${index}][${number}]" data-option-id="${number}" id="product-width" placeholder="Nhập chiều rộng sản phẩm" >
                        </div>
                        <div class="product-height mt-3">
                            <label for="product-height" class="text-secondary font-size-14">Chiều cao (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-height[${index}][${number}]" data-option-id="${number}" id="product-height" placeholder="Nhập chiều cao sản phẩm" >
                        </div>
                        <div class="product-length mt-3">
                            <label for="product-length" class="text-secondary font-size-14">Chiều dài (đơn vị: cm)</label>
                            <input type="number" min="0" class="w-100 b-2 border-color p-2 outline-0" name="product-length[${index}][${number}]" data-option-id="${number}" id="product-length" placeholder="Nhập chiều dài sản phẩm" >
                        </div>    
                </div>
            `;
        }

        function populateDropdown(attributes, index, number) {
            var html = ''
            attributes.forEach(function(item) {
                html += `<div class="value-attribute p-2 b-2 hover-color" data-item-id="${index}" data-option-id="${number}" data-attribute-id="${item.id}" data-attribute-name="${item.name}">
                    ${item.name}
                </div>`;
            });
            return html;
        }

        var attributess = @json($attribute);


        $(document).on('click', '.value-attribute', function() {
            var name = $(this).data('attribute-name');
            var item = $(this).data('item-id');
            var op_id = $(this).data('option-id');

            $(`input[name="option-type[${item}][${op_id}]"]`).val(name);
            $(`div#dropdown-option[data-item-id="${item}"][data-option-id="${op_id}"]`).addClass('d-none');
        });

        $(document).on('focus', 'input[id="option-type"]', function() {
            var item = $(this).data('item-id');
            var op_id = $(this).data('option-id');
            $(`div#dropdown-option[data-item-id="${item}"][data-option-id="${op_id}"]`).removeClass('d-none');
        });

        // Hide dropdown when clicking outside
        $(document).on('click', function(e) {
                if (!$(e.target).closest('.option-type').length) {
                    $('#dropdown-option').addClass('d-none');
                }
            });

        $(document).on('input', 'input[id="option-type"]', function() {
            var item = $(this).data('item-id');
            var op_id = $(this).data('option-id');
            var data = $(this).val().toLowerCase();

            var filteredAttributes = attributess.filter(attr => attr.name.toLowerCase().includes(data));
            var filteredHTML = populateDropdown(filteredAttributes, item, op_id);
            $(`div#dropdown-option[data-item-id="${item}"][data-option-id="${op_id}"]`).html(filteredHTML);
        });

        var indx = 0;
        var op_id = 0;
        $(document).on('click', '.add-image-option', function() {
            indx = $(this).data('item-id');
            op_id = $(this).data('option-id');
            $(`#image-option-poster[data-item-id="${indx}"][data-option-id="${op_id}"]`).click();
        })

        $(document).on('change', `#image-option-poster`, function() {

            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // Hiển thị preview ảnh
                    $(`.preview-option-poster[data-item-id="${indx}"][data-option-id="${op_id}"]`).html(
                        '<img src="' + e.target.result +
                        '" class="b-2 image-product" data-item-id="' + indx +
                        '" data-option-id="' + op_id +
                        '" id="image-option-preview" width="100%"/>');
                }
                reader.readAsDataURL(file);

            }
        });


        $(document).on('change', '#product-options', function() {
            var index = $(this).data('item-id');
            var data = $(this).val();
            var $noOption = $(`.no-option[data-item-id="${index}"]`);
            var $yesOption = $(`.yes-option[data-item-id="${index}"]`);

            if (data == 1) {
                $noOption.css('display', 'block').addClass('expand border-0').removeClass('collapses');
                $yesOption.css('display', 'none').addClass('collapses').removeClass('expand');
            } else {
                $noOption.css('display', 'none').addClass('collapses').removeClass('expand');
                $yesOption.css('display', 'block').addClass('expand border-0').removeClass('collapses');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            function handleResize() {
                var btnShoping = document.querySelector('#btn-save-product');
                if (window.innerWidth <= 768) {
                    btnShoping.classList.add('btn-shoping-fixed');
                } else {
                    btnShoping.classList.remove('btn-shoping-fixed');
                }
            }

            handleResize();
            window.addEventListener('resize', handleResize);
        });
    </script>
@endsection
