<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserRolesController;
use App\Http\Controllers\Backend\BasicinfoController;
use App\Http\Controllers\Backend\PolicymenuController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubcategoryController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\AddbannerController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\AttrvalueController;
use App\Http\Controllers\Backend\ServicepackageController;
use App\Http\Controllers\Backend\PaymenticonController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\InformationController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\ComplanenoteController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymenttypeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AdminInformation;
use App\Http\Controllers\FindorderController;
use App\Http\Controllers\ExpensetypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PathaoController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierpaymentController;
use App\Http\Controllers\WcustomerController;
use App\Http\Controllers\WpaymentController;
use App\Http\Controllers\WsaleController;
use App\Http\Controllers\WsalestockController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MainproductController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'admin',], function () {
    // login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.loginview');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');

    // logout
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');
    // reset password

});

Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin:admin']], function () {
    Route::get('fraud-check-data', [OrderController::class, 'fraudcheck']);
    Route::get('sendsms', [OrderController::class, 'sendsms']);
    Route::get('/my/profile', [AdminInformation::class, 'myprofile']);
    Route::get('get/information', [OrderController::class, 'getinfo']);
    Route::get('get/order/information', [OrderController::class, 'getorderinfo']);
    Route::get('/account/settings', [AdminInformation::class, 'settings']);
    Route::post('/update/profile/', [AdminInformation::class, 'profileupdate']);
    Route::get('/dashboard', [AuthenticatedSessionController::class, 'dashboard']);
    Route::get('stock/{slug}', [ProductController::class, 'stockoverview']);
    Route::get('stock/get/{stage}', [ProductController::class, 'stockdata']);
    Route::get('stock-by/product', [ProductController::class, 'stockby']);
    Route::get('stock-by/product/data', [ProductController::class, 'stockbydata']);

    // block user
    Route::get('unblock/{id}', [UserController::class, 'unblock']);
    Route::get('block-user', [UserController::class, 'blockuser']);
    Route::post('block-now', [UserController::class, 'blocknow']);

    // role & permission
    Route::resource('roles', RolesController::class, ['names' => 'admin.roles']);
    Route::resource('userroles', UserRolesController::class, ['names' => 'admin.userroles']);
    Route::resource('admins', AdminController::class, ['names' => 'admin.admins']);
    Route::resource('users', UserController::class, ['names' => 'admin.users']);
    Route::get('print/{id}', [AdminController::class, 'print'])->name('admin.print');
    // basic info
    Route::resource('basicinfos', BasicinfoController::class, ['names' => 'admin.basicinfos']);
    Route::post('/pixel/analytics/{id}', [BasicinfoController::class, 'pixelanalytics']);
    Route::post('/basicinfo/update/{id}', [BasicinfoController::class, 'sociallink']);
    Route::put('/shippinginfo/update/{id}', [BasicinfoController::class, 'shippinginfo'])->name('admin.shipping.update');
    //payment icon
    Route::resource('paymenticons', PaymenticonController::class, ['names' => 'admin.paymenticons']);
    Route::get('paymenticon/get/data', [PaymenticonController::class, 'paymenticondata'])->name('admin.paymenticon.data');
    Route::post('paymenticon/{id}', [PaymenticonController::class, 'update']);
    Route::put('paymenticon/status', [PaymenticonController::class, 'statusupdate']);

    //policy menu
    Route::resource('policymenus', PolicymenuController::class, ['names' => 'admin.policymenus']);
    Route::get('policymenu/get/data', [PolicymenuController::class, 'policymenudata'])->name('admin.policymenu.data');
    Route::post('policymenu/{id}', [PolicymenuController::class, 'update']);
    Route::put('policymenu/status', [PolicymenuController::class, 'statusupdate']);

    //blogs
    Route::resource('blogs', BlogController::class, ['names' => 'admin.blogs']);
    Route::get('blog/get/data', [BlogController::class, 'blogdata'])->name('admin.blog.data');
    Route::post('blog/{id}', [BlogController::class, 'update']);
    Route::put('blog/status', [BlogController::class, 'statusupdate']);

    //header menu
    Route::resource('menus', MenuController::class, ['names' => 'admin.menus']);
    Route::get('menu/get/data', [MenuController::class, 'menudata'])->name('admin.menu.data');
    Route::post('menu/{id}', [MenuController::class, 'update']);
    Route::put('menu/status', [MenuController::class, 'statusupdate']);

    //task
    Route::resource('tasks', TaskController::class);
    Route::post('task/{id}', [TaskController::class, 'update']);
    Route::put('task/status', [TaskController::class, 'updatestatus']);
    Route::get('admin/task/data', [TaskController::class, 'taskdata'])->name('task.data');
    Route::get('admin/task/datacourier', [TaskController::class, 'datacourier'])->name('task.datacourier');
    Route::get('admin/task/report', [ReportController::class, 'taskreport'])->name('taskreport');
    Route::get('admin/task/report/data', [ReportController::class, 'taskreportdata']);

    //header menu
    Route::resource('coupons', CouponController::class, ['names' => 'admin.coupons']);
    Route::get('coupon/get/data', [CouponController::class, 'coupondata'])->name('admin.coupon.data');
    Route::post('coupon/{id}', [CouponController::class, 'update']);
    Route::put('coupon/status', [CouponController::class, 'updatestatus']);

    Route::resource('reviews', ReviewController::class, ['names' => 'admin.reviews']);
    Route::get('review/get/data', [ReviewController::class, 'reviewdata'])->name('admin.review.data');
    Route::put('review/status', [ReviewController::class, 'updatestatus']);

    //Sliders
    Route::resource('sliders', SliderController::class, ['names' => 'admin.sliders']);
    Route::get('slider/get/data', [SliderController::class, 'sliderdata'])->name('admin.slider.data');
    Route::post('slider/{id}', [SliderController::class, 'update']);
    Route::put('slider/status', [SliderController::class, 'statusupdate']);

    //add banners
    Route::resource('addbanners', AddbannerController::class, ['names' => 'admin.addbanners']);
    Route::post('addbanner/{id}', [AddbannerController::class, 'update']);
    Route::put('addbanner/status/{id}', [AddbannerController::class, 'statusupdate']);

    //Brands
    Route::resource('brands', BrandController::class, ['names' => 'admin.brands']);
    Route::get('brand/get/data', [BrandController::class, 'branddata'])->name('admin.brand.data');
    Route::post('brand/{id}', [BrandController::class, 'update']);
    Route::put('brand/status', [BrandController::class, 'statusupdate']);

    //Service Package
    Route::resource('servicepackages', ServicepackageController::class, ['names' => 'admin.servicepackages']);
    Route::get('servicepackage/get/data', [ServicepackageController::class, 'servicepackagedata'])->name('admin.servicepackage.data');
    Route::post('servicepackage/{id}', [ServicepackageController::class, 'update']);
    Route::put('servicepackage/status', [ServicepackageController::class, 'statusupdate']);

    //category
    Route::resource('categorys', CategoryController::class, ['names' => 'admin.categorys']);
    Route::get('category/get/data', [CategoryController::class, 'categorydata'])->name('admin.category.data');
    Route::post('category/{id}', [CategoryController::class, 'update']);
    Route::put('category/status', [CategoryController::class, 'statusupdate']);
    Route::get('/get/subcategory/{id}', [CategoryController::class, 'getsubcategory']);

    //sub-category
    Route::resource('subcategorys', SubcategoryController::class, ['names' => 'admin.subcategorys']);
    Route::get('subcategory/get/data', [SubcategoryController::class, 'subcategorydata'])->name('admin.subcategory.data');
    Route::post('subcategory/{id}', [SubcategoryController::class, 'update']);
    Route::put('subcategory/status', [SubcategoryController::class, 'statusupdate']);

    //Attributes
    Route::resource('attributes', AttributeController::class, ['names' => 'admin.attributes']);
    Route::get('attribute/get/data', [AttributeController::class, 'attributedata'])->name('admin.attribute.data');
    Route::post('attribute/{id}', [AttributeController::class, 'update']);
    Route::put('attribute/status', [AttributeController::class, 'statusupdate']);

    //Attributes Values
    Route::resource('attrvalues', AttrvalueController::class, ['names' => 'admin.attrvalues']);
    Route::get('attrvalue/get/data', [AttrvalueController::class, 'attrvaluedata'])->name('admin.attrvalue.data');
    Route::post('attrvalue/{id}', [AttrvalueController::class, 'update']);
    Route::put('attrvalue/status', [AttrvalueController::class, 'statusupdate']);

    //products
    Route::resource('products', ProductController::class, ['names' => 'admin.products']);
    Route::get('product/get/data', [ProductController::class, 'productdata'])->name('admin.product.data');
    Route::get('remove-varient/{id}', [ProductController::class, 'removevarient']);
    Route::get('remove-size/{id}', [ProductController::class, 'removesize']);
    Route::get('remove-weight/{id}', [ProductController::class, 'removeweight']);
    Route::post('product/{id}', [ProductController::class, 'update']);
    Route::put('product/status', [ProductController::class, 'statusupdate']);
    Route::put('product/rated', [ProductController::class, 'ratedstatusupdate']);
    Route::put('product/featur', [ProductController::class, 'featurestatusupdate']);
    Route::put('product/best-selling', [ProductController::class, 'bestsellstatusupdate']);

    Route::get('information/{slug}', [InformationController::class, 'index']);
    Route::post('information/update/{slug}', [InformationController::class, 'update']);
    Route::get('menu/page/{slug}', [InformationController::class, 'create']);
    Route::post('menu/page/create/{slug}', [InformationController::class, 'createpage']);

    // GET CITY
    Route::get('get/city', [PathaoController::class, 'getCities']);
    //Expense method
    Route::resource('expensetypes', ExpensetypeController::class);
    Route::post('expensetype/{id}', [ExpensetypeController::class, 'update']);
    Route::put('expensetype/status', [ExpensetypeController::class, 'updatestatus']);
    Route::get('admin/expensetype', [ExpensetypeController::class, 'expensetypedata'])->name('expensetype.info');

    //account
    Route::resource('accounts', AccountController::class);
    Route::post('account/{id}', [AccountController::class, 'update']);
    Route::get('account/data/{slug}', [AccountController::class, 'accountdata'])->name('account.data');
    Route::get('account-deposit/{slug}', [AccountController::class, 'accountslug'])->name('accountslug');
    Route::get('account/report', [ReportController::class, 'accountreport'])->name('accountreport');
    Route::get('account/report/data', [ReportController::class, 'accountreportdata']);

    //Expense
    Route::resource('expenses', ExpenseController::class);
    Route::post('expense/{id}', [ExpenseController::class, 'update']);
    Route::put('expense/status', [ExpenseController::class, 'updatestatus']);
    Route::get('expense/data/{slug}', [ExpenseController::class, 'expensedata'])->name('expense.data');
    Route::get('expense-cost/{slug}', [ExpenseController::class, 'expenseslug'])->name('expenseslug');
});

