<?php

use App\Http\Controllers\admin\HomeController as AdminHomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSendController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RevenueReportController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::get('/', function () {
    
    return view('home');
})->name('user.home');


// Group for HomeController routes
Route::controller(HomeController::class)->group(function () {
    Route::get('/test', 'flashNotification')->name('user.flashNotification');
   
    Route::get('/', 'getThumbnail')->name('user.getTumbnail');
});

// Group for LoginController routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
});

// Group for DeviceController routes
Route::controller(DeviceController::class)->group(function () {
    Route::get('/up-scription-ids', 'Subcription_Id')->name('user.addsubsription');
});

// Group for ProductController routes
Route::controller(ProductController::class)->group(function () {
    Route::post('/add-to-card', 'addCart')->name('user.addcart');
});

Route::controller(NotificationController::class)->group(function(){
    Route::get('/get/notification', 'getUserNotificationUser')->name('notification.get.user');
    Route::get('/count-notification', 'countNotification')->name('notification.get.countNotification');

    Route::post('/update-status-notification', 'markSeeNotification')->name('notification.post.markSee');

});

// Group for UserController routes
Route::controller(UserController::class)->group(function () {


    // cart item
    Route::get('/cart', 'cart')->name('user.viewCart');
    Route::post('update-cart', 'updateCart')->name('user.updateCart');
    Route::post('trans-cart', 'transCartProduct')->name('user.transCart');
    Route::get('/quantity/cart', 'getCartUser')->name('user.get.quantityCart');


    // Pay and order
    Route::post('viewpay', 'viewPay')->name('user.viewPay');
    Route::get('/price', 'getPrice')->name('user.cartPrice');
    Route::get('/order', 'createOrder')->name('user.createOrder')->middleware('handleBeforeOrder');


    // add phone and verify phone number
    Route::get('/add/phone-number', 'viewPhoneNumber')->name('user.view.addPhoneNumber');
    Route::post('/user/add-Phone-Number','addPhoneNumber')->name('user.post.PhoneNumber');

    Route::post("add-phone-number", "addNumberPhone")->name('user.addPhoneNumber');
    Route::post("verify-phone-number", "verifyPhoneNumber")->name("user.VerifyPhoneNumber");
    Route::get('/verify-phone', 'showVerificationForm')->name('verify.phone');
    Route::post('/verify-phone', 'verifyPhoneNumberForm')->name('verify.phone.submit');
    Route::get('/vnpay-response', 'response')->name('vnpay.response');
    Route::get('/rePay/{id}', 'rePayment')->name('user.rePayment')->middleware('handleBeforeOrder');
    Route::post('edit-informaion', 'editInformation')->name('user.editInformation');

    Route::get('get-farouvite', 'viewPageFavourite')->name('user.getviewPageFavourite');
    Route::get('get-product-favourite', 'getProductFavourite')->name('user.getProductFavourite');


    Route::get('get-status-order', 'getStatusOrder')->name('user.getStatusOrder');
    Route::post('/cancel-order', 'cancelOrderProduct')->name('user.cancelOrderProduct');

    // address 

    Route::post('add-address', 'addAddress')->name("user.addAddress");
    Route::get('get-address-user', 'getAddress')->name("user.getAddress");
});


Route::controller(ProductController::class)->group(function(){
    Route::get('/product/{order_code}/detail-order-product', 'getDetailOrderProduct')->name('product.get.detailOrderProduct');

});

Route::controller(ProfileController::class)->group(function(){
    Route::get('/setting-account', 'viewSettingAccount')->name('user.settingAccount');
    Route::get('/queue-verify-payment', 'viewQueueVerify')->name('user.viewQueueVerify');
    Route::get('/queue-pick-order', 'viewQueuePickOrder')->name('user.viewQueuePickOrder');
    Route::get('/queue-shipping-order', 'viewQueueShipping')->name('user.viewQueueShipping');
    Route::get('/history-user-order', 'viewHistoryOrder')->name('user.viewHistoryOrder');
    Route::get('/view-user-rating', 'viewRatingProduct')->name('user.viewRatingProduct');
});


Route::get("/test", [Controller::class, 'testPay']);
Route::get("/testToken", [Controller::class, 'testToken']);

Route::get('filler-product', [ProductController::class ,'FilterProduct'])->name('user.filterProduct');


