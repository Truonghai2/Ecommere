@extends('admin.layout.app')


@section('content')
    <div class="container pt-5 pb-5">
        <div class="path pb-5 text-color">
            <span class="text-start text-color">
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('home') }}" class="text-color">
                        Quản Lý Hệ Thống
                    </a>
                </span> /
                <span class="hover-underline font-size-14 text-secondary">
                    <a href="{{ route('admin.categories') }}" class="text-color">
                        Quản Lý Sản Phẩm
                    </a>
                </span>
            </span>
        </div>

        <div class="card border-0 b-2" style="box-shadow: 0px 25px 50px -12px rgba(15, 23, 42, 0.25); ">
            <div class="card-header border-0 bg-white d-flex align-items-center justify-content-between">

                <h5 class="card-title col-lg-6 col-sm-8 text-bold text-color">
                    Danh Sách Sản Phẩm
                </h5>

                <div class="item row col-lg-5 col-sm-8">
                    <div class="row gap-2 justify-content-end">
                        <div class="search border-1 col-lg-6 col-sm-8  border-color p-1 b-3 d-flex align-items-center">
                            <div class="icon mt-1"><i class='bx bx-search-alt-2 font-size-20'></i></div>
                            <div class="input ml-1">
                                <input type="text" class="border-0 outline-0" name="search-user" id="search-user"
                                    placeholder="Tìm kiếm sản phẩm">
                            </div>
                        </div>

                        <a class="btn btn-success col-lg-4 col-sm-3 d-flex align-items-center b-2"
                            href="{{ route('admin.addproducts') }}" role="button">
                            <div class="icon">
                                <i class='bx bx-plus text-white mt-1 font-size-20'></i>
                            </div>
                            <div class="title">
                                <span class="title badge">Thêm Sản Phẩm</span>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table">
                        <thead class="border-0">
                            <tr class="b-2">
                               <th scope="col" class="text-white" >#</th>
                               <th scope="col" class="text-white" >Ảnh</th>
                               <th scope="col" class="text-white" >Tên</th>
                               <th scope="col" class="text-white" >Giá trước giảm</th>
                               <th scope="col" class="text-white" >Giảm giá</th>
                               <th scope="col" class="text-white" >Giá sau giảm</th>
                               <th scope="col" class="text-white" >Các lựa chọn</th>
                               <th scope="col" class="text-white" >Thông tin sản phẩm</th>
                               <th scope="col" class="text-white" >Ảnh đính kèm</th>
                               <th scope="col" class="text-white" >Danh mục</th>
                               <th scope="col" class="text-white" >Hàng tồn kho</th>
                               <th scope="col" class="text-white" >Đã bán</th>
                               <th scope="col" class="text-white" >Đánh giá</th>
                               <th scope="col" class="text-white" >Ngày tạo</th>
                               <th scope="col" class="text-white" >Hành động</th>
                            </tr>
                        </thead>
                        
                        <tbody class="right" style="display: none">
                        </tbody>
                        <tbody class="told">

                        </tbody>
                    </table>
                </div>

                <div class="p-5 d-none" id="loading-product-append">
                    <div class="loader-item mx-auto"></div>
                </div>
            </div>
        </div>


        <div class="modal fade w-100" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="exampleModalToggleLabel" style=" flex:5;">Chỉnh
                            sửa sản phẩm</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="edit-image-preview d-flex align-items-center gap-2 p-2 b-2 hover-color item"
                                data-bs-dismiss="modal" aria-label="Close" id="editIamgePreviewclick"
                                href="#editImagePreview" data-bs-toggle="modal" role="button">
                                <div class="icon">
                                    <i class='bx bxs-file-image font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa hình ảnh minh họa</div>
                            </div>

                            <div class="edit-option-product d-flex align-items-center gap-2 p-2 b-2 hover-color item"
                                data-bs-dismiss="modal" aria-label="Close" id="editOptionProductClick"
                                href="#editOptionProduct" data-bs-toggle="modal" role="button">
                                <div class="icon">
                                    <i class='bx bx-food-menu font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa lựa chọn</div>
                            </div>

                            <div class="edit-option-product d-flex align-items-center gap-2 p-2 b-2 hover-color item"
                                data-bs-dismiss="modal" aria-label="Close" id="editQuantityProductClick"
                                href="#editQuantityProduct" data-bs-toggle="modal" role="button">
                                <div class="icon">
                                    <i class='bx bx-food-menu font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa số lượng sản phẩm</div>
                            </div>

                            <div class="edit-option-product d-flex align-items-center gap-2 p-2 b-2 hover-color item">
                                <div class="icon">
                                    <i class='bx bx-money-withdraw font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa giá sản phẩm</div>
                            </div>


                            <div class="edit-option-product d-flex align-items-center gap-2 p-2 b-2 hover-color item">
                                <div class="icon">
                                    <i class='bx bx-package font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa tổng thể sản phẩm</div>
                            </div>

                            <div class="edit-option-product d-flex align-items-center gap-2 p-2 b-2 hover-color item"
                            data-bs-dismiss="modal" aria-label="Close" id="uploadCategoriesMenu"
                            href="#uploadCategories" data-bs-toggle="modal" role="button">
                                <div class="icon">
                                    <i class='bx bx-category font-size-24'></i>
                                </div>
                                <div class="title">Chỉnh sửa danh mục sản phẩm</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade w-100" id="editImagePreview" tabindex="-1" aria-labelledby="editImagePreviewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="editImagePreviewToggleLabel" style=" flex:5;">
                            Chỉnh sửa hình ảnh sản phẩm</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <div class="row p-1" id="image-preview-product">

                            </div>
                        </div>
                        <div class="form-group">
                            <div id="image-preview-container"></div>
                            <div id="upload-progress"></div>
                        </div>
                        <div class="form-group p-3 border-1 border-label b-2 hover-color" id="click-upload">
                            <div class="icon text-center">
                                <i class='bx bx-upload text-center text-secondary' style="font-size: 30px;"></i>
                            </div>
                            <div class="title text-center text-secondary">
                                Thêm ảnh mô tả sản phẩm
                            </div>

                        </div>
                        <input type="file" name="upload-image-preview" id="inp-upload-image-preview" hidden multiple>

                    </div>
                    <div class="modal-footer d-none" id="footer-modal-add-image">
                        <div class="btn btn-primary bg-color border-color b-2 w-100" id="btn-save-images">Lưu ảnh</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal edit option product --}}
        <div class="modal fade w-100" id="editOptionProduct" tabindex="-1" aria-labelledby="editOptionProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="editOptionProductToggleLabel" style=" flex:5;">
                            Chỉnh sửa lựa chọn sản phẩm</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="optionsContainer">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-center btn btn-primary border-0 bg-color p-2 b-2 gap-2 w-100">

                            <div class="title text-white" id="update-option-to-db" data-bs-dismiss="modal"
                            aria-label="Close">
                                Lưu thay đổi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal edit quantity product --}}

        <div class="modal fade w-100" id="editQuantityProduct" tabindex="-1" aria-labelledby="editQuantityProductLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-2 shadow border-0">
                <div class="modal-header">
                    <h5 class="modal-title text-center text-bold" id="editQuantityProductToggleLabel" style=" flex:5;">
                        Chỉnh sửa số lượng sản phẩm</h5>
                    <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="productContainer">

                    </div>

                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center btn btn-primary border-0 bg-color p-2 b-2 gap-2 w-100">

                        <div class="title text-white" id="update-quantity-to-db" data-bs-dismiss="modal"
                        aria-label="Close">
                            Lưu thay đổi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        {{-- modal show options --}}
        <div class="modal fade w-100" id="showOptionProduct" tabindex="-1" aria-labelledby="showOptionProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down">
                <div class="modal-content b-2  shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="showOptionProductToggleLabel" style=" flex:5;">
                            Các lựa chọn
                        </h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="showOptionItems">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-center btn btn-primary border-0 bg-color p-2 b-2 gap-2 w-100">
                            <div class="icon">
                                <i class="bx bx-plus font-size-24 text-white"></i>
                            </div>
                            <div class="title text-white">
                                Thêm lựa chọn cho sản phẩm
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal show information product --}}
        <div class="modal fade w-100" id="showInformationProduct" tabindex="-1"
            aria-labelledby="showInformationProductLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="showInformationProductToggleLabel"
                            style=" flex:5;">Thông tin sản phẩm</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="optionsContainer">

                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        {{-- modal show image preview product --}}
        <div class="modal fade w-100" id="showImagePreviewProduct" tabindex="-1"
            aria-labelledby="showImagePreviewProductLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="showImagePreviewProductToggleLabel"
                            style=" flex:5;">Hình ảnh đính kèm</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="optionsContainer">

                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        {{-- modal add category in product --}}
        <div class="modal fade w-100" id="uploadCategories" tabindex="-1"
            aria-labelledby="uploadCategoriesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-center text-bold" id="uploadCategoriesToggleLabel"
                            style=" flex:5;">Chỉnh sửa danh mục</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-3 pb-3">
                        <div class="form-group mb-3">
                            <label for="" class="text-secondary font-size-14 mb-2">Danh mục hiện tại:</label>
                            <div id="history-item" class="d-flex flex-wrap gap-1">

                            </div>
                        </div>

                        <div class="form-group d-none new-item-categories">
                            <label for="" class="text-secondary font-size-14 mb-2">Danh mục mới:</label>
                            <div id="new-item-categories" class="d-flex flex-wrap gap-1">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <div class="bg-color-subtle badge w-100 p-1 b-2 item d-flex hover-color justify-content-center gap-2 align-items-center"
                        data-bs-target="#selectCategories" data-bs-toggle="modal" data-bs-dismiss="modal" id="open-modal-select">
                            <div class="icon">
                                <i class="bx bx-plus font-size-24 text-white"></i>
                            </div>
                            <div class="title text-white">
                                Thêm danh mục
                            </div>
                        </div>

                        <div class="d-flex gap-2 d-none w-100" id="save-category">
                            <div class="btn btn-default bg-color-2 b-2 w-100" style="color:black;" data-bs-dismiss="modal"
                            aria-label="Close">Hủy</div>
                            <div class="btn btn-danger b-2 w-100" data-bs-dismiss="modal" id="save-category-server"
                            aria-label="Close">Lưu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade w-100" id="selectCategories" tabindex="-1"
            aria-labelledby="selectCategoriesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2 shadow border-0">
                    <div class="modal-header p-2">
                        <h5 class="modal-title text-center text-bold" id="selectCategoriesToggleLabel"
                            style=" flex:5;">Chọn danh mục</h5>
                        <button type="button" class="btn-close" style="flex:0.2;" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-3 pb-3">
                        <div class="form-group">
                            <div class="d-flex flex-wrap mt-3 gap-1" id="category-list">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <div class="bg-color-subtle badge w-100 p-2 b-2 item d-flex hover-color justify-content-center gap-2 align-items-center"
                        data-bs-target="#uploadCategories" data-bs-toggle="modal" data-bs-dismiss="modal" id="save-category-select">
                            Ok
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- modal confirm remove item --}}

        <div class="modal fade w-100" id="confirmationModal" style="z-index: 1101;" tabindex="-1"
            aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content b-2">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="confirmationModalLabel" style="flex: 5;">Bạn có chắc chắn
                            muốn xóa không?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="flex: 0.2;"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác.</p>
                    </div>
                    <div class="modal-footer p-1">
                        <div class="d-flex gap-2 w-100">
                            <div class="btn btn-default bg-color-2 b-2 w-100 hover-color" style="color:black;" data-bs-dismiss="modal"
                            aria-label="Close">Hủy</div>
                            <div class="btn btn-danger b-2 w-100" data-bs-dismiss="modal"
                            aria-label="Close" id="confirmDeleteButton">Xóa</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- modal confirm remove product --}}

        <div class="modal fade w-100" style="z-index: 1101;" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" >
              <div class="modal-content b-2 border-0 shadow">
                <div class="modal-header p-2">
                  <h5 class="modal-title text-center" id="confirmDeleteModalLabel" style="flex: 5;">Xác nhận xóa sản phẩm</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="flex: 0.2;"></button>
                </div>
                <div class="modal-body">
                  Bạn có chắc chắn muốn xóa sản phẩm này không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer p-1">
                    <div class="d-flex gap-2 w-100">
                        <div class="btn btn-default bg-color-2 b-2 w-100 hover-color" style="color:black;" data-bs-dismiss="modal"
                        aria-label="Close">Hủy</div>
                        <div class="btn btn-danger b-2 w-100" data-bs-dismiss="modal"
                        aria-label="Close">Lưu</div>
                    </div>
                </div>
              </div>
            </div>
          </div>


    </div>

    <script>
        let allProducts = [];
        var page = 1;
        let noMoreProduct = false;
        let index = 0;
        let loading = false;
        getProduct(true);

        actionOnScrollBottom(window, function() {
            getProduct();
        })

        function getProduct(newPage = false) {
            if (newPage) {
                page = 1;
                noMoreProduct = false;
                index = 0;
                allProducts = [];
            }

            if (!noMoreProduct && !loading) {

                loading = true;
                $('#loading-product-append').removeClass('d-none');
                $.ajax({
                    url: "{{ route('admin.get.product') }}",
                    method: 'GET',
                    data: {
                        page: page,
                    },
                    success: function(res) {
                        allProducts = allProducts.concat(res.products);
                        if (page == 1) {
                            var html = '';

                            res.products.forEach(element => {
                                index++;
                                html += renderRow(element);
                            });


                            $('.told').html(html)
                        } else {
                            var html = '';

                            res.products.forEach(element => {
                                index++;
                                html += renderRow(element);
                            });


                            $('.told').append(html)
                        }

                        noMoreProduct = page >= res?.last_page;
                        if (!noMoreProduct) {
                            page++;
                        }
                        loading = false;

                        $('#loading-product-append').addClass('d-none');
                    }
                })
            }
        }


        function formatPrice(price) {
            if (price != null) {
                return price.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }
            return '';
        }

        function handlePrice(price, sale) {
            const discountedPrice = price - (price * (sale / 100));
            return formatPrice(discountedPrice);
        }

        function formatDate(datetime) {
            return new Date(datetime).toLocaleString();
        }

        function renderRow(item) {
            const categories = item.categories.map(category => `<div class="badge bg-warning-subtle text-warning">${category.name}</div>`).join('');

            var price = '';
            var option = '';
            var quantity = '';
            if (item.option_type == 0) {
                quantity = `<td id="quantity-product-render">
                    ${item.quantity}
                </td>`;
                option = `<div class="d-flex align-items-center hover-underline" id="showOptionProductClick" data-product-id="${item.id}" data-bs-toggle="modal"
                        href="#showOptionProduct" role="button">
                        <div class="icon mt-2">
                            <i class='bx bx-show font-size-24 text-success '></i>
                        </div>
                        <div class="title">
                            <span class="badge text-success bg-success-subtle hover-underline">
                                Xem
                            </span>
                        </div>
                    </div>`;


                const minPriceFormatted = handlePrice(item.min_price, 0)
                const maxPriceFormatted = handlePrice(item.max_price, 0)
                const minSale = item.min_sale;
                const maxSale = item.max_sale;

                // Check if max_price is equal to min_price
                if (item.min_price == item.max_price) {
                    price = `
                        <td class="text-bold text-danger">${minPriceFormatted}</td>
                        <td class="text-bold text-danger">${minSale}%</td>
                        <td class="text-bold text-danger">${handlePrice(item.min_price, minSale)}</td>
                    `;
                } else {

                    price = `
                        <td class="text-bold text-danger">${minPriceFormatted} ~ ${maxPriceFormatted}</td>
                        <td class="text-bold text-danger">${minSale}% ~ ${maxSale}%</td>
                        <td class="text-bold text-danger">${handlePrice(item.min_price, minSale)} - ${handlePrice(item.max_price, maxSale)}</td>
                    `;
                }

            } else {
                quantity = `<td id="quantity-product-render">
                    ${item.quantity}
                </td>`;
                option = `<span class="bg-danger-subtle text-danger badge">Không có lựa chọn</span>`
                price = `
                <td class="text-bold text-danger">
                    ${formatPrice(item.price)}
                </td>
                <td class="text-bold text-danger">
                    ${item.sale}%
                </td >
                <td class="text-bold text-danger">
                    ${handlePrice(item.price, item.sale)}
                </td>
                `;
            }


            return `
            <tr>
                <th scope="row" class="badge text-success bg-success-subtle">${index}</th>
                <td >
                    <img width="100px" class="image-product b-2"
                        src="${item.poster}"
                        alt="">
                </td>
                <td >
                    <span class="">
                        ${item.title}
                    </span>
                </td>
                ${price}
                <td class="render-option text-center ">
                    ${option}
                </td>
                <td >
                    <div class="d-flex align-items-center hover-underline" data-bs-toggle="modal"
                        href="#modal-information-${item.id}" role="button">
                        <div class="icon mt-2">
                            <i class='bx bx-show font-size-24 text-success text-center'></i>
                        </div>
                        <div class="title text-center">
                            <span class="badge text-success bg-success-subtle hover-underline">
                                Xem
                            </span>
                        </div>
                    </div>
                </td>
                <td >
                    <div class="d-flex align-items-center hover-underline" href="#modal-image-product-${item.id}"
                        data-bs-toggle="modal" role="button">
                        <div class="icon mt-2">
                            <i class='bx bx-show font-size-24 text-success '></i>
                        </div>
                        <div class="title">
                            <span class="badge text-success bg-success-subtle hover-underline">
                                Xem
                            </span>
                        </div>
                    </div>
                </td>
                <td id="success-new-categories" data-product-id="${item.id}">
                    ${categories}
                </td>
                ${quantity}
                <td>
                    ${item.quantity_saled}
                </td>
                <td>
                    ${item.total_rate}
                </td>
                <td>
                    ${formatDate(item.created_at)}
                </td>
                <td>
                    <span class="btn btn-primary b-2 mb-1 btn-edit-item-product" data-product-id="${item.id}"><i class='bx bx-edit-alt text-white' ></i></span>
                    <span class="btn btn-danger b-2 mb-1" id="delete-product" data-product-id="${item.id}"><i class='bx bxs-trash text-white' ></i></span>
                </td>

            </tr>`;
        }

        var productEditFocus = 0;
        let fileIdToDelete = null; // Store the file ID to delete
        let previousModals = [];
        let confirmationModalInstance;
        let uploadedImageUrls = [];
        var list_checked;

        $(document).on('click', '.btn-edit-item-product', function() {
            productEditFocus = $(this).data('product-id');
            fileIdToDelete = null;
            previousModals = [];
            uploadedImageUrls = [];
            list_checked = [];
            var editProductModal = new bootstrap.Modal($('#editProductModal')[0]);
            editProductModal.show();
        });


        function getProductById() {
            const product = allProducts.find(p => p.id === productEditFocus);

            if (product) {
                return product;
            }
        }


        $('#editIamgePreviewclick').click(function() {
            var product = getProductById();

            if (product.preview_images.length > 0) {
                var html = '';
                product.preview_images.forEach(item => {
                    html += `<div class="col-6 col-md-3 p-1 position-relative">
                                    <img src="${item.image}" width="100%" class="image-product b-2 " alt="">
                                    <div class="btn-close p-2 b-50 bg-color-2 top-0 right-0 position-absolute" id="btn-delete-image-preview" data-file-id="${item.file_id}" data-product-id="${item.product_id}">

                                    </div>
                                </div>`;
                });

                $('#image-preview-product').html(html);
            }
        })




        // Show confirmation modal when the button is clicked
        $(document).on('click', '#btn-delete-image-preview', function() {
            // Store the file ID and product ID from data attributes
            fileIdToDelete = $(this).data('file-id');
            const productId = $(this).data('product-id');

            // Hide all currently shown modals and store their instances
            $('.modal.show').each(function() {
                previousModals.push({
                    id: $(this).attr('id'),
                    instance: bootstrap.Modal.getInstance(this)
                });
                var modalInstance = bootstrap.Modal.getInstance(this);
                modalInstance.hide();
            });


            // Show the confirmation modal
            confirmationModalInstance = new bootstrap.Modal($('#confirmationModal')[0]);
            confirmationModalInstance.show();
        });

        // Handle delete confirmation
        $(document).on('click', '#confirmDeleteButton', function() {
            console.log('click');
            deleteItem(fileIdToDelete);

            confirmationModalInstance.hide();
        });

        // Restore previously shown modals if the confirmation modal is dismissed
        $('#confirmationModal').on('hidden.bs.modal', function() {
            previousModals.forEach(function(modal) {
                var modalElement = $('#' + modal.id);
                var newModalInstance = new bootstrap.Modal(modalElement[0]);
                newModalInstance.show();
            });

            previousModals = [];
        });

        // Function to perform the delete action
        function deleteItem(fileId) {
            $.ajax({
                url: "{{ route('admin.delete.imagePreview') }}", // Update with your route
                method: 'DELETE',
                data: {
                    file_id: fileId,
                    product_id: productEditFocus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {

                    $(`#btn-delete-image-preview[data-file-id="${fileId}"][data-product-id="${productId}"]`)
                        .closest('.col-6').remove();
                },
                error: function(xhr) {
                    // Handle error (e.g., show an error message)
                    console.log('Error deleting item:', xhr);
                }
            });
        }


        $('#click-upload').click(function() {

            $('#inp-upload-image-preview').click();
        })



        // Handle file selection and display previews
        $('#inp-upload-image-preview').on('change', function(event) {
            const files = event.target.files;
            const previewContainer = $('#image-preview-container');

            // Reset the preview container and the file array
            previewContainer.empty();
            uploadedImageFiles = [];

            // Check if any files are selected
            if (files.length > 0) {
                $('#footer-modal-add-image').removeClass('d-none');
            } else {
                $('#footer-modal-add-image').addClass('d-none');
            }

            // Loop through selected files
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Check if the file is an image
                if (file && file.type.startsWith('image/')) {
                    // Add file to the array
                    uploadedImageFiles.push(file);

                    // Create a FileReader to read the image file
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Create a container for each image and close button
                        const imageContainer = $('<div>', {
                            class: 'image-preview-container position-relative d-inline-block m-2'
                        });

                        // Create an image element
                        const img = $('<img>', {
                            src: e.target.result,
                            alt: 'Image Preview',
                            class: 'img-thumbnail image-product b-2',
                            width: '120px' // Adjust width as needed
                        });

                        // Create a close button
                        const closeButton = $('<button>', {
                            type: 'button',
                            class: 'btn-close position-absolute top-0 end-0 p-2 bg-color-2 b-50 m-2',
                            'aria-label': 'Close'
                        }).on('click', function() {
                            imageContainer.remove();
                            uploadedImageFiles = uploadedImageFiles.filter(f => f !==
                                file); // Remove the file from the array
                            checkImageCount(); // Check image count after removal
                        });

                        // Append image and close button to the container
                        imageContainer.append(img).append(closeButton);

                        // Append the image container to the preview container
                        previewContainer.append(imageContainer);
                    };

                    // Read the image file as a data URL
                    reader.readAsDataURL(file);
                }
            }
        });

        function checkImageCount() {
            if (uploadedImageFiles.length < 1) {
                $('#btn-save-images').addClass('d-none');
            } else {
                $('#btn-save-images').removeClass('d-none');
            }
        }


        $('#btn-save-images').on('click', function() {
            const formData = new FormData();
            uploadedImageFiles.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });
            formData.append('product_id', productEditFocus);
            formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

            $.ajax({
                url: "{{ route('admin.save.imagesPreview') }}", // Update with your route
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    $('#image-preview-container').empty();
                    uploadedImageFiles.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = $('<img>')
                                .attr('src', e.target.result)
                                .addClass('img-thumbnail image-product b-2')
                                .css('width', '120px'); // Set width using .css() method
                            $('#image-preview-container').append(img);
                        };
                        reader.readAsDataURL(file);
                    });
                    uploadedImageFiles = [];
                    $('#btn-save-images').addClass('d-none');
                },
                error: function(response) {
                    $('#upload-progress').text('Upload Failed');
                }
            });
        });

        var attribute = @json($attribute);

        $("#editOptionProductClick").click(function() {
            var product = getProductById();

            var option = '';
            attribute.forEach(item => {
                option += `<option value="${item.id}">${ item.name }</option>`;
            })
            var variations = product.uniqueAttributes;

            $('#optionsContainer').empty();

            // Render the options
            for (var attributeName in variations) {
                if (variations.hasOwnProperty(attributeName)) {
                    variations[attributeName].forEach(function(variation, index) {
                        var optionsHtml = `
                            <div class="mb-3">
                                <label class="form-label">
                                    <select class="form-select attribute-name-select" value="${variation.attribute_id}">
                                        ${option}
                                    </select>
                                </label>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <img src="${variation.variation.poster}" id="preview-poster-option"  data-index-id="${variation.variation.id}" width="100px" class="poster image-product b-2"/>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group p-3 border-1 border-label b-2 hover-color" id="update-poster-option" data-index-id="${variation.variation.id}">
                                            <div class="icon text-center">
                                                <i class="bx bx-upload text-center text-secondary" style="font-size: 30px;"></i>
                                            </div>
                                            <div class="title text-center text-secondary">
                                                Thay đổi ảnh
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="update-image-option[${variation.variation.id}]" class="outline-0 border-color b-2" id="inp-update-image-option" hidden>
                                    <div class="col-md-3">
                                        <label class="form-label text-secondary font-size-14">Tên lựa chọn</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 attribute-value" name="attribute_value[${variation.variation.id}]" value="${variation.attribute_value}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Trọng lượng(đơn vị: gam)</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 weight" name="weight[${variation.variation.id}]" value="${variation.variation.weight}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Rộng</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 width" name="width[${variation.variation.id}]" value="${variation.variation.width}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Dài</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 length" name="length[${variation.variation.id}]" value="${variation.variation.length}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Cao</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 height" name="height[${variation.variation.id}]" value="${variation.variation.height}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Giá(đ)</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 price" name="price[${variation.variation.id}]" value="${variation.variation.price}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Giảm giá(%)</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 sale" name="sale[${variation.variation.id}]" value="${variation.variation.sale}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label text-secondary font-size-14">Chất liệu(%)</label>
                                        <input type="text" class="outline-0 border-color b-2 w-100 p-2 sale" name="sale[${variation.variation.id}]" value="${variation.variation.material}">
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#optionsContainer').append(optionsHtml);


                        $(`#optionsContainer .mb-3:last-child .attribute-name-select`).val(variation.attribute_id);
                    });
                }
            }
        })


        var number = 0;
        $(document).on("click", '#update-poster-option', function(){
            number = $(this).data('index-id');
            $(`input[name="update-image-option[${number}]"]`).click();
        })

        $(document).on('change', '#inp-update-image-option', function(e){
            const file = e.target.files[0];
            if(file){
                const reader = new FileReader();

                reader.onload = function(event) {
                    $(`#preview-poster-option[data-index-id="${number}"]`).attr('src', event.target.result).show();
                };

                reader.readAsDataURL(file);
            }
        })

        $(document).on('click', '#update-option-to-db', function() {
            var product = getProductById(); // Giả sử đây là hàm để lấy sản phẩm từ server
            var variations = product.uniqueAttributes;

            // Kiểm tra dữ liệu
            console.log('Variations:', variations);

            // Thu thập dữ liệu từ HTML
            var htmlVariations = [];
            $('#optionsContainer .mb-3').each(function() {
                var $element = $(this);

                // Lấy index từ data-index-id
                var index = $element.find('[data-index-id]').data('index-id');

                // Lấy tên thuộc tính (attributeName) từ select
                var attributeName = $element.find('select.attribute-name-select').find(":selected").text() || '';
                var attributeId = $element.find('select.attribute-name-select').find(":selected").val() || '';

                // Lấy dữ liệu từ các trường
                var attributeValue = $element.find('input[name^="attribute_value"]').val() || '';
                var poster = $element.find('.poster').attr('src') || '';
                var weight = $element.find('input[name^="weight"]').val() || '';
                var width = $element.find('input[name^="width"]').val() || '';
                var length = $element.find('input[name^="length"]').val() || '';
                var height = $element.find('input[name^="height"]').val() || '';
                var price = $element.find('input[name^="price"]').val() || '';
                var sale = $element.find('input[name^="sale"]').val() || '';

                // Kiểm tra xem các trường dữ liệu có tồn tại không
                if (index !== undefined) {
                    htmlVariations.push({
                        index: index,
                        attribute_name: attributeName,
                        attribute_id: attributeId,
                        attribute_value: attributeValue,
                        weight: weight,
                        width: width,
                        length: length,
                        height: height,
                        price: price,
                        sale: sale,
                        poster: poster,
                    });
                }
            });

            // Kiểm tra dữ liệu thu thập được

            // So sánh dữ liệu từ HTML với dữ liệu từ variations
            var updatedVariations = [];

            // Tạo một map từ index đến các items trong variations để dễ dàng tra cứu
            var variationsMap = {};
            for (const [option, items] of Object.entries(variations)) {
                items.forEach(variation => {
                    variationsMap[variation.variation.id] = { ...variation, attribute_name: option };
                });
            }

            // So sánh dựa trên index
            htmlVariations.forEach(matchingItem => {
                var variation = variationsMap[matchingItem.index];

                if (variation) {
                    var changes = {};

                    if (matchingItem.attribute_name != variation.attribute_name) {
                        changes.attribute_name = matchingItem.attribute_name;
                    }

                    if (matchingItem.attribute_id != variation.attribute_id) {
                        changes.attribute_id = matchingItem.attribute_id;
                    }
                    if (matchingItem.attribute_value != variation.attribute_value) {
                        changes.attribute_value = matchingItem.attribute_value;
                    }
                    if (matchingItem.weight != variation.variation.weight) {
                        changes.weight = matchingItem.weight;
                    }
                    if (matchingItem.width != variation.variation.width) {
                        changes.width = matchingItem.width;
                    }
                    if (matchingItem.length != variation.variation.length) {
                        changes.length = matchingItem.length;
                    }
                    if (matchingItem.height != variation.variation.height) {
                        changes.height = matchingItem.height;
                    }
                    if (matchingItem.price != variation.variation.price) {
                        changes.price = matchingItem.price;
                    }
                    if (matchingItem.sale != variation.variation.sale) {
                        changes.sale = matchingItem.sale;
                    }
                    if (matchingItem.poster != variation.variation.poster) {
                        changes.poster = matchingItem.poster;
                    }

                    if (Object.keys(changes).length > 0) {
                        updatedVariations.push({
                            id: matchingItem.index,
                            changes: changes
                        });
                    }
                }
            });

            // Xử lý các dữ liệu đã được chỉnh sửa
            // Xử lý các dữ liệu đã được chỉnh sửa
            if (updatedVariations.length > 0) {

                // Gửi dữ liệu đã chỉnh sửa lên server
                $.ajax({
                    url: '{{ route('admin.update.updateOption') }}', // Cập nhật URL upload của bạn
                    method: 'POST',
                    data: {
                        product_id: product.id,
                        updatedVariations: updatedVariations,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Cập nhật thành công.');

                        Object.keys(variations).forEach(attributeName => {
                            let attributeGroup = variations[attributeName] || [];
                            console.log(attributeGroup);
                            updatedVariations.forEach(updatedItem => {
                                const variationIndex = attributeGroup.findIndex(v => v.variation.id == updatedItem.id);

                                if (variationIndex !== -1) {
                                    attributeGroup[variationIndex] = updatedItem;
                                } else {
                                    attributeGroup.push(updatedItem);
                                }
                            });

                            variations[attributeName] = attributeGroup;
                        });

                        console.log('Updated variations:', variations);
                    },
                    error: function(xhr, status, error) {
                        alert('Đã xảy ra lỗi.');
                    }
                });
            } else {
                alert('Không có thay đổi nào.');
            }
        });




        $(document).on('click', '#showOptionProductClick', function() {
            productEditFocus = $(this).data('product-id');

            var product = getProductById();
            var variations = product.uniqueAttributes;


            $('#showOptionItems').empty();


            for (var Attribute_name in variations) {
                if (variations.hasOwnProperty(Attribute_name)) {
                    variations[Attribute_name].forEach(function(variation, index) {
                        var html = `<div class="product d-flex gap-2 pb-3 mb-2" style="border-bottom: 1px solid #ccc; ">
                            <div class="first">
                                <div class="product-poster">
                                    <img width="100px" src="${variation.variation.poster}" class="image-product b-2" alt="">
                                </div>
                            </div>
                            <div class="second d-flex flex-column gap-2 w-100">
                                <div class="product-title">
                                    ${variation.attribute_value}
                                </div>
                                <div class="information">
                                    <div class="form-group">
                                        <label for="" class="font-size-14 text-secondary">
                                            Kích thước (đơn vị: cm):
                                       </label>
                                       <span>${variation.variation.length} x ${variation.variation.width} x ${variation.variation.height}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="" class="font-size-14 text-secondary">
                                            Trọng lượng (đơn vị: gam):
                                       </label>
                                       <span>${variation.variation.weight}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="font-size-14 text-secondary">
                                            Chất liệu:
                                       </label>
                                       <span>${variation.variation.material}</span>
                                    </div>
                                </div>
                                <div class="price d-flex justify-content-between">
                                    <div class="first d-flex gap-2 align-items-center">
                                        <div class="price-saled text-bold text-danger">
                                            ${handlePrice(variation.variation.price, variation.variation.sale)}
                                        </div>
                                        <div class="text-secondary text-decoration-line-through">
                                           ${handlePrice(variation.variation.price, 0)}
                                        </div>
                                    </div>
                                    <div class="last">
                                        <div class="quantity">
                                            <span>Tồn kho: ${variation.variation.quantity}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        $('#showOptionItems').append(html);
                    })
                }
            }
        })

        $('#uploadCategoriesMenu').click(function(){
            var product = getProductById();

            var categories = product.categories;
            $('#history-item').empty();
            $(".new-item-categories").addClass('d-none');
            $("#new-item-categories").empty();
            $('#open-modal-select').removeClass('d-none');
            $('#save-category').addClass('d-none');
            var html = '';
            categories.forEach(function(category){
                html += `<span class="border-1 p-2 b-3 text-color hover-color">${category.name}</span>`;
            })

            $('#history-item').html(html);

        })

        var listCategories = @json($categories);

        $('#open-modal-select').click(function(){
            var product = getProductById();
            var categories = product.categories;

            $('#category-list').empty();

            listCategories.forEach(function(category) {
                var checked =  '';
                categories.forEach(function(item){
                    if(category.id == item.id){
                        checked = 'checked';
                    }
                })
                $('#category-list').append(`
                    <div class="block-checkbox">
                        <input type="checkbox" name="check-box-category" ${checked} id="category-${category.id}" value="${category.id}">
                        <label for="category-${category.id}" class="p-2 b-3 border-label">${category.name}</label>
                    </div>
                `);
            });

        })

        $('#save-category-select').click(function(){
            var product = getProductById();
            var categories = product.categories;

            list_checked = $('input[name="check-box-category"]:checked').map(function() {
                return $(this).val();
            }).get();

            if(list_checked.length > 0){
                $('#save-category').removeClass('d-none');
                $('#open-modal-select').addClass('d-none');
                $('.new-item-categories').removeClass('d-none');

                listCategories.forEach(function(item){
                    if(list_checked.includes(item.id.toString())){
                        $('#new-item-categories').append(`<span class="border-1 p-2 b-3 text-color hover-color">${item.name}</span>`);
                    }
                });
            }
        });

        $('#save-category-server').click(function(){
            $.ajax({
                url: "{{ route('admin.add.addCategoriesProduct') }}",
                method: "POST",
                data:{
                    listCategoriesId: list_checked,
                    product_id: productEditFocus,
                    _token: '{{ csrf_token() }}',
                },
                success:function(res){
                    var product = getProductById(); // Ensure this returns the product object correctly
                    const productId = productEditFocus;

                    // Map over checked category IDs to find the corresponding category objects
                    const categoriesHtml = list_checked.map(id => {
                        const category = listCategories.find(category => category.id == id);
                        if (category) {
                            // Add category to the product's categories if not already present
                            if (!product.categories.find(cat => cat.id == category.id)) {
                                product.categories.push(category);
                            }
                            return `<div class="badge bg-warning-subtle text-warning">${category.name}</div>`;
                        }
                        return '';
                    }).join('');

                    $(`#success-new-categories[data-product-id="${productEditFocus}"]`).html(categoriesHtml);

                    list_checked = [];

                }
            })
        })


        $(document).on('click', '#delete-product', function(){
            productEditFocus = $(this).data('product-id');

            $('#confirmDeleteModal').modal('show');
        })

        var initialQuantities = [];

        $('#editQuantityProductClick').click(function(){
            var product = getProductById();
            $('#productContainer').empty();
            var html = '';
            if(product.option_type == 0){
                var variations = product.uniqueAttributes;
                for (var Attribute_name in variations) {
                    if (variations.hasOwnProperty(Attribute_name)) {
                        variations[Attribute_name].forEach(function(variation, index) {
                            html += `<div class="product d-flex gap-2 pb-3 mb-2" style="border-bottom: 1px solid #ccc; ">
                                <div class="first">
                                    <div class="product-poster">
                                        <img width="100px" src="${variation.variation.poster}" class="image-product b-2" alt="">
                                    </div>
                                </div>
                                <div class="second d-flex flex-column gap-2 w-100">
                                    <div class="product-title">
                                        ${variation.attribute_value}
                                    </div>
                                    <div class="information">
                                        <div class="form-group">
                                            <label for="quantity-product" class="text-secondary font-size-14">
                                                Số lượng sản phẩm:
                                            </label>

                                            <input type="number" name="quantity-product" class="w-100 b-2 border-color p-2 outline-0" id="quantity-product" data-option-id="${variation.variation.id}" value="${variation.variation.quantity}" placeholder="số lượng sản phẩm">
                                        </div>
                                    </div>

                                </div>
                            </div>`;

                            // Lưu trữ dữ liệu ban đầu
                            initialQuantities.push({
                                optionId: variation.variation.id,
                                quantity: variation.variation.quantity
                            });
                        })
                    }
                }
            }
            else{
                html += `<div class="form-group">
                        <label for="quantity-product" class="text-secondary font-size-14">
                            Số lượng sản phẩm:
                        </label>

                        <input type="number" name="quantity-product" class="w-100 b-2 border-color p-2 outline-0" id="quantity-product" data-option-id="" value="${product.quantity}" placeholder="số lượng sản phẩm">
                    </div>`;

                     // Lưu trữ dữ liệu ban đầu
                initialQuantities.push({
                    optionId: null, // Không có optionId cho trường hợp này
                    quantity: product.quantity
                });
            }
            $('#productContainer').html(html);
        })

        $('#update-quantity-to-db').click(function() {
            var changedQuantities = [];

            // Thu thập dữ liệu hiện tại và so sánh với dữ liệu ban đầu
            $('input[name="quantity-product"]').each(function(index) {
                var newQuantity = $(this).val();
                var optionId = $(this).data('option-id');

                // So sánh với dữ liệu ban đầu
                if (newQuantity != initialQuantities[index].quantity) {
                    changedQuantities.push({
                        optionId: optionId,
                        quantity: newQuantity
                    });
                }
            });

            // Chỉ gửi dữ liệu đã thay đổi nếu có
            if (changedQuantities.length > 0) {
                $.ajax({
                    url: '{{ route('admin.update.quantity') }}',  // URL của endpoint server
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        productId: productEditFocus,
                        changes: changedQuantities,
                        _token: '{{ csrf_token() }}'
                    }),
                    success: function(response) {
                        updateProductQuantities(changedQuantities);
                    },
                    error: function(xhr, status, error) {
                        console.error('Đã xảy ra lỗi khi cập nhật dữ liệu:', error);
                    }
                });
            } else {
                console.log('Không có dữ liệu thay đổi.');
            }
        });


        function updateProductQuantities(changedQuantities) {
            // Lấy dữ liệu sản phẩm mới
            var updatedProduct = getProductById();

            if (updatedProduct.option_type == 0) {
                var variations = updatedProduct.uniqueAttributes;

                // Duyệt qua các `variations` và cập nhật số lượng
                for (var Attribute_name in variations) {
                    if (variations.hasOwnProperty(Attribute_name)) {
                        variations[Attribute_name].forEach(function(variation, index) {
                            // Tìm biến thể tương ứng và cập nhật số lượng
                            var match = changedQuantities.find(function(c) {
                                return c.optionId === variation.variation.id;
                            });

                            if (match) {
                                variation.variation.quantity = match.quantity;
                            }
                        });
                    }
                }
            } else {
                // Cập nhật số lượng sản phẩm nếu không có tùy chọn
                if (changedQuantities.length > 0) {
                    updatedProduct.quantity = changedQuantities[0].quantity;
                }
            }

        }


    </script>
@endsection