Route::group(['middleware' => ['auth.admin:admin']], function () {
    Route::get('admin/maps', [OrderController::class, 'maps']);
    Route::get('admin/citydata/{id}', [OrderController::class, 'citydata']);

    Route::get('admin/product/color', [ProductController::class, 'variant']);
    Route::get('admin/product/size', [ProductController::class, 'size']);
    Route::get('admin/product/weight', [ProductController::class, 'weight']);
    Route::post('admin/products/store', [ProductController::class, 'store']);

    //main products
    Route::resource('mainproducts', MainproductController::class);
    Route::post('mainproduct/{id}', [MainproductController::class, 'update']);
    Route::put('mainproduct/status', [MainproductController::class, 'updatestatus']);
    Route::put('mainproduct/position-update', [MainproductController::class, 'positionupdate']);

    Route::get('admin/create/order', [OrderController::class, 'createorder']);
    Route::post('admin/order/store', [OrderController::class, 'storeorder']);
    // complain
    Route::resource('complain/complains', ComplainController::class);
    Route::post('complain/data/{id}', [ComplainController::class, 'update']);
    Route::get('complain/{status}', [ComplainController::class, 'subindex']);
    Route::get('complain/data/{status}', [ComplainController::class, 'complaindata'])->name('complain.info');
    Route::get('complain/complain/Sync', [ComplainController::class, 'complainSync']);
    Route::post('complain/complainstatus', [ComplainController::class, 'updatestatus']);
    Route::get('complain/comment/update', [ComplanenoteController::class, 'store']);
    Route::get('complain/comment/get', [ComplanenoteController::class, 'getComplainNote']);
    Route::get('complain/create/complain', [ComplanenoteController::class, 'createcomplain']);
    Route::get('admin/admin_order/complaneinfo', [OrderController::class, 'complane'])->name('admin_order.complane');
    //order complain
    Route::get('/order/complain', [OrderController::class, 'complain']);
    Route::get('/user/order', [OrderController::class, 'userorder']);
    Route::get('admin/admin_order/user{status}', [OrderController::class, 'orderdata'])->name('admin_order.infos');

    Route::get('assign_user_complain', [ComplanenoteController::class, 'assignusertocomplain']);
    Route::get('admin/scanbarcode', [FindorderController::class, 'scanbarcode'])->name('orderchange.bybarcode');
    Route::get('admin/manualbarcode', [FindorderController::class, 'manualbarcode'])->name('orderchange.manualbarcode');
    Route::get('admin/refresh-barcode', [FindorderController::class, 'refreshbarcode']);
    Route::post('admin/change-status', [FindorderController::class, 'chnagestatus']);

    Route::get('admin/auto-return', [FindorderController::class, 'autoreturn'])->name('orderchange.autoreturn');
    Route::get('admin/manual-return', [FindorderController::class, 'manualreturn'])->name('orderchange.manualreturn');
    Route::post('admin/return-status', [FindorderController::class, 'return']);

    Route::get('getorder/bybarcode', [FindorderController::class, 'orderdetails']);
    Route::get('admin/getscan/order', [FindorderController::class, 'orderdetabybar']);
    Route::get('admin/orderbysearch', [FindorderController::class, 'searchdata']);
    Route::get('admin/courierUpdateByCheckbox', [FindorderController::class, 'courierUpdateByCheckbox']);
    //order by product
    Route::get('/orderby-product', [OrderController::class, 'orderByproductindex']);
    Route::get('/findorder-byproduct', [OrderController::class, 'findByproduct']);

    //invoiced
    Route::get('admin_order/store/Invoice', [OrderController::class, 'storeInvoice']);
    Route::get('admin_order/invoice/{id}', [OrderController::class, 'viewInvoice']);

    //download excel
    Route::get('admin/download/orderinfo', [InvoiceController::class, 'getdata']);
    Route::post('admin_order/download/excel', [InvoiceController::class, 'fileExport'])->name('file-export');
    Route::post('/download-excle', [InvoiceController::class, 'downloadexcle'])->name('orderdata-export');

    Route::resource('admin_orders', OrderController::class);
    Route::post('admin_order/{id}', [OrderController::class, 'update']);

    Route::get('admin_order/getComment', [OrderController::class, 'getComments']);
    Route::get('admin_order/updateComment', [OrderController::class, 'updateComments']);

    Route::get('admin_order/paymenttype', [OrderController::class, 'paymenttype']);
    Route::get('admin_order/paymentnumber', [OrderController::class, 'paymentnumber']);

    Route::get('admin_wholesale/products', [OrderController::class, 'wholeproduct']);
    Route::get('admin_order/products', [OrderController::class, 'admproduct']);
    Route::get('admin_order/previous_orders', [OrderController::class, 'previous_orders']);
    Route::get('admin_order/mini-products', [OrderController::class, 'admminiproduct']);
    Route::post('admin_order/main-product/store', [MainproductController::class, 'storeproduct']);
    Route::post('admin_order/main-product/update', [MainproductController::class, 'update']);
    Route::get('mainproduct/get/data', [MainproductController::class, 'productdata'])->name('admin.mainproduct.data');
    Route::put('mainproduct/status', [MainproductController::class, 'statusupdate']);
    Route::put('mainproduct/rated', [MainproductController::class, 'ratedstatusupdate']);
    Route::post('mainproduct/{id}', [MainproductController::class, 'update']);
    Route::get('mainproduct-edit/{id}', [MainproductController::class, 'edit']);

    Route::get('order/admin_order/status', [OrderController::class, 'updateorderstatus']);
    Route::get('admin_order/product/topsell/{id}', [OrderController::class, 'topsellpeoduct']);
    Route::get('admin_order/product/recentsell/{id}', [OrderController::class, 'recentsellpeoduct']);

    Route::get('admin_order/count', [OrderController::class, 'countorder']);
    Route::get('admin_order/count/{id}', [OrderController::class, 'countorderbyid']);
    Route::get('admin_order/couriers', [OrderController::class, 'couriers']);
    Route::get('admin_order/cities', [OrderController::class, 'city']);
    Route::get('admin_order/zones', [OrderController::class, 'zone']);
    Route::get('admin_order/areas', [OrderController::class, 'area']);
    Route::get('admin_order/courier', [OrderController::class, 'courier']);
    Route::get('admin_order/users', [OrderController::class, 'users']);
    Route::get('admin_order/statusUpdateByCheckbox', [OrderController::class, 'statusUpdateByCheckbox']);
    Route::get('admin_order/delete_selected_order', [OrderController::class, 'delete_selected_order']);
    Route::get('admin_order/assign_user', [OrderController::class, 'assignuser']);
    Route::get('admin_order/assign_courier', [OrderController::class, 'assigncourier']);
    Route::get('admin/admin_order/{status}', [OrderController::class, 'orderdata'])->name('admin_order.info');
    //order by status
    Route::get('admin_order/{status}', [OrderController::class, 'ordersByStatus']);
    Route::delete('incomplete_order/delete/{id}', [OrderController::class, 'deleteinc']);
    Route::get('incomplete_order/{status}', [OrderController::class, 'incompleteorder']);
    Route::get('admin/incomplete_order/{status}', [OrderController::class, 'incompletedata']);
    Route::get('order/incomplete_order/status', [OrderController::class, 'incups']);

    Route::get('order/manager/dashboard', [AuthenticatedSessionController::class, 'managerdashboard']);
    Route::get('order/dashboard', [AuthenticatedSessionController::class, 'userdashboard']);

    //courier
    Route::resource('couriers', CourierController::class);
    Route::post('courier/{id}', [CourierController::class, 'update']);
    Route::put('courier/status', [CourierController::class, 'updatestatus']);
    Route::get('admin/couriers', [CourierController::class, 'courierdata'])->name('courier.info');
    //city
    Route::resource('cities', CityController::class);
    Route::post('city/{id}', [CityController::class, 'update']);
    Route::put('city/status', [CityController::class, 'updatestatus']);
    Route::get('admin/cities', [CityController::class, 'citydata'])->name('city.info');
    //zone
    Route::resource('zones', ZoneController::class);
    Route::post('zone/{id}', [ZoneController::class, 'update']);
    Route::put('zone/status', [ZoneController::class, 'updatestatus']);
    Route::get('admin/zones', [ZoneController::class, 'zonedata'])->name('zone.info');
    //areas
    Route::resource('areas', AreaController::class);
    Route::post('area/{id}', [AreaController::class, 'update']);
    Route::put('area/status', [AreaController::class, 'updatestatus']);
    Route::get('admin/areas', [AreaController::class, 'areadata'])->name('area.info');

    //purchess
    Route::resource('purchases', PurchaseController::class);
    Route::post('admin_purchase/store', [PurchaseController::class, 'store']);
    Route::post('purchase/{id}', [PurchaseController::class, 'update']);
    Route::get('admin/purchase', [PurchaseController::class, 'purchasedata'])->name('purchese.info');
    Route::get('admin/purchase-create', [PurchaseController::class, 'create']);
    Route::get('admin_get/suppliers', [PurchaseController::class, 'suppliers']);
    //purchess

    Route::resource('returns', ReturnController::class);
    Route::post('admin_return/store', [ReturnController::class, 'store']);
    Route::post('return/{id}', [ReturnController::class, 'update']);
    Route::get('admin/return', [ReturnController::class, 'returndata'])->name('return.info');
    Route::get('admin/return-create', [ReturnController::class, 'create']);

    //stocks
    Route::resource('stocks', StockController::class);
    Route::get('admin/stock', [StockController::class, 'stockdata'])->name('stock.info');
    //supplier
    Route::resource('suppliers', SupplierController::class);
    Route::post('supplier/{id}', [SupplierController::class, 'update']);
    Route::put('supplier/status', [SupplierController::class, 'updatestatus']);
    Route::get('admin/supplier', [SupplierController::class, 'supplierdata'])->name('supplier.info');
    Route::get('supplier/ledger/{id}', [SupplierController::class, 'supplierLedger'])->name('purchase.ledger');

    Route::post('supplier-payment', [SupplierpaymentController::class, 'store'])->name('supplierpayment.store');

    //payment method
    Route::resource('paymenttypes', PaymenttypeController::class);
    Route::post('paymenttype/{id}', [PaymenttypeController::class, 'update']);
    Route::put('paymenttype/status', [PaymenttypeController::class, 'updatestatus']);
    Route::get('admin/paymenttype', [PaymenttypeController::class, 'paymenttypedata'])->name('paymenttype.info');
    //payment method
    Route::resource('payments', PaymentController::class);
    Route::post('payment/{id}', [PaymentController::class, 'update']);
    Route::put('payment/status', [PaymentController::class, 'updatestatus']);
    Route::get('admin/payment', [PaymentController::class, 'paymentdata'])->name('payment.info');

    //wcustomer
    Route::resource('wcustomers', WcustomerController::class);
    Route::post('wcustomer/{id}', [WcustomerController::class, 'update']);
    Route::put('wcustomer/status', [WcustomerController::class, 'updatestatus']);
    Route::get('admin/wcustomer', [WcustomerController::class, 'wcustomerdata'])->name('wcustomer.info');
    Route::get('wcustomer/ledger/{id}', [WcustomerController::class, 'wcustomerLedger'])->name('wsale.ledger');

    Route::post('wcustomer-payment', [WpaymentController::class, 'store'])->name('wcustomerpayment.store');
    //wsale
    Route::resource('wsales', WsaleController::class);
    Route::post('admin_wsale/store', [WsaleController::class, 'store']);
    Route::post('wsale/{id}', [WsaleController::class, 'update']);
    Route::get('admin/wsale', [WsaleController::class, 'wsaledata'])->name('wsale.info');
    Route::get('admin/wsale-create', [WsaleController::class, 'create']);
    Route::get('admin_get/wcustomers', [WsaleController::class, 'wcustomers']);
    //wsalestock
    Route::resource('wsalestocks', WsalestockController::class);
    Route::get('admin/wsalestock', [WsalestockController::class, 'wsalestockdata'])->name('wsalestock.info');


    //report section
    Route::get('admin/courier/user/report', [ReportController::class, 'courieruserreport'])->name('courieruserreport');
    Route::get('admin/courier/report', [ReportController::class, 'courierreport'])->name('courierreport');
    Route::get('admin/user/report', [ReportController::class, 'userreport'])->name('userreport');
    Route::get('admin/payment/report', [ReportController::class, 'paymentreport'])->name('paymentreport');
    Route::get('admin/product/report', [ReportController::class, 'productreport'])->name('productreport');
    Route::get('admin/expense/report', [ReportController::class, 'expensereport'])->name('expensereport');

    // report data
    Route::get('admin/expense/report/data', [ReportController::class, 'expensereportdata']);
    Route::get('admin/courier/user/report/data', [ReportController::class, 'courieruserreportdata']);
    Route::get('admin/courier/report/data', [ReportController::class, 'courierreportdata']);
    Route::get('admin/user/report/data', [ReportController::class, 'userreportdata']);
    Route::get('admin/payment/report/data', [ReportController::class, 'paymentreportdata']);
    Route::get('admin/product/report/data', [ReportController::class, 'productreportdata']);
});
