<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
Route::get('admin/login', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'getLogin'])->name('adminLogin');
Route::post('admin/login', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'postLogin'])->name('adminLoginPost');
Route::get('admin/logout', [App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'logout'])->name('adminLogout');
Route::get('admin/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showForgot'])->name('password.request');
Route::post('admin/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'forgotlink'])->name('password.email');
Route::get('admin/reset-password/{token}', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('admin/reset-password', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('password.update');

Route::group(['middleware' => 'adminauth'], function () {
    //Route::get('/admin/add_permissions', [App\Http\Controllers\Admin\PermissionController::class, 'Permission']);
    // Route::get('/admin/roleTest', function () {
    //     $user = auth()->guard('webadmin')->user();
    //     dd($user->hasPermission('view'));
    // });
    //Admin Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardContoller::class, 'index'])->name('admin.dashboard.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/dashboard', [App\Http\Controllers\DashboardContoller::class, 'index'])->name('admin.filter.dashboard.index');
    Route::post('/admin/liked-data', [App\Http\Controllers\DashboardContoller::class, 'likedDetails']);
    Route::post('/admin/visitor-data', [App\Http\Controllers\DashboardContoller::class, 'visitorsDetails']);
    Route::get('/admin/edit-admin-detail', [App\Http\Controllers\DashboardContoller::class, 'editAdminDetail'])->name('admin.editdetails');
    Route::post('/admin/admin-edit/{id}', [App\Http\Controllers\DashboardContoller::class, 'update'])->name('admin.update');

    //Claim Section
    Route::get('/admin/claim', [App\Http\Controllers\Admin\Claim\ClaimController::class, 'index'])->name('admin.claim.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/claim', [App\Http\Controllers\Admin\Claim\ClaimController::class, 'index'])->name('admin.claim.indexsearch');
    Route::get('/admin/claim/view/{id}', [App\Http\Controllers\Admin\Claim\ClaimController::class, 'view'])->name('admin.claim.view');

    Route::get('/admin/affiliates', [App\Http\Controllers\Admin\Affiliate\AdminAffiliateController::class, 'index'])->name('admin.affiliate.index');
    Route::post('/admin/affiliates/{affiliate}/approve',[App\Http\Controllers\Admin\Affiliate\AdminAffiliateController::class, 'approve'])->name('admin.affiliates.approve');
    Route::post('/admin/affiliates/{affiliate}/reject',[App\Http\Controllers\Admin\Affiliate\AdminAffiliateController::class, 'reject'])->name('admin.affiliates.reject');

    //Reports
    Route::get('/admin/reports', [App\Http\Controllers\DashboardContoller::class, 'reportsview'])->name('admin.reports.view');
    //offline Reports
    Route::get('/admin/offline-reports', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'manualreportsview'])->name('admin.offline.reports');
    Route::post('/admin/offline-reports', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'create'])->name('admin.offline.submit.reports');
    Route::get('/admin/allvisitors', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allvisitors'])->name('admin.all.visitors');
    Route::post('/admin/allvisitors', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allvisitors'])->name('admin.all.visitorsfilter');
    Route::get('/admin/allreceiptclosing', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allreceiptclosing'])->name('admin.all.receiptclosing');
    Route::post('/admin/allreceiptclosing', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allreceiptclosing'])->name('admin.all.receiptclosingfilter');
    Route::get('/admin/allextra/reports', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allextrareports'])->name('admin.allextra.reports');
    Route::post('/admin/allextra/reports', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'allextrareports'])->name('admin.allextra.reportsfilter');

    Route::get('/admin/offline-reports-edit/{id}', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'edit'])->name('admin.offline.reports.edit');
    Route::post('/admin/offline-reports-edit/{id}', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'update'])->name('admin.offline.reports.update');
    Route::get('/admin/offline-reports-delete/{id}', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'destroy'])->name('admin.offline.reports.destroy');
    Route::get('/admin/offline-reports/changeStatus', [App\Http\Controllers\Admin\AdminManualReportsContoller::class, 'changeStatus']);

    Route::get('/admin/reports/dashboard', [App\Http\Controllers\DashboardContoller::class, 'reportsdashboard'])->name('admin.reports.dashboard')->middleware('ModuleAdminAuth');
    Route::post('/admin/reports/dashboard', [App\Http\Controllers\DashboardContoller::class, 'reportsdashboard'])->name('admin.filter.reports.dashboard');
    Route::get('/admin/all-customer', [App\Http\Controllers\Admin\UserController::class, 'regionCustomer'])->name('admin.user.customerfilter');
    Route::get('/admin/all-companies', [App\Http\Controllers\Admin\UserController::class, 'regionCompanies'])->name('admin.user.companiesfilter');

    Route::get('/admin/add-mail-template', [App\Http\Controllers\Admin\Mailtemplate\MailtemplateController::class, 'create'])->name('admin.mailtemplate')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-mail-template', [App\Http\Controllers\Admin\Mailtemplate\MailtemplateController::class, 'submit'])->name('admin.mailtemplate.submit');
    Route::post('/admin/get-maildata-by-templateId', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'getMailByTemplateId']);

    Route::get('/admin/mail', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'create'])->name('admin.mail.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/send-mail-using-mailchimp', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'sendMail'])->name('send.mail.using.mailchimp.sendMail');
    Route::get('/admin/mail-listing', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'listing'])->name('admin.mail.listing')->middleware('ModuleAdminAuth');
    Route::post('/admin/mail-listing', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'listing'])->name('admin.mail.indexsearch');
    Route::get('/admin/mail-view/{id}', [App\Http\Controllers\Admin\Mail\MailControllers::class, 'view'])->name('admin.mail.view');

    /* User creation */
    Route::get('/admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user.index')->middleware('ModuleAdminAuth');
    Route::post('/sendBulkMessage', [App\Http\Controllers\Admin\UserController::class, 'sendBulkMessage']);
    Route::get('/admin/vendor/staff/{id}', [App\Http\Controllers\Admin\UserController::class, 'vendorstaff'])->name('admin.vendor.staff');
    Route::post('/admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user.indexsearch');
    Route::get('/admin/user-registration', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.user.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/user-registration', [App\Http\Controllers\Admin\UserController::class, 'submitUser'])->name('admin.user.submitUser');
    Route::get('/admin/user-edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'editUser'])->name('admin.user.editUser');
    Route::post('/admin/user-edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'updateUser'])->name('admin.user.updateUser');
    Route::get('/admin/user-delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.user.destroy');
    Route::get('/showbulkimportfile', [App\Http\Controllers\Admin\UserController::class, 'showBulkImportFile']);

    Route::get('/admin/account/add-form-address/{id}', [App\Http\Controllers\Admin\UserController::class, 'addAddress'])->name('admin.address.add');
    Route::post('/admin/account/add-address', [App\Http\Controllers\Admin\UserController::class, 'storeAddress'])->name('admin.address.storeAddress');
    Route::get('/admin/account/edit-address/{id}', [App\Http\Controllers\Admin\UserController::class, 'editAddress'])->name('admin.address.edit');
    Route::post('/admin/account/edit-address', [App\Http\Controllers\Admin\UserController::class, 'updateAddress'])->name('admin.address.updateAddress');
    Route::get('/admin/account/delete-address/{id}', [App\Http\Controllers\Admin\UserController::class, 'deleteAddress'])->name('admin.address.deleteAddress');

    /* Default Image insertion */
    Route::get('/admin/default-images', [App\Http\Controllers\Admin\DefaultImages::class, 'index'])->name('admin.defaultimages.index')->middleware('ModuleAdminAuth');
    Route::get('/admin/default-image-edit/{id}', [App\Http\Controllers\Admin\DefaultImages::class, 'edit'])->name('admin.defaultimages.edit');
    Route::post('/admin/default-image-edit/{id}', [App\Http\Controllers\Admin\DefaultImages::class, 'update'])->name('admin.defaultimages.update');
    Route::get('/admin/default-image-delete/{id}', [App\Http\Controllers\Admin\DefaultImages::class, 'destroy'])->name('admin.defaultimages.destroy');

    /* Role managment */
    Route::get('/admin/role', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.role.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/role', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.role.indexsearch');
    Route::get('/admin/role-create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('admin.role.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/role-submit', [App\Http\Controllers\Admin\RoleController::class, 'submit'])->name('admin.role.submit');
    Route::get('/admin/role-edit/{id}', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('admin.role.edit');
    Route::post('/admin/role-edit/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('admin.role.update');
    Route::get('/admin/role-delete/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('admin.role.destroy');
    Route::get('/admin/changeRoleStatus', [App\Http\Controllers\Admin\RoleController::class, 'changeStatus']);

    /* Permission managment */
    Route::get('/admin/permission', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.permission.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/permission', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.permission.indexsearch');
    Route::get('/admin/permission-create', [App\Http\Controllers\Admin\AdminController::class, 'create'])->name('admin.permission.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/permission-submit', [App\Http\Controllers\Admin\AdminController::class, 'submit'])->name('admin.permission.submit');
    Route::get('/admin/permission-edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admin.permission.edit');
    Route::post('/admin/permission-edit/{id}', [App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admin.permission.update');
    Route::get('/admin/permission-delete/{id}', [App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admin.permission.destroy');

    /* User Status */
    Route::get('/admin/changeStatus', [App\Http\Controllers\Admin\UserController::class, 'changeStatus']);

    /* Admin Status */
    Route::get('/admin/changeAdminStatus', [App\Http\Controllers\Admin\UserController::class, 'changeAdminStatus']);

    /* Category creation */
    Route::get('/admin/categories', [App\Http\Controllers\Admin\Category\CategoryController::class, 'index'])->name('admin.categories.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/categories', [App\Http\Controllers\Admin\Category\CategoryController::class, 'index'])->name('admin.categories.indexsearch');
    Route::get('/admin/add-category', [App\Http\Controllers\Admin\Category\CategoryController::class, 'create'])->name('admin.category.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-category', [App\Http\Controllers\Admin\Category\CategoryController::class, 'submitCategory'])->name('admin.category.submitCategory');
    Route::get('/admin/category-edit/{id}', [App\Http\Controllers\Admin\Category\CategoryController::class, 'editCategory'])->name('admin.category.editCategory');
    Route::post('/admin/category-edit/{id}', [App\Http\Controllers\Admin\Category\CategoryController::class, 'updateCategory'])->name('admin.category.updateCategory');
    Route::get('/admin/category-delete/{id}', [App\Http\Controllers\Admin\Category\CategoryController::class, 'destroy'])->name('admin.category.destroy');
    Route::get('/admin/tree-category', [App\Http\Controllers\Admin\Category\CategoryController::class, 'tree'])->name('admin.category.tree');
    Route::get('/admin/changeDirectoryCatStatus', [App\Http\Controllers\Admin\Category\CategoryController::class, 'changeDirectoryCatStatus']);


    /* Parent category according to the category Type */
    Route::post('/admin/parent-category', [App\Http\Controllers\Admin\Category\CategoryController::class, 'parentcat_categoryType']);
    Route::post('/admin/categoryseo', [App\Http\Controllers\Admin\Category\CategoryController::class, 'checkCategorySeo']);
    Route::post('/admin/directoryseo', [App\Http\Controllers\Admin\Category\CategoryController::class, 'checkDirectorySeo']);

    /* Amenity creation */
    Route::get('/admin/amenities', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'index'])->name('admin.amenity.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/amenities', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'index'])->name('admin.amenity.indexsearch');
    Route::get('/admin/add-amenity', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'create'])->name('admin.amenity.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-amenity', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'submitAmenity'])->name('admin.amenity.submitAmenity');
    Route::get('/admin/amenity-edit/{id}', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'editAmenity'])->name('admin.amenity.editAmenity');
    Route::post('/admin/amenity-edit/{id}', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'updateAmenity'])->name('admin.amenity.updateAmenity');
    Route::get('/admin/amenity-delete/{id}', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'destroy'])->name('admin.amenity.destroy');
    Route::get('/admin/import-amenities', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'import'])->name('admin.amenity.import');
    Route::get('/admin/changeAmenityStatus', [App\Http\Controllers\Admin\Amenity\AmenityController::class, 'changeAmenityStatus']);

    /* Sipping managment */
    Route::get('/admin/shipping', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'index'])->name('admin.shipping.index');
    Route::post('/admin/shipping', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'index'])->name('admin.shipping.indexsearch');
    Route::get('/admin/shipping-edit/{id}', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'edit'])->name('admin.shipping.edit');
    Route::post('/admin/shipping-update', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'update'])->name('admin.shipping.update');
    Route::get('/admin/shipping-delete/{id}', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'destroy'])->name('admin.shipping.destroy');
    Route::get('/admin/changeShippingStatus', [App\Http\Controllers\Admin\Shipping\ShippingController::class, 'changeStatus']);

    /* Product Attributes */
    Route::get('/admin/attributes', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'index'])->name('admin.attributes.index');
    Route::get('/admin/add-attribute', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'create'])->name('admin.attribute.create');
    Route::post('/admin/add-attribute', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'submit'])->name('admin.attribute.submit');
    Route::get('/admin/attribute-edit/{id}', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'edit'])->name('admin.attribute.edit');
    Route::post('/admin/attribute-edit/{id}', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'update'])->name('admin.attribute.update');
    Route::get('/admin/attribute-delete/{id}', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'destroy'])->name('admin.attribute.destroy');
    Route::get('/admin/changeAttributeStatus', [App\Http\Controllers\Admin\Attributes\AttributesController::class, 'changeStatus']);

    /* Amenity Category creation */
    Route::get('/admin/amenity-category', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'index'])->name('admin.amenitycategory.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/amenity-category', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'index'])->name('admin.amenitycategory.indexsearch');
    Route::get('/admin/add-amenity-category', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'create'])->name('admin.amenitycategory.create');
    Route::post('/admin/add-amenity-category', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'submit'])->name('admin.amenitycategory.submit');
    Route::get('/admin/amenity-category-edit/{id}', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'edit'])->name('admin.amenitycategory.edit');
    Route::post('/admin/amenity-category-edit/{id}', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'update'])->name('admin.amenitycategory.update');
    Route::get('/admin/amenity-category-delete/{id}', [App\Http\Controllers\Admin\Amenity\AmenityCategoryController::class, 'destroy'])->name('admin.amenitycategory.destroy');

    /* Languages creation */
    Route::get('/admin/languages', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'index'])->name('admin.languages.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/languages', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'index'])->name('admin.languages.indexsearch');
    Route::get('/admin/add-languages', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'create'])->name('admin.languages.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-languages', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'submitLanguage'])->name('admin.languages.submitLanguage');
    Route::get('/admin/languages-edit/{id}', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'editLanguage'])->name('admin.languages.editLanguage');
    Route::post('/admin/languages-edit/{id}', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'updateLanguage'])->name('admin.languages.updateLanguage');
    Route::get('/admin/languages-delete/{id}', [App\Http\Controllers\Admin\Languages\LangaugeController::class, 'destroy'])->name('admin.languages.destroy');
    Route::get('/admin/get-search-data', [App\Helpers\Helper::class, 'getSearchData']);

    /* System setting creation */
    Route::get('/admin/systemsetting', [App\Http\Controllers\Admin\Systemsetting\SystemsettingController::class, 'index'])->name('admin.systemsetting.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-systemsetting', [App\Http\Controllers\Admin\Systemsetting\SystemsettingController::class, 'submitSystemsetting'])->name('admin.systemsetting.submitSystemsetting');
    Route::get('/admin/systemsetting-edit', [App\Http\Controllers\Admin\Systemsetting\SystemsettingController::class, 'editSystemsetting'])->name('admin.systemsetting.editSystemsetting');
    Route::post('/admin/systemsetting-edit', [App\Http\Controllers\Admin\Systemsetting\SystemsettingController::class, 'updateSystemsetting'])->name('admin.systemsetting.updateSystemsetting');
    /* Frontend setting creation */
    Route::get('/admin/frontendsetting', [App\Http\Controllers\Admin\Frontendsetting\FrontendsettingController::class, 'index'])->name('admin.frontendsetting.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-frontendsetting', [App\Http\Controllers\Admin\Frontendsetting\FrontendsettingController::class, 'submitFrontendsetting'])->name('admin.frontendsetting.submitFrontendsetting');
    Route::get('/admin/frontendsetting-edit', [App\Http\Controllers\Admin\Frontendsetting\FrontendsettingController::class, 'editFrontendsetting'])->name('admin.frontendsetting.editFrontendsetting');
    Route::post('/admin/frontendsetting-edit', [App\Http\Controllers\Admin\Frontendsetting\FrontendsettingController::class, 'updateFrontendsetting'])->name('admin.frontendsetting.updateFrontendsetting');
    /* Category Custom Fields creation */
    Route::get('/admin/category-cusfield', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'index'])->name('admin.customfields.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/category-cusfield', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'index'])->name('admin.customfields.indexsearch');
    Route::get('/admin/add-category-cusfield', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'create'])->name('admin.customfields.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-category-cusfield', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'submitcustfield'])->name('admin.customfields.submitcustfield');
    Route::get('/admin/category-cusfield-edit/{id}', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'editcustfield'])->name('admin.customfields.editcustfield');
    Route::post('/admin/category-cusfield-edit/{id}', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'updatecustfield'])->name('admin.customfields.updatecustfield');
    Route::get('/admin/category-cusfield-delete/{id}', [App\Http\Controllers\Admin\Category\CatCustomFieldController::class, 'destroy'])->name('admin.customfields.destroy');
    /* Map setting creation */
    Route::get('/admin/mapsetting', [App\Http\Controllers\Admin\Mapsetting\MapsettingController::class, 'index'])->name('admin.mapsetting.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-mapsetting', [App\Http\Controllers\Admin\Mapsetting\MapsettingController::class, 'submitMapsetting'])->name('admin.mapsetting.submitMapsetting');
    Route::get('/admin/mapsetting-edit', [App\Http\Controllers\Admin\Mapsetting\MapsettingController::class, 'editMapsetting'])->name('admin.mapsetting.editMapsetting');
    Route::post('/admin/mapsetting-edit', [App\Http\Controllers\Admin\Mapsetting\MapsettingController::class, 'updateMapsetting'])->name('admin.mapsetting.updateMapsetting');
    /* SMTP setting creation */
    Route::get('/admin/smtpsetting', [App\Http\Controllers\Admin\Smtpsetting\SmtpsettingController::class, 'index'])->name('admin.smtpsetting.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-smtpsetting', [App\Http\Controllers\Admin\Smtpsetting\SmtpsettingController::class, 'submitSmtpsetting'])->name('admin.smtpsetting.submitSmtpsetting');
    Route::get('/admin/smtpsetting-edit', [App\Http\Controllers\Admin\Smtpsetting\SmtpsettingController::class, 'editSmtpsetting'])->name('admin.smtpsetting.editSmtpsetting');
    Route::post('/admin/smtpsetting-edit', [App\Http\Controllers\Admin\Smtpsetting\SmtpsettingController::class, 'updateSmtpsetting'])->name('admin.smtpsetting.updateSmtpsetting');
    /* Blog creation */
    Route::get('/admin/blog', [App\Http\Controllers\Admin\Blog\BlogController::class, 'index'])->name('admin.blog.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/blog', [App\Http\Controllers\Admin\Blog\BlogController::class, 'index'])->name('admin.blog.indexsearch');
    Route::get('/admin/add-blog', [App\Http\Controllers\Admin\Blog\BlogController::class, 'create'])->name('admin.blog.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-blog', [App\Http\Controllers\Admin\Blog\BlogController::class, 'submitBlog'])->name('admin.blog.submitBlog');
    Route::get('/admin/blog-edit/{id}', [App\Http\Controllers\Admin\Blog\BlogController::class, 'editBlog'])->name('admin.blog.editBlog');
    Route::post('/admin/blog-edit/{id}', [App\Http\Controllers\Admin\Blog\BlogController::class, 'updateblog'])->name('admin.blog.updateblog');
    Route::get('/admin/blog-delete/{id}', [App\Http\Controllers\Admin\Blog\BlogController::class, 'destroy'])->name('admin.blog.destroy');
    Route::post('/admin/blogslug', [App\Http\Controllers\Admin\Blog\BlogController::class, 'checkBlogSlug']);
    Route::get('/admin/changeBlogStatus', [App\Http\Controllers\Admin\Blog\BlogController::class, 'changeBlogStatus']);


    /* Directory creation */
    Route::get('/admin/directory', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'index'])->name('admin.directory.index')->middleware('ModuleAdminAuth');
    Route::get('/admin/directory-orders/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'directoryOrders'])->name('admin.directory.orders');
    Route::get('/admin/ordersfilter', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'ordersfilter'])->name('admin.orders.ordersfilter');
    Route::post('/admin/directory', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'index'])->name('admin.directory.indexsearch');
    Route::get('/admin/add-directory', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'create'])->name('admin.directory.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-directory', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'submit'])->name('admin.directory.submit');
    Route::get('/admin/directory-edit/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'edit'])->name('admin.directory.edit');
    Route::post('/admin/directory-edit/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'update'])->name('admin.directory.update');
    Route::get('/admin/directory-delete/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'destroy'])->name('admin.directory.destroy');
    Route::get('/admin/directory-offer/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'directoryAllOffer'])->name('admin.directory.offer');
    Route::get('/admin/directory-product/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'directoryAllProduct'])->name('admin.directory.product');
    Route::get('/admin/directory-voucher/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'directoryAllVoucher'])->name('admin.directory.voucher');
    Route::get('/admin/directory-giftvoucher/{id}', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'directoryAllGiftVoucher'])->name('admin.directory.giftvoucher');
    Route::post('/admin/get-states-by-country', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getState']);
    Route::post('/admin/get-cities-by-state', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getCity']);
    Route::post('/admin/get-tagsandOwntags-by-categoriesid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTagsAndOwntags']);
    Route::post('/admin/get-amenity-by-amenitycategory', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getAmenity']);
    Route::post('/admin/get-trademarks-by-categoriesid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTrademarksByCatid']);
    Route::post('/admin/get-normaltemplate-by-id', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getNormalTemplateData']);
    Route::post('/admin/get-publictemplate-by-id', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getPublicTemplateData']);
    Route::post('/admin/get-exceptiontemplate-by-id', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getExceptionTemplateOption']);
    Route::post('/admin/get-gallery-img', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getGalleryImageDelete']);
    Route::post('/admin/get-cover-img', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getCoverImageDelete']);
    Route::post('/admin/get-thumb-img', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getThumbImageDelete']);
    Route::post('/admin/get-trademark-img', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTrademarkImageDelete']);
    Route::post('/admin/gallery-image-delete-all', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'galleryImageDeleteAll']);
    Route::post('/admin/get-type-block', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTypeBlock']);
    Route::post('/admin/get-schedual-block', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getSchedualBlock']);
    Route::post('/admin/delete-holiday-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteHolidayRecord']);
    Route::post('/admin/delete-execption_holiday-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteExecptionHolidayRecord']);
    Route::post('/admin/get-vendor-by-vendorid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getvendorbyid']);
    Route::get('/admin/changeDirectoryStatus', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'changeDirectoryStatus']);

    /* Tags creation */
    Route::get('/admin/tags', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'index'])->name('admin.tags.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/tags', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'index'])->name('admin.tags.indexsearch');
    Route::get('/admin/add-tags', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'create'])->name('admin.tags.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-tags', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'submit'])->name('admin.tags.submit');
    Route::get('/admin/tags-edit/{id}', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'edit'])->name('admin.tags.edit');
    Route::post('/admin/tags-edit/{id}', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'update'])->name('admin.tags.update');
    Route::get('/admin/tags-delete/{id}', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'destroy'])->name('admin.tags.destroy');
    Route::get('/admin/changeTagStatus', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'changeTagStatus']);
    Route::post('/admin/get-subcategories-by-categoryid', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'getSubcategory']);
    Route::post('/admin/get-tags-by-subcategoryid', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'getTagsSubcategoryId']);

    /* Product Tags creation */
    Route::get('/admin/product-tags', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'index'])->name('admin.producttags.index');
    Route::post('/admin/product-tags', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'index'])->name('admin.producttags.indexsearch');
    Route::get('/admin/add-product-tags', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'create'])->name('admin.producttags.create');
    Route::post('/admin/add-product-tags', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'submit'])->name('admin.producttags.submit');
    Route::get('/admin/product-tags-edit/{id}', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'edit'])->name('admin.producttags.edit');
    Route::post('/admin/product-tags-edit/{id}', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'update'])->name('admin.producttags.update');
    Route::get('/admin/product-tags-delete/{id}', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'destroy'])->name('admin.producttags.destroy');
    Route::get('/admin/changeProductTagStatus', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'changeProductTagStatus']);
    Route::post('/get-subproductcategory-by-productcategoryid', [App\Http\Controllers\Admin\Product\ProductTagsController::class, 'getParentProductSubCategory']);

    /* OWN TAGS creation */
    Route::get('/admin/owntags', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'index'])->name('admin.owntags.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/owntags', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'index'])->name('admin.owntags.indexsearch');
    Route::get('/admin/add-owntags', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'create'])->name('admin.owntags.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-owntags', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'submit'])->name('admin.owntags.submit');
    Route::get('/admin/owntags-edit/{id}', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'edit'])->name('admin.owntags.edit');
    Route::post('/admin/owntags-edit/{id}', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'update'])->name('admin.owntags.update');
    Route::get('/admin/owntags-delete/{id}', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'destroy'])->name('admin.owntags.destroy');
    Route::get('/admin/changeownTagStatus', [App\Http\Controllers\Admin\Category\OwnTagsControllers::class, 'changeownTagStatus']);

    /* currency creation */
    Route::get('/admin/currency', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'index'])->name('admin.currency.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/currency', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'index'])->name('admin.currency.indexsearch');
    Route::get('/admin/add-currency', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'create'])->name('admin.currency.create');
    Route::post('/admin/add-currency', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'submit'])->name('admin.currency.submit');
    Route::get('/admin/currency-edit/{id}', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'edit'])->name('admin.currency.edit');
    Route::post('/admin/currency-edit/{id}', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'update'])->name('admin.currency.update');
    Route::get('/admin/currency-delete/{id}', [App\Http\Controllers\Admin\Currency\CurrencyControllers::class, 'destroy'])->name('admin.currency.destroy');

    /* Instruction creation */
    Route::get('/admin/instruction', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'index'])->name('admin.instruction.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/instruction', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'index'])->name('admin.instruction.indexsearch');
    Route::get('/admin/add-instruction', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'create'])->name('admin.instruction.create');
    Route::post('/admin/add-instruction', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'submit'])->name('admin.instruction.submit');

    Route::get('/admin/instruction-edit/{id}', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'edit'])->name('admin.instruction.edit');
    Route::post('/admin/instruction-edit/{id}', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'update'])->name('admin.instruction.update');
    Route::get('/admin/instruction-delete/{id}', [App\Http\Controllers\Admin\Instruction\InstructionControllers::class, 'destroy'])->name('admin.instruction.destroy');

    /* Trademark creation */
    Route::get('/admin/trademarks', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'index'])->name('admin.trademarks.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/trademarks', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'index'])->name('admin.trademarks.indexsearch');
    Route::get('/admin/add-trademarks', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'create'])->name('admin.trademarks.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-trademarks', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'submit'])->name('admin.trademarks.submit');
    Route::get('/admin/trademarks-edit/{id}', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'edit'])->name('admin.trademarks.edit');
    Route::post('/admin/trademarks-edit/{id}', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'update'])->name('admin.trademarks.update');
    Route::get('/admin/trademarks-delete/{id}', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'destroy'])->name('admin.trademarks.destroy');
    Route::post('/admin/changeTrademarkStatus', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'updateStatus']);
    Route::post('/admin/changeTrademarkbulkStatus', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'updateBulkStatus']);
    Route::get('/admin/pending/trademarks', [App\Http\Controllers\Admin\Trademark\TrademarksControllers::class, 'pendingtrademarks'])->name('admin.pending.trademarks')->middleware('ModuleAdminAuth');

    /* Trademark Category creation */
    Route::get('/admin/trademarks-category', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'index'])->name('admin.trademarkcategory.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/trademarks-category', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'index'])->name('admin.trademarkcategory.indexsearch');
    Route::get('/admin/add-trademarks-category', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'create'])->name('admin.trademarkcategory.create');
    Route::post('/admin/add-trademarks-category', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'submit'])->name('admin.trademarkcategory.submit');
    Route::get('/admin/trademarks-category-edit/{id}', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'edit'])->name('admin.trademarkcategory.edit');
    Route::post('/admin/trademarks-category-edit/{id}', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'update'])->name('admin.trademarkcategory.update');
    Route::get('/admin/trademarks-category-delete/{id}', [App\Http\Controllers\Admin\Trademark\TrademarkCategoryController::class, 'destroy'])->name('admin.trademarkcategory.destroy');

    /* Product creation */
    Route::get('/admin/product', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'index'])->name('admin.product.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/product', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'index'])->name('admin.product.indexsearch');
    Route::get('/admin/add-product', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'create'])->name('admin.product.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-product', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'submit'])->name('admin.product.submit');
    Route::get('/admin/product-edit/{id}', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'edit'])->name('admin.product.edit');
    Route::post('/admin/product-edit/{id}', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'update'])->name('admin.product.update');
    Route::get('/admin/product-delete/{id}', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'destroy'])->name('admin.product.destroy');
    Route::post('/admin/delete-gallery-img', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'deleteGalleryImageDelete']);
    Route::post('/admin/get-tags-by-directoryid', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'getTagsByDirectoryId']);
    Route::get('/admin/changeProductStatus', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'changeProductStatus']);
    Route::post('/admin/get-product-attribute-by-directoryid', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'getProductAttributeByDirectoryid']);
    Route::post('/get-subproductcat-by-productcategoryid', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'getProductParentSubCategory']);
    Route::post('/get-product-tags-by-subcategoriesid', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'getProductTags']);
    Route::get('/selectedmultipleproductsdelete', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'deleteMtltipleProducts']);

    /*Product Category Creation */
    Route::get('/admin/productcategories', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'index'])->name('admin.productcategories.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/productcategories', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'index'])->name('admin.productcategories.indexsearch');
    Route::get('/admin/add-productcategory', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'create'])->name('admin.productcategory.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-productcategory', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'submitProductCategory'])->name('admin.productcategory.submitProductCategory');
    Route::get('/admin/productcategory-edit/{id}', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'editProductCategory'])->name('admin.productcategory.editProductCategory');
    Route::post('/admin/productcategory-edit/{id}', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'updateProductCategory'])->name('admin.productcategory.updateProductCategory');
    Route::get('/admin/productcategory-delete/{id}', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'destroy'])->name('admin.productcategory.destroy');

    // Route::post('/admin/parent-productcategory', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'parent_productcat_categoryType']);
    Route::post('/admin/productcategoryseo', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'checkProductCategorySeo']);
    Route::get('/admin/product-tree-category', [App\Http\Controllers\Admin\Product\ProductCategoryController::class, 'producttree'])->name('admin.product.category.tree');

    /* appointment booking creation */
    Route::get('/admin/booking', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'index'])->name('admin.booking.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/booking', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'index'])->name('admin.booking.indexsearch');
    Route::get('/admin/booking-edit/{id}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'edit'])->name('admin.booking.edit');
    Route::post('/admin/booking-edit/{id}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'update'])->name('admin.booking.update');
    Route::get('/admin/booking-delete/{id}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'destroy'])->name('admin.booking.destroy');
    Route::get('/admin/selected-delete-booking', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'checkedDeleteBooking']);/* delete multiple records by selection checkbox */

    /* Contact Us listing */
    Route::get('/admin/contact-us', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'contactus'])->name('admin.contact.contactus')->middleware('ModuleAdminAuth');
    Route::post('/admin/contact-us', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'contactus'])->name('admin.contact.contactussearch');


    /* Ticket Request */
    Route::get('/admin/ticket/request/list', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'ticketRequestList'])->name('admin.ticket.requestList');
    Route::get('/admin/ticket/view/{id}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'ticketview'])->name('admin.ticket.view');
    Route::get('/admin/ticket/changeTicketStatus', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'changeTicketStatus']);
    Route::get('/admin/ticket-delete/{id}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'ticketdestroy'])->name('admin.ticket.destroy');
    Route::get('/admin/notifications', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'notifications'])->name('admin.notifications');
    Route::get('/admin/accept/{id}/{uid}/{tid}', [App\Http\Controllers\Admin\Booking\BookingControllers::class, 'accept']);

    /* offer creation */
    Route::get('/admin/offer', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'index'])->name('admin.offer.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/offer', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'index'])->name('admin.offer.indexsearch');
    Route::get('/admin/add-offer', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'create'])->name('admin.offer.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-offer', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'submit'])->name('admin.offer.submit');
    Route::get('/admin/offer-edit/{id}', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'edit'])->name('admin.offer.edit');
    Route::post('/admin/offer-edit/{id}', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'update'])->name('admin.offer.update');
    Route::get('/admin/offer-delete/{id}', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'destroy'])->name('admin.offer.destroy');
    Route::post('/admin/get-product-by-directory', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'getProduct']);
    Route::post('/admin/get-freeproduct-by-directory', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'getFreeProduct']);
    Route::post('/admin/delete-offergallery-img', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'deleteGalleryImageDelete']);
    Route::post('/admin/offerslug', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'checkOfferSlug']);
    Route::post('/admin/get-offer-subprocat-by-productcatid', [App\Http\Controllers\Admin\Offer\OfferControllers::class, 'getAdminOfferProductsubCategory']);

    /* Shopping Cart creation */
    Route::get('/admin/shoppingcard', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'index'])->name('admin.shoppingcard.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/shoppingcard', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'index'])->name('admin.shoppingcard.indexsearch');
    Route::get('/admin/add-shoppingcard', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'create'])->name('admin.shoppingcard.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-shoppingcard', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'submit'])->name('admin.shoppingcard.submit');
    Route::get('/admin/shoppingcard-edit/{id}', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'edit'])->name('admin.shoppingcard.edit');
    Route::post('/admin/shoppingcard-edit/{id}', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'update'])->name('admin.shoppingcard.update');
    Route::get('/admin/shoppingcard-delete/{id}', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'destroy'])->name('admin.shoppingcard.destroy');
    Route::post('/admin/delete-shoppingcardgallery-img', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'deleteGalleryImageDelete']);
    Route::post('/admin/shoppingcard-slug', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'checkShoppingcardSlug']);

    /* Inventory creation */
    Route::get('/admin/inventory', [App\Http\Controllers\Admin\Inventory\InventoryControllers::class, 'index'])->name('admin.inventory.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/inventory', [App\Http\Controllers\Admin\Inventory\InventoryControllers::class, 'index'])->name('admin.inventory.indexsearch');
    Route::post('/admin/inventory-edit/{id}', [App\Http\Controllers\Admin\Inventory\InventoryControllers::class, 'update'])->name('admin.inventory.update');

    /* Normal Template creation */
    Route::get('/admin/normal-template', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'index'])->name('admin.normaltemplate.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/normal-template', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'index'])->name('admin.normaltemplate.indexsearch');
    Route::post('/admin/importnormaltemp', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'importNormalTemp'])->name('admin.import.normaltemp');

    Route::get('/admin/add-normal-template', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'create'])->name('admin.normaltemplate.create');
    Route::post('/admin/add-normal-template', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'submit'])->name('admin.normaltemplate.submit');
    Route::get('/admin/normal-template-edit/{id}', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'edit'])->name('admin.normaltemplate.edit');
    Route::post('/admin/normal-template-edit/{id}', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'update'])->name('admin.normaltemplate.update');
    Route::get('/admin/normal-template-delete/{id}', [App\Http\Controllers\Admin\Template\NormalTemplateCategoryController::class, 'destroy'])->name('admin.normaltemplate.destroy');

    /* Holiday Template creation */
    Route::get('/admin/holiday-template', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'index'])->name('admin.holidaytemplate.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/holiday-template', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'index'])->name('admin.holidaytemplate.indexsearch');
    Route::get('/admin/add-holiday-template', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'create'])->name('admin.holidaytemplate.create');
    Route::post('/admin/add-holiday-template', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'submit'])->name('admin.holidaytemplate.submit');
    Route::get('/admin/holiday-template-edit/{id}', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'edit'])->name('admin.holidaytemplate.edit');
    Route::post('/admin/holiday-template-edit/{id}', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'update'])->name('admin.holidaytemplate.update');
    Route::get('/admin/holiday-template-delete/{id}', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'destroy'])->name('admin.holidaytemplate.destroy');
    Route::get('/admin/holiday-template-dataedit/{id}', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'dataedit'])->name('admin.holidaytemplate.dataedit');
    Route::post('/admin/holiday-template-dataedit/{id}', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'dataupdate'])->name('admin.holidaytemplate.dataupdate');
    Route::post('/admin/delete-holiday-template-record', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'deleteHolidayRecord']);
    Route::post('/admin/importholidaytemp', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'importHolidayTemp'])->name('admin.import.holidayemp');
    Route::post('/admin/importholidaytempInner', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'importHolidayTempInner'])->name('admin.import.holidayempinner');
    Route::get('/admin/publicholiday/export', [App\Http\Controllers\Admin\Template\HolidayTemplateCategoryController::class, 'exportPublicHolidayTempInner'])->name('admin.exportPublicHolidayInner');

    /* Directory Business creation */
    Route::get('/admin/business', [App\Http\Controllers\Admin\Directory\DirectoryBussinessController::class, 'index'])->name('admin.business.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/business', [App\Http\Controllers\Admin\Directory\DirectoryBussinessController::class, 'index'])->name('admin.business.indexsearch');

    /* Exception Holiday Template creation */
    Route::get('/admin/exceptionalholiday-template', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'index'])->name('admin.exceptionalholidaytemplate.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/exceptionalholiday-template', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'index'])->name('admin.exceptionalholidaytemplate.indexsearch');
    Route::get('/admin/add-exceptionalholiday-template', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'create'])->name('admin.exceptionalholidaytemplate.create');
    Route::post('/admin/add-exceptionalholiday-template', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'submit'])->name('admin.exceptionalholidaytemplate.submit');
    Route::get('/admin/exceptionalholiday-template-edit/{id}', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'edit'])->name('admin.exceptionalholidaytemplate.edit');
    Route::post('/admin/exceptionalholiday-template-edit/{id}', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'update'])->name('admin.exceptionalholidaytemplate.update');
    Route::get('/admin/exceptionalholiday-template-delete/{id}', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'destroy'])->name('admin.exceptionalholidaytemplate.destroy');
    Route::post('/admin/delete-exe-holiday-template-record', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'deleteHolidayRecord']);
    Route::post('/admin/importexceptionalholidaytemp', [App\Http\Controllers\Admin\Template\ExceptinalHolidayTemplateCategoryController::class, 'importExceptionalHolidayTemplate'])->name('admin.import.exceptionalholidaytemplate');

    /* Advertisement Template creation */
    Route::get('/admin/advertisement', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'index'])->name('admin.advertise.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/advertisement', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'index'])->name('admin.advertise.indexsearch');

    Route::get('/admin/hotads', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'index'])->name('admin.hotads.index');
    Route::post('/admin/hotads', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'index'])->name('admin.hotads.indexsearch');
    Route::get('/admin/add-hotad', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'create'])->name('admin.hotads.create');
    Route::post('/admin/add-hotad', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'submit'])->name('admin.hotads.submit');
    Route::get('/admin/changeHotAdStatus', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'changeHotAdStatus']);

    Route::get('/admin/hotads-edit/{id}', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'edit'])->name('admin.hotads.edit');
    Route::post('/admin/hotads-edit/{id}', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'update'])->name('admin.hotads.update');
    Route::get('/admin/hotads-delete-mp3/{id}', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'deleteMp3'])->name('admin.hotads.deleteMp3');
    Route::get('/admin/hotads-delete/{id}', [App\Http\Controllers\Admin\Advertisement\HotadsController::class, 'destroy'])->name('admin.hotads.destroy');

    Route::get('/admin/competition-participants/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'competitionParticipants'])->name('admin.competition.participants');
    Route::get('/admin/export-answer/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'exportAnswer']);

    Route::get('/admin/add-advertisement', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'create'])->name('admin.advertise.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-advertisement', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'submit'])->name('admin.advertise.submit');
    Route::get('/admin/advertisement-edit/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'edit'])->name('admin.advertise.edit');
    Route::post('/admin/advertisement-edit/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'update'])->name('admin.advertise.update');
    Route::get('/admin/advertisement-delete/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'destroy'])->name('admin.advertise.destroy');
    Route::post('/admin/addAnswerstatusprizebyId', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'addAnswerStatusPrizebyAdminId']);
     Route::post('/admin/choose-competition-winner', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'chooseCompetitionWinner']);
    Route::post('/admin/get-advertisementquestionsid', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'getAdvertisementQuestionsId']);
    Route::post('/admin/get-selected-answersid', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'getSelectedAnswersId']);
    Route::post('/admin/selectAutowinner/{id}', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'selectAutowinner']);
    Route::post('/admindeleteQueById', [App\Http\Controllers\Admin\Advertisement\AdvertisementController::class, 'deleteQueById']);

    /* Events creation */
    Route::get('/admin/events', [App\Http\Controllers\Admin\Events\EventsController::class, 'index'])->name('admin.events.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/events', [App\Http\Controllers\Admin\Events\EventsController::class, 'index'])->name('admin.events.indexsearch');
    Route::get('/admin/add-events', [App\Http\Controllers\Admin\Events\EventsController::class, 'create'])->name('admin.events.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-events', [App\Http\Controllers\Admin\Events\EventsController::class, 'submit'])->name('admin.events.submit');
    Route::get('/admin/events-edit/{id}', [App\Http\Controllers\Admin\Events\EventsController::class, 'edit'])->name('admin.events.edit');
    Route::post('/admin/events-edit/{id}', [App\Http\Controllers\Admin\Events\EventsController::class, 'update'])->name('admin.events.update');
    Route::get('/admin/events-delete/{id}', [App\Http\Controllers\Admin\Events\EventsController::class, 'destroy'])->name('admin.events.destroy');
    Route::post('/admin/delete-event-gallery', [App\Http\Controllers\Admin\Events\EventsController::class, 'deleteGalleryImage']);
    Route::post('/admin/eventslug', [App\Http\Controllers\Admin\Events\EventsController::class, 'checkEventSlug']);
    Route::get('/admin/changeEventStatus', [App\Http\Controllers\Admin\Events\EventsController::class, 'changeEventStatus']);

    /* Packages */
    Route::get('/admin/package', [App\Http\Controllers\Admin\Package\PackageController::class, 'index'])->name('admin.package.index');
    Route::post('/admin/package', [App\Http\Controllers\Admin\Package\PackageController::class, 'index'])->name('admin.package.indexsearch');
    Route::get('/admin/add-package', [App\Http\Controllers\Admin\Package\PackageController::class, 'create'])->name('admin.package.create');
    Route::post('/admin/add-package', [App\Http\Controllers\Admin\Package\PackageController::class, 'submit'])->name('admin.package.submit');
    Route::get('/admin/package-edit/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'edit'])->name('admin.package.edit');
    Route::post('/admin/package-edit/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'update'])->name('admin.package.update');
    Route::get('/admin/package-delete/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'destroy'])->name('admin.package.destroy');
    Route::get('/admin/manual-package-permission', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualindex'])->name('admin.manual.package.index');
    Route::post('/admin/manual-package-permission', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualindex'])->name('admin.manual.package.indexsearch');
    Route::get('/admin/manual-add-package', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualcreate'])->name('admin.manual.package.create');
    Route::post('/admin/manual-add-package', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualsubmit'])->name('admin.manual.package.submit');
    Route::get('/admin/manual-package-edit/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualedit'])->name('admin.manual.package.edit');
    Route::post('/admin/manual-package-edit/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualupdate'])->name('admin.manual.package.update');
    Route::get('/admin/manual-package-delete/{id}', [App\Http\Controllers\Admin\Package\PackageController::class, 'manualdestroy'])->name('admin.manual.package.destroy');
    Route::get('/admin/package-order-history', [App\Http\Controllers\Admin\Package\PackageController::class, 'packageHistory'])->name('admin.package.order.history');

    /* Review creation */
    Route::get('/admin/review', [App\Http\Controllers\Admin\Review\ReviewController::class, 'index'])->name('admin.review.index')->middleware('ModuleAdminAuth');
    Route::get('/admin/add-review', [App\Http\Controllers\Admin\Review\ReviewController::class, 'create'])->name('admin.review.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-review', [App\Http\Controllers\Admin\Review\ReviewController::class, 'submitReview'])->name('admin.review.submitReview');
    Route::get('/admin/review-edit/{id}', [App\Http\Controllers\Admin\Review\ReviewController::class, 'editReview'])->name('admin.review.editReview');
    Route::post('/admin/review-edit/{id}', [App\Http\Controllers\Admin\Review\ReviewController::class, 'updateReview'])->name('admin.review.updateReview');
    Route::get('/admin/review-delete/{id}', [App\Http\Controllers\Admin\Review\ReviewController::class, 'destroy'])->name('admin.review.destroy');
    Route::get('/admin/review/changeReviewStatus', [App\Http\Controllers\Admin\Review\ReviewController::class, 'changeReviewStatus']);

    /* Order Status */
    Route::get('/admin/order-status', [App\Http\Controllers\Admin\OrderStatusController::class, 'index'])->name('admin.orderstatus.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/order-status', [App\Http\Controllers\Admin\OrderStatusController::class, 'index'])->name('admin.orderstatus.indexsearch');
    Route::get('/admin/edit-order-status/{id}', [App\Http\Controllers\Admin\OrderStatusController::class, 'edit'])->name('admin.orderstatus.edit');
    Route::post('/admin/edit-order-status/{id}', [App\Http\Controllers\Admin\OrderStatusController::class, 'update'])->name('admin.orderstatus.update');
    Route::get('/admin/order-status-create', [App\Http\Controllers\Admin\OrderStatusController::class, 'create'])->name('admin.orderstatus.create');
    Route::post('/admin/order-status-submit', [App\Http\Controllers\Admin\OrderStatusController::class, 'submit'])->name('admin.orderstatus.submit');
    Route::get('/admin/order-status/changeStatus', [App\Http\Controllers\Admin\OrderStatusController::class, 'changeStatus']);

    /* Order creation */
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.order.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.order.indexsearch');
    Route::get('/admin/view/{id}', [App\Http\Controllers\Admin\OrderController::class, 'view'])->name('admin.order.view');
    Route::post('/admin/view/{id}', [App\Http\Controllers\Admin\OrderController::class, 'changeOrderStatus']);
    Route::post('/admin/makepayment/{id}', [App\Http\Controllers\Admin\OrderController::class, 'makepayment'])->name('admin.order.makepayment');

    Route::get('/admin/order-delete/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.order.destroy');
    Route::get('/admin/softdeleteorders', [App\Http\Controllers\Admin\OrderController::class, 'softDeletedOrders'])->name('admin.order.softdeleteorders');
    Route::get('/admin/permnentlydeleteorders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'permanentlydeleteOrder'])->name('admin.order.permnentlydeleteorders');
    Route::get('/admin/restore', [App\Http\Controllers\Admin\OrderController::class, 'restoredeleteOrder']);
    Route::get('/admin/bulk-delete-permanently', [App\Http\Controllers\Admin\OrderController::class, 'permanentlyBulkDeletedOrder']);
    Route::get('/admin/trashed-view/{id}', [App\Http\Controllers\Admin\OrderController::class, 'viewTrashedOrder'])->name('admin.order.trashedview');
    Route::get('/admin/pending-orders', [App\Http\Controllers\Admin\OrderController::class, 'pendingOrders'])->name('admin.order.pendingorders');
    Route::get('/admin/processing-orders', [App\Http\Controllers\Admin\OrderController::class, 'processingOrders'])->name('admin.order.processingorders');
    Route::get('/admin/payment-cancel-orders', [App\Http\Controllers\Admin\OrderController::class, 'paymentCancelOrders'])->name('admin.order.paymentcancelorders');
    Route::get('/admin/delivered-orders', [App\Http\Controllers\Admin\OrderController::class, 'deliveredOrders'])->name('admin.order.deliveredorders');
    Route::get('/admin/rto-orders', [App\Http\Controllers\Admin\OrderController::class, 'rtoOrders'])->name('admin.order.rtoorders');
    Route::get('/admin/refunded-orders', [App\Http\Controllers\Admin\OrderController::class, 'refundedOrders'])->name('admin.order.refundedorders');
    Route::get('/trashed-orders-generate-pdf/{id}', [App\Http\Controllers\Admin\OrderController::class, 'trashedOrderGeneratePDF'])->name('trashedordersgenerate.pdf');

    /* Discount creation */
    //Voucher Creation
    Route::get('/admin/discount/voucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'index'])->name('admin.discount.voucher.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/voucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'index'])->name('admin.discount.voucher.indexsearch');
    Route::get('/admin/discount/voucher/add-voucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'create'])->name('admin.discount.voucher.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/voucher/add-voucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'submitVoucher'])->name('admin.discount.voucher.submitVoucher');
    Route::get('/admin/discount/voucher/voucher-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'editVoucher'])->name('admin.discount.voucher.editVoucher');
    Route::post('/admin/discount/voucher/voucher-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'updateVoucher'])->name('admin.discount.voucher.updateVoucher');
    Route::get('/admin/discount/voucher/voucher-delete/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'destroyVoucher'])->name('admin.discount.voucher.destroyVoucher');

    //Gift Voucher Creation
    Route::get('/admin/discount/giftvoucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'indexGiftVoucher'])->name('admin.discount.giftvoucher.indexGiftVoucher')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/giftvoucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'indexGiftVoucher'])->name('admin.discount.giftvoucher.indexsearch');
    Route::get('/admin/discount/giftvoucher/add-giftvoucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'createGiftVoucher'])->name('admin.discount.giftvoucher.createGiftVoucher')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/giftvoucher/add-giftvoucher', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'submitGiftVoucher'])->name('admin.discount.giftvoucher.submitGiftVoucher');
    Route::get('/admin/discount/giftvoucher/giftvoucher-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'editGiftVoucher'])->name('admin.discount.giftvoucher.editGiftVoucher');
    Route::post('/admin/discount/giftvoucher/giftvoucher-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'updateGiftVoucher'])->name('admin.discount.giftvoucher.updateGiftVoucher');
    Route::get('/admin/discount/giftvoucher/giftvoucher-delete/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'destroyGiftVoucher'])->name('admin.discount.giftvoucher.destroyGiftVoucher');

    //Free Products Discount
    Route::get('/admin/discount/freeproductsdiscount', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'indexProductDiscount'])->name('admin.discount.freeproductsdiscount.indexProductDiscount')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/freeproductsdiscount', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'indexProductDiscount'])->name('admin.discount.freeproductsdiscount.indexsearch');
    Route::get('/admin/discount/freeproductsdiscount/add-productDiscount', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'createProductDiscount'])->name('admin.discount.freeproductsdiscount.createProductDiscount')->middleware('ModuleAdminAuth');
    Route::post('/admin/discount/freeproductsdiscount/add-productDiscount', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'submitProductDiscount'])->name('admin.discount.freeproductsdiscount.submitProductDiscount');
    Route::get('/admin/discount/freeproductsdiscount/productdiscount-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'editProductDiscount'])->name('admin.discount.freeproductsdiscount.editProductDiscount');
    Route::post('/admin/discount/freeproductsdiscount/productdiscount-edit/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'updateProductDiscount'])->name('admin.discount.freeproductsdiscount.updateProductDiscount');
    Route::get('/admin/discount/freeproductsdiscount/productdiscount-delete/{id}', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'destroyProductDiscount'])->name('admin.discount.freeproductsdiscount.destroyProductDiscount');
    Route::post('/admin/get-directory-product', [App\Http\Controllers\Admin\Discount\DiscountController::class, 'getProduct']);

    /* directory own Tags creation */
    Route::get('/admin/directory-own-tags', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'index'])->name('admin.directoryowntags.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/directory-own-tags', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'index'])->name('admin.directoryowntags.indexsearch');
    Route::get('/admin/add-directory-own-tags', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'create'])->name('admin.directoryowntags.create')->middleware('ModuleAdminAuth');
    Route::post('/admin/add-directory-own-tags', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'submit'])->name('admin.directoryowntags.submit');
    Route::get('/admin/directory-own-tags-edit/{id}', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'edit'])->name('admin.directoryowntags.edit');
    Route::post('/admin/directory-own-tags-edit/{id}', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'update'])->name('admin.directoryowntags.update');
    Route::get('/admin/directory-own-tags-delete/{id}', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'destroy'])->name('admin.directoryowntags.destroy');
    Route::get('/admin/changedirectoryownTagStatus', [App\Http\Controllers\Admin\Category\DirectoryOwnTagsControllers::class, 'changedirectoryownTagStatus']);

    /* Add New Country State City */
    Route::get('/admin/add-new-country', [App\Http\Controllers\DashboardContoller::class, 'getCountry'])->name('admin.new_country_state_city.country');
    Route::post('/admin/add-new-country', [App\Http\Controllers\DashboardContoller::class, 'addCountry'])->name('admin.new_country_state_city.addcountry');
    Route::get('/admin/add-new-state', [App\Http\Controllers\DashboardContoller::class, 'getState'])->name('admin.new_country_state_city.state');
    Route::post('/admin/add-new-state', [App\Http\Controllers\DashboardContoller::class, 'addState'])->name('admin.new_country_state_city.addstate');
    Route::get('/admin/add-new-city', [App\Http\Controllers\DashboardContoller::class, 'getCity'])->name('admin.new_country_state_city.city');
    Route::post('/admin/add-new-city', [App\Http\Controllers\DashboardContoller::class, 'addCity'])->name('admin.new_country_state_city.addcity');

    /* Distance creation */
    Route::get('/admin/distance', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'index'])->name('admin.distance.index')->middleware('ModuleAdminAuth');
    Route::post('/admin/distance', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'index'])->name('admin.distance.indexsearch');
    Route::get('/admin/add-distance', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'create'])->name('admin.distance.create');
    Route::post('/admin/add-distance', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'submit'])->name('admin.distance.submit');

    Route::get('/admin/distance-edit/{id}', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'edit'])->name('admin.distance.edit');
    Route::post('/admin/distance-edit/{id}', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'update'])->name('admin.distance.update');
    Route::get('/admin/distance-delete/{id}', [App\Http\Controllers\Admin\Distance\DistanceControllers::class, 'destroy'])->name('admin.distance.destroy');

});