Route::middleware('checkAdmin')->group(function(){

    // category
    Route::get('/admin/categories', [CategoriesController::class, 'getCategory'])->name('admin.categories');
    Route::get('/admin/thumbnail', [CategoriesController::class, 'thumbailCategory'])->name('admin.thumbnailCategory');

    Route::post('/admin/addThumbnailCategory', [CategoriesController::class, 'createContentThumbnailCategory'])->name('admin.createContentThumbnailCategory');

    Route::post('/admin/remove-category',[CategoriesController::class, 'removeCategory'])->name('admin.post.removeCategory');
    // end  container cateogory



    Route::get('/admin', [AdminHomeController::class, 'index'])->name('home');

    Route::get('/admin/user', [UserController::class ,'index'])->name('users.index');

    Route::get('search_user', [UserController::class, 'SearchUser'])->name('search_user');

    Route::get('getUser', [UserController::class, 'getUser'])->name('getUser');

    Route::post('add-categories', [CategoriesController::class, 'addCategories'])->name('addcategory');
    Route::post('/remove-category', [CategoriesController::class, 'removeCategory'])->name('admin.removeCategory');
    Route::post('/add-categories-product', [CategoriesController::class, 'addCategoriesProduct'])->name('admin.add.addCategoriesProduct');

    Route::get('/admin/products', [ProductController::class, 'viewPageProduct'])->name('admin.products');

    Route::get('/new-product',[ProductController::class ,'viewNewProduct'])->name('admin.addproducts');

    Route::post('/add-product', [ProductController::class, 'addProduct'])->name('admin.addItemProduct');


    Route::get('/provinces', [HomeController::class, 'getProvinces']);


    Route::get('/view-notification', function(){
        return view('admin.pages.notification');
    })->name('admin.notification');

    Route::post('add-notificaion',[NotificationController::class, 'addNotificaion'])->name('addmin.addNotification');
    Route::get('get-notification', [NotificationController::class, 'getNotification'])->name('admin.getNotification');

    Route::get('get-product', [ProductController::class, 'getProduct'])->name('admin.get.product');
    Route::delete('/admin-delete-image-preview',[ProductController::class,'deleteImage'])->name('admin.delete.imagePreview');
    Route::post('/admin-add-image-preview', [ProductController::class ,'addImagePreview'])->name('admin.save.imagesPreview');
    Route::get('/job-progress/{jobId}', [ProductController::class,  'getProgress'])->name('admin.get.progressUpload');
    Route::post('update-option', [ProductController::class, 'updateOption'])->name('admin.update.updateOption');
    Route::post('update-quantity', [ProductController::class, 'updateQuantity'])->name('admin.update.quantity');

    Route::get('/admin/order', [PageController::class, 'viewOrder'])->name('admin.view.orderProduct');
    Route::get('/admin/getitemorder', [OrderController::class, 'getOrder'])->name('admin.get.orderProduct');
    Route::get('/admin/print/order', [OrderController::class,  'PrintBillOrderTOA5'])->name('admin.print.billtoA5');
    Route::post('/admin/cancel/order', [OrderController::class, 'cancelOrderProduct'])->name('admin.cancel.orderProduct');


    // layout 
    Route::get("/admin/manager-layout", [PageController::class, 'viewlayout'])->name('admin.view.layout');



});

Route::get("/user/notification", [NotificationController::class, "notificationUser"])->name('user.notificationUser');

Route::get('/information', [UserController::class, 'informationUser'])->name('user.information');

Route::get('/category/{slug}',[CategoriesController::class, 'getCategoryUser'])->name('user.category');

Route::get('/select-User',[UserController::class, 'selectUser'])->name('admin.selectUser');



Route::get('/search_product', [ProductController::class, 'searchPerpage'])->name('user.searchProduct');
Route::get('/product/{id}', [ProductController::class, 'detailProduct'])->name('user.detailproduct');


Route::get('product/{id}/recommendation', [ProductController::class, 'RecomendationProduct'])->name('user.recommendationProduct');
Route::get('get-many-recomendation', [ProductController::class, 'ManyRecomendationProduct'])->name('product.ManyRecomendationProduct');
Route::get('Menuoption',[ProductController::class, 'getOptionProduct'])->name('user.getOptionProduct');


// address 
Route::get('get-district',[HomeController::class, 'getDistrict'])->name('user.getDistrict');
Route::get('get-ward', [HomeController::class, 'getWard'])->name("user.getWard");
Route::post('add-address', [UserController::class, 'addAddress'])->name("user.addAddress");


Route::get('/hotTrend', [ProductController::class, 'hotTrend'])->name('user.getHostTrend');
Route::post('/calculate-shipping-fee', [ProductController::class, 'calculateShippingFee'])->name('user.handleShip');

Route::post('/favourite-product', [ProductController::class, 'addFavouriteProduct'])->name("user.favourite.product");
Route::get('get-favourite-product', [ProductController::class, 'getFavouriteProduct'])->name("user.getFavouriteProduct");


Route::controller(RevenueReportController::class)->group(function(){
    Route::get('/report/revenue', 'getRevenueReport')->name('report.get.revenue');
});
// http://127.0.0.1:8000/send-noti?contents=test notification&subscription_ids=46830c63-799d-4dfa-aeee-1538ffac73a4&url=http://127.0.0.1:8000/
