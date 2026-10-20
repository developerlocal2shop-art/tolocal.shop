<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Pages;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SellerRegisterController;
use App\Http\Controllers\Client\Listing;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\Blog;
use App\Http\Controllers\Client\Event;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\CompanyController;
use App\Http\Controllers\Account\CompanyBookingControllers;
use App\Http\Controllers\Account\CompanyDirectoryOwnTagsControllers;
use App\Http\Controllers\Account\CompanyDiscountController;
use App\Http\Controllers\Account\CompanyOfferControllers;
use App\Http\Controllers\Account\CompanyOrderController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\Account\CompanyDashboardContoller;
use App\Http\Controllers\Account\CompanyManualReportsContoller;
use App\Http\Controllers\Account\CompanyShoppingCardController;
use App\Http\Controllers\Account\CompanyEventsController;
use App\Http\Controllers\Account\CompanyBlogController;
use App\Http\Controllers\Account\CompanyAdvertisementController;
use App\Http\Controllers\Client\OfferControllers;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\Client\StripeController;
use App\Http\Controllers\Client\ThankuController;
use App\Http\Controllers\Client\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Account\MailtemplateVendorController;
use App\Http\Controllers\Account\MailVendorController;
use App\Http\Controllers\Account\HolidayTemplate\PublicHolidayTemplateControllers;
use App\Http\Controllers\Account\HolidayTemplate\NormalHolidayTemplateControllers;
use App\Http\Controllers\Account\HolidayTemplate\ExceptionHolidayTemplateControllers;
use App\Http\Controllers\Account\AffiliateController;
use App\Http\Controllers\Client\AboutController;
use Spatie\GoogleCalendar\Events;
use App\Http\Controllers\Client\LikeCounterControllers;
use App\Http\Controllers\PayPalWebhookController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/qr-test', function () {
    //return QrCode::size(200)->generate('Test QR');
});

Route::get('/', [Pages::class, 'index'])->name('pages');
Route::get('/home', [Pages::class, 'index'])->name('pages.home');
Route::post('/getcities', [Pages::class, 'getcities'])->name('get.cities');
Route::post('/setCityinSession', [Pages::class, 'setCitySession']);
Route::post('/companies-time', [Pages::class, 'findCompaniesOpenCloseTime']);
Route::post('/get-city-by-id', [Pages::class, 'getcitybyid'])->name('get.city.id');
Route::post('/get-city-by-name', [Pages::class, 'getcitybyname'])->name('get.city.name');
Route::get('/autocomplete-search', [Pages::class, 'autocompleteSearch']);
Route::post('/set-address-change', [CheckoutController::class, 'setAddressChange']);
Route::post('/updateDistanceSessionData', [Listing::class, 'updateDistanceSessionData']);

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/terms-and-condition', [AboutController::class, 'termsindex'])->name('terms-and-condition');
Route::get('/privacy-policy', [AboutController::class, 'privacyindex'])->name('privacy-policy');
Route::get('/sitemap', [AboutController::class, 'sitemap'])->name('page.sitemap');
Route::get('/faq', [AboutController::class, 'faqindex'])->name('faq');
Route::get('/cookie-policy', [AboutController::class, 'cookiepolicy'])->name('cookie-policy');
Route::get('/access-otp', [LoginController::class, 'accessByotp'])->name('accessByotp');

Route::get('/vendor-login', [LoginController::class, 'getVendorLogin'])->name('VendorLogin');
Route::post('/vendor-login', [LoginController::class, 'vendorpostLogin'])->name('VendorLoginPost');

Route::get('/login', [LoginController::class, 'getuserLogin'])->name('login');
Route::post('/login', [LoginController::class, 'postLogin'])->name('LoginPost');

Route::get('/competition-login', [LoginController::class, 'getCompetitionLogin'])->name('competitionLogin');
Route::post('/competition-login', [LoginController::class, 'postCompetitionLogin'])->name('competitionLoginPost');
Route::post('/competition/check-unique', [LoginController::class, 'competitionUniqueCheck'])->name('competition.unique.check');

// Facebook Login
Route::get('/login/facebook', [LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback', [LoginController::class, 'handleFacebookCallback']);
// Google Login
Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('/logout', [RegisterController::class, 'logout'])->name('Logout');

Route::get('/register', [RegisterController::class, 'registerUser'])->name('register.page');
Route::post('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/member-register', [RegisterController::class, 'membercreate'])->name('member.register.create');
Route::post('/competition-register', [RegisterController::class, 'competitionRegister'])->name('competition.register');

Route::get('/reset', [ForgotPasswordController::class, 'showForgot'])->name('userpassword.request');
Route::post('/reset', [ForgotPasswordController::class, 'forgotlink'])->name('user.password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('user.reset.password');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('user.password.update');

Route::post('/sendotp', [RegisterController::class, 'sendotp']);
Route::post('/sendemailotp', [RegisterController::class, 'sendemailotp']);
Route::post('/login-with-otp', [RegisterController::class, 'requestOTP']);
Route::post('/verify', [RegisterController::class, 'verify']);
Route::post('/verify-cotp', [RegisterController::class, 'verifyCotp']);
Route::post('/verify-loginotp', [RegisterController::class, 'verifyLoginOtp']);
Route::post('/sendcompany-emailotp', [RegisterController::class, 'sendcompanyEmailotp']);

Route::post('/seller-registration', [SellerRegisterController::class, 'create'])->name('registerseller.create');



Route::get('listings', [Listing::class, 'index'])->name('listing');
Route::post('listings', [Listing::class, 'index'])->name('filter_cat');

Route::get('listing/tag/{tag}', [Listing::class, 'listingbytag'])->name('listingbytag');
Route::get('listing/trademark/{trademark}', [Listing::class, 'listingbytrademark'])->name('listingbytrademark');
Route::get('directories-map', [Listing::class, 'directories_map'])->name('directories.map');
Route::get('listings-product/{id}', [Listing::class, 'productListing'])->name('directory.product');

Route::get('/load-most-viewed-listings', [Listing::class, 'loadMostViewedListings'])->name('load.most.viewed.listings');
Route::get('/load-tree-structure-listings', [Listing::class, 'loadTreeStructureListings'])->name('load.tree.structure.listings');
Route::get('/load-tree-structure-shop', [Listing::class, 'loadTreeStructureShop'])->name('load.tree.structure.shop');

Route::get('listings/details/{id}', [Listing::class, 'details'])->name('listing.details');

Route::post('listings/details/booking', [Listing::class, 'appointmentBooking'])->name('appointment.booking');
Route::post('listings/customerclub/reg', [Listing::class, 'CustomerClubReg'])->name('customerclub.reg');
Route::get('customer-club/messages', [Listing::class, 'getMessages'])->name('get.all.customer.club.messages');

Route::get('delete/customer/club', [Listing::class, 'deleteCustomerClub'])->name('delete.customer.club');

Route::get('redeem/offers/customerclubbyemail', [Listing::class, 'redeemOffersCustomerClubbyEmail'])->name('get.all.redeem.offers.customer.club');
Route::post('user/request/vendor', [Listing::class, 'userContactVendor'])->name('user.request.vendor');

Route::get('location', [Listing::class, 'getGeoLocation']);
Route::get('categories', [Listing::class, 'allCategory'])->name('category');
Route::get('/category/{id}', [Pages::class, 'category'])->name('categoryid');
Route::get('/category-product/{id}', [ShopController::class, 'categoryProduct'])->name('category.product');
Route::get('contact', [App\Http\Controllers\Client\ContactController::class, 'index'])->name('contact');
Route::post('contact', [App\Http\Controllers\Client\ContactController::class, 'submitContact'])->name('contact.submitContact');


Route::get('shoppingcard', [Listing::class, 'allShoppingCard'])->name('shoppingcard');
Route::get('competitions', [Listing::class, 'competitions'])->name('competitions');
Route::get('hotoffer', [Listing::class, 'allHotOffers'])->name('hotoffer');
Route::get('specialoffer', [Listing::class, 'allSpecialOffers'])->name('specialoffer');
Route::get('hotadslist', [Pages::class, 'hotaddslist'])->name('hotadslist');
Route::get('/hotads/detail/{id}', [Pages::class, 'hotaddetails'])->name('hotads.detail');
Route::get('/competition/detail/{id}', [Pages::class, 'competitiondetails'])->name('competition.detail');
Route::get('competitionlist', [Pages::class, 'competitionlist'])->name('competition.list');
Route::get('hotoffer/{id}', [Listing::class, 'DirectoryHotOffers'])->name('directory.hotoffers');

Route::get('shoppingcard/{id}', [Listing::class, 'DirectoryShoppingCard'])->name('directory.shoppingcards');
Route::post('addreview/{products}', [Listing::class, 'addreview']);

Route::get('blog', [Blog::class, 'index'])->name('blog');
Route::post('/blog', [Blog::class, 'index'])->name('blog.indexsearch');
Route::get('/blog/details/{id}', [Blog::class, 'details'])->name('blog-details');
Route::get('/blog/category/{id}', [Blog::class, 'categorydetails'])->name('blog-categorydata');
Route::post('/blog/category/{id}', [Blog::class, 'index'])->name('blogcat.indexsearch');

Route::get('offer/detail/{id}', [OfferControllers::class, 'details'])->name('offer.detail');
Route::get('shopping-card/detail/{id}', [OfferControllers::class, 'shoppingcarddetails'])->name('shoppingcard.detail');
Route::get('hotads/details/{link}', [Listing::class, 'hotadsdetails'])->name('hotads.linkdetail');

Route::get('shop', [ShopController::class, 'shop'])->name('shop');
Route::get('product-comparison', [ShopController::class, 'productComparison'])->name('productComparison');
Route::get('product/comparison/{slug}/{id}', [ShopController::class, 'companyproductComparison'])->name('companyproductComparison');

Route::get('product/detail/{id}/{slug}', [Listing::class, 'shopdetails'])->name('shop-details');
Route::get('/affiliate/go/{code}', [AffiliateController::class, 'redirectLink'])->name('affiliate.link.redirect');
Route::get('share/product/{id}', [Listing::class, 'shareonFacebook'])->name('share-facebook');
Route::get('share/shoping-card/{id}', [Listing::class, 'shareshoppingcardonFacebook'])->name('share-shoppin-card-facebook');
Route::get('share/offer-facebook-share/{id}', [Listing::class, 'shareofferonFacebook'])->name('share-offer-facebook');

Route::get('shop/tags-product/{tag}', [ShopController::class, 'productListUsingTags'])->name('product.details.tag');
Route::get('search', [Pages::class, 'search'])->name('search.banner');
Route::get('autocomplete', [Pages::class, 'autocomplete'])->name('autocomplete');

Route::get('events', [Event::class, 'index'])->name('event');
Route::post('/events', [Event::class, 'index'])->name('event.indexsearch');
Route::get('addToCalender', [Event::class, 'addToCalender'])->name('addToCalender');
Route::get('/events/category/{id}', [Event::class, 'categoryDetailsEvent'])->name('event-categorydata');
Route::post('/events/category/{id}', [Event::class, 'categoryDetailsEvent'])->name('eventcat.indexsearch');

Route::get('/event/details/{slug}', [Event::class, 'details'])->name('event-details');
// Route::get('offer/{id}', [App\Http\Controllers\Client\Offer::class, 'offer'])->name('offer');

Route::post('/add-to-wishlist/{id}', [WishlistController::class, 'addToWishlist']);
Route::post('/add-to-wishlist-directory/{id}', [WishlistController::class, 'addDirectoryToWishlist']);
Route::post('/addsaveforlatter/{id}', [WishlistController::class, 'addsaveforlatter']);
Route::post('/addto-compare/{id}', [WishlistController::class, 'addtocompare']);

Route::post('/add-to-like/{id}', [LikeCounterControllers::class, 'addToLikeCounter']);

Route::post('/get-states-by-country', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getState']);
Route::post('/get-directory-type-galleries', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getDirectoryTypeGalleries']);
Route::post('/get-directory-type-pdf', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getDirectoryTypePdf']);
Route::post('/delete-directory-type-gallery', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteDirectoryTypeGallery']);
Route::post('/update-directory-type-galleries-sort', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'updateDirectoryTypeGalleriesBySort']);

Route::get('/selectedgallerytypedelete', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteCheckedTypeGallery']);
Route::get('/deleteCheckedListingGallery', [CompanyController::class, 'deleteCheckedListingGallery']);

Route::get('/selectedpdftypedelete', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteCheckedTypePdf']);
Route::post('/delete-directory-type-pdf', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteDirectoryTypePdf']);


Route::post('/get-cities-by-state', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getCity']);
Route::post('/get-type-block', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTypeBlock']);

Route::post('/get-countrybyid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getCountrybyId']);
Route::post('/get-subcategories-by-categoryid', [App\Http\Controllers\Admin\Category\TagsControllers::class, 'getSubcategory']);

Route::post('get-product-variations/{id}', [Listing::class, 'product_variations'])->name('product.variations');
Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
Route::patch('update-cart', [CartController::class, 'update'])->name('update.cart');
Route::delete('remove-item-cart', [CartController::class, 'remove'])->name('remove.item.cart');
Route::get('remove-from-cart', [CartController::class, 'clearAllCart'])->name('remove.from.cart');
Route::post('apply-coupon-code', [CartController::class, 'applyCouponCode'])->name('apply.coupon.code.to.cart');
Route::post('/getvendor-imppro-tagsbyid', [CompanyController::class, 'getVendorImpProTagsbyId']);
Route::post('/delete-imppro-tagsbyid', [CompanyController::class, 'deleteImpProTagsbyId']);

Route::post('/paypal/webhook', [PayPalWebhookController::class, 'handle']);

Route::group(['middleware' => ['isuserlogin']], function () {

    Route::post('/parent-category', [App\Http\Controllers\Admin\Category\CategoryController::class, 'parentcat_categoryType']);
    Route::post('/categoryseo', [App\Http\Controllers\Admin\Category\CategoryController::class, 'checkCategorySeo']);
    Route::post('/directoryseo', [App\Http\Controllers\Admin\Category\CategoryController::class, 'checkDirectorySeo']);
    Route::post('/get-tagsandOwntags-by-categoriesid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTagsAndOwntags']);
    Route::post('/get-dirtags-by-subcategoriesid', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'getTags']);

    Route::post('/delete-holiday-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteHolidayRecord']);
    Route::post('/delete-exceptional-holiday-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteExecptionHolidayRecord']);
    Route::post('/delete-public-holiday-yes-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteHolidayPublicYesRecord']);
    Route::post('/delete-exceptional-holiday-yes-record', [App\Http\Controllers\Admin\Directory\DirectoryController::class, 'deleteHolidayExceptionalYesRecord']);

    Route::get('/generate-pdf/{id}', [CompanyOrderController::class, 'generatePDF'])->name('generate.pdf');

    Route::post('/company/refund/request', [CompanyOrderController::class, 'refundRequest'])->name('refund.request');

    Route::post('/get-advertisementquestionsid', [CompanyAdvertisementController::class, 'getAdvertisementQuestionsId']);
    Route::post('/selectAutowinner/{id}', [CompanyAdvertisementController::class, 'selectAutowinner']);
    Route::post('/updateanswerstatusbyid', [CompanyAdvertisementController::class, 'updateAnswerStatusbyId']);
    Route::post('/addAnswerstatusprizebyId', [CompanyAdvertisementController::class, 'addAnswerStatusPrizebyId']);
    Route::post('/getAutoinnerPrizebyId', [CompanyAdvertisementController::class, 'getAutoinnerPrizebyId']);
    Route::post('/admingetAutoinnerPrizebyId', [CompanyAdvertisementController::class, 'AdmingetAutoinnerPrizebyId']);
    Route::post('/deleteQueById', [CompanyAdvertisementController::class, 'deleteQueById']);
    Route::post('/get-selected-answersid', [CompanyAdvertisementController::class, 'getSelectedAnswersId']);
    Route::post('/company/alluser-autowinner', [CompanyAdvertisementController::class, 'chooseAlluserAutowinner']);
    Route::post('/update-question-winner-data', [CompanyAdvertisementController::class, 'updateQuestionWinnerData'])->name('update.question.winner.data');
    Route::get('/export-answer/{id}', [CompanyAdvertisementController::class, 'exportAnswer']);

    /* Company route*/
    Route::group(['middleware' => 'role:1'], function () {

        Route::post('/company/choose-competition-winner', [CompanyAdvertisementController::class, 'chooseCompetitionWinner']);
        Route::post('/company/choose-auto-alluser-competition-winner', [CompanyAdvertisementController::class, 'chooseAutoAlluserCompetitionWinner']);

        /* Product Attributes */
        Route::get('/company/attributes', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'index'])->name('company.attributes.index');
        Route::get('/company/add-attribute', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'create'])->name('company.attribute.create');
        Route::post('/company/add-attribute', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'submit'])->name('company.attribute.submit');
        Route::get('/company/attribute-edit/{id}', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'edit'])->name('company.attribute.edit');
        Route::post('/company/attribute-edit/{id}', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'update'])->name('company.attribute.update');
        Route::get('/company/attribute-delete/{id}', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'destroy'])->name('company.attribute.destroy');
        Route::get('/company/changeAttributeStatus', [App\Http\Controllers\Account\Attributes\AttributesController::class, 'changeStatus']);

        Route::get('company/packages', [CompanyController::class, 'packages'])->name('company.packages');
        Route::get('package/{plan_id}', [CompanyController::class, 'packagesdetails'])->name('company.package.details');
        Route::post('package-place', [CheckoutController::class, 'placePackage'])->name('package.place');
        Route::get('/company/getoffersbytype', [CompanyController::class, 'getOffersByType'])->name('get.offers.by.type');
        Route::get('/redirect', [CompanyController::class, 'redirect']);
        Route::get('/company/verify/stripe', [CompanyController::class, 'verifyStripe'])->name('company.verify.stripe');

        Route::get('/company-profile', [CompanyController::class, 'companyprofile'])->name('companyprofile');
        Route::get('/complate-companyinfo', [CompanyController::class, 'complatecompanyinfo'])->name('complatecompanyinfo');
        Route::get('/company', [CompanyController::class, 'companydashboard'])->name('company');
        Route::post('/company', [CompanyController::class, 'savedirectory'])->name('directory.submit');

        Route::get('importExportView', [CompanyController::class, 'importExportView']);
        Route::get('export', [CompanyController::class, 'export'])->name('export');
        Route::post('import', [CompanyController::class, 'import'])->name('company.import');
        Route::post('/company/booking/reply', [CompanyController::class, 'appointmentBookingReply'])->name('appointment.booking.reply');

        Route::get('/company-information', [CompanyController::class, 'editcompanyinformation'])->name('editcompanyinformation')->middleware('ModuleAuth');
        Route::post('/company-information', [CompanyController::class, 'updatecompanyinformation'])->name('updatecompanyinformation');
        Route::post('/company-information/cover-image', [CompanyController::class, 'uploadCompanyCoverImage'])->name('company.cover-image.upload');
        Route::post('/company-information/gallery-image-with-text', [CompanyController::class, 'uploadCompanyGalleryImageWithText'])->name('company.gallery-image-with-text.upload');
        Route::get('/company/products', [CompanyController::class, 'companyproducts'])->name('company.products')->middleware('ModuleAuth');
        Route::post('/company/products', [CompanyController::class, 'companyproducts'])->name('company.products.save');
        Route::post('/company/get-trademarks-by-categoriesid', [CompanyController::class, 'getTrademarksByCatid']);
        Route::get('/changeVendorProductStatus', [CompanyController::class, 'changeVendorProductStatus']);

        Route::get('/changeCustomerClubStatus', [CompanyController::class, 'changeCustomerClubStatus']);

        Route::get('/selectedproductsdelete', [CompanyController::class, 'deleteCheckedProducts']);
        Route::get('/company/shipping', [CompanyController::class, 'companyshipping'])->name('company.shipping')->middleware('ModuleAuth');
        Route::post('/company/importholidaypublic', [CompanyController::class, 'importHolidaypublic'])->name('vendor.import.public.holiday');
        Route::post('/company/importholidaytempName', [CompanyController::class, 'importHolidayTempName'])->name('vendor.import.holidaytemp.name');
        Route::post('/company/importholidaytempInner', [CompanyController::class, 'importHolidayTempInner'])->name('vendor.import.holidayempinner');

        Route::post('/company/importholidayexceptional', [CompanyController::class, 'importHolidayExceptional'])->name('vendor.import.exceptional.holiday');
        Route::post('/company/add-exceptional-holiday-tempName', [CompanyController::class, 'addExceptionalHolidayTempName'])->name('vendor.import.exceptional.temp.name');
        Route::post('/company/importexceptionalholidaytempInner', [CompanyController::class, 'importExceptionalHolidayTempInner'])->name('vendor.import.exceptional.holidayempinner');


         /* Review creation */
        Route::get('/company/review', [App\Http\Controllers\Account\Review\ReviewController::class, 'index'])->name('company.review.index')->middleware('ModuleAdminAuth');
        Route::get('/company/add-review', [App\Http\Controllers\Account\Review\ReviewController::class, 'create'])->name('company.review.create')->middleware('ModuleAdminAuth');
        Route::post('/company/add-review', [App\Http\Controllers\Account\Review\ReviewController::class, 'submitReview'])->name('company.review.submitReview');
        Route::get('/company/review-edit/{id}', [App\Http\Controllers\Account\Review\ReviewController::class, 'editReview'])->name('company.review.editReview');
        Route::post('/company/review-edit/{id}', [App\Http\Controllers\Account\Review\ReviewController::class, 'updateReview'])->name('company.review.updateReview');
        Route::get('/company/review-delete/{id}', [App\Http\Controllers\Account\Review\ReviewController::class, 'destroy'])->name('company.review.destroy');
        Route::get('/company/review/changeReviewStatus', [App\Http\Controllers\Account\Review\ReviewController::class, 'companyChangeReviewStatus']);

         /* Sipping managment */
        Route::get('/company/shipping', [App\Http\Controllers\Account\ShippingController::class, 'index'])->name('company.shipping.index');
        Route::post('/company/shipping', [App\Http\Controllers\Account\ShippingController::class, 'index'])->name('company.shipping.indexsearch');
        Route::get('/company/shipping-edit/{id}', [App\Http\Controllers\Account\ShippingController::class, 'edit'])->name('company.shipping.edit');
        Route::post('/company/shipping-update', [App\Http\Controllers\Account\ShippingController::class, 'update'])->name('company.shipping.update');
        Route::get('/company/shipping-delete/{id}', [App\Http\Controllers\Account\ShippingController::class, 'destroy'])->name('company.shipping.destroy');
        Route::get('/company/changeShippingStatus', [App\Http\Controllers\Account\ShippingController::class, 'changeStatus']);


        Route::get('/company/add-product', [CompanyController::class, 'companyaddproduct'])->name('company.addproduct')->middleware('ModuleAuth');
        Route::post('/company/add-product', [CompanyController::class, 'companyproductsubmit'])->name('company.product.submit');

        Route::post('/get-subprocat-by-productcatid', [CompanyController::class, 'getProductsubCategory']);
        Route::post('/get-tags-by-subcategoriesid', [CompanyController::class, 'getsubcatProductTags']);
        Route::post('/company/normal/get-normaltemplate-by-id', [CompanyController::class, 'getNormalTemplateData']);
        Route::post('/company/public/get-publictemplate-by-id', [CompanyController::class, 'getPublicTemplateData']);
        Route::post('/company/exception/get-exceptiontemplate-by-id', [CompanyController::class, 'getExceptionTemplateOption']);

        Route::post('/company/get-gallery-img', [CompanyController::class, 'getGalleryImageDelete']);
        Route::post('/company/get-cover-img', [CompanyController::class, 'getCoverImageDelete']);
        Route::post('/company/get-thumb-img', [CompanyController::class, 'getThumbImageDelete']);
        Route::post('/company/get-trademark-img', [CompanyController::class, 'getTrademarkImageDelete']);
        Route::post('/company/gallery-image-delete-all', [CompanyController::class, 'galleryImageDeleteAll'])->name('company.galleryImageDeleteAll');

        Route::get('/paypal/subscription/success', [CompanyController::class, 'subscriptionSuccess'])->name('paypal.subscription.success');
        Route::get('/paypal/subscription/cancel', [CompanyController::class, 'subscriptionCancel'])->name('paypal.subscription.cancel');

        /* NORMAL HOLIDAY creation START */
        Route::get('/company/normal/list-template', [NormalHolidayTemplateControllers::class, 'companyaddcustomTemplate'])->name('company.normal.addtemplate');
        Route::get('/company/normal/add-template', [NormalHolidayTemplateControllers::class, 'createtemplate'])->name('company.normal.template.create');
        Route::post('/company/normal/add-template', [NormalHolidayTemplateControllers::class, 'submittemplate'])->name('company.normal.template.submit');
        Route::get('/company/normal/template-edit/{id}', [NormalHolidayTemplateControllers::class, 'edittemplate'])->name('company.normal.template.edit');
        Route::post('/company/normal/template-edit/{id}', [NormalHolidayTemplateControllers::class, 'updatetemplate'])->name('company.normal.template.update');
        Route::get('/company/normal/template-delete/{id}', [NormalHolidayTemplateControllers::class, 'destroytemplate'])->name('company.normal.template.destroy');

        Route::get('/company/normal/holiday-template-dataedit/{id}', [NormalHolidayTemplateControllers::class, 'publicDataEdit'])->name('company.normal.holidaytemplate.dataedit');
        Route::post('/company/normal/holiday-template-dataedit/{id}', [NormalHolidayTemplateControllers::class, 'publicDataUpdate'])->name('company.normal.holidaytemplate.dataupdate');
        Route::post('/company/normal/delete-holiday-template-record', [NormalHolidayTemplateControllers::class, 'deleteHolidayRecord']);
        /* NORMAL HOLIDAY creation END */

        /* PUBLIC HOLIDAY creation START */
        Route::get('/company/public/list-template', [PublicHolidayTemplateControllers::class, 'companyaddcustomTemplate'])->name('company.addtemplate');
        Route::get('/company/public/add-template', [PublicHolidayTemplateControllers::class, 'createtemplate'])->name('company.template.create');
        Route::post('/company/public/add-template', [PublicHolidayTemplateControllers::class, 'submittemplate'])->name('company.template.submit');
        Route::get('/company/public/template-edit/{id}', [PublicHolidayTemplateControllers::class, 'edittemplate'])->name('company.template.edit');
        Route::post('/company/public/template-edit/{id}', [PublicHolidayTemplateControllers::class, 'updatetemplate'])->name('company.template.update');
        Route::get('/company/public/template-delete/{id}', [PublicHolidayTemplateControllers::class, 'destroytemplate'])->name('company.template.destroy');

        Route::get('/company/public/holiday-template-dataedit/{id}', [PublicHolidayTemplateControllers::class, 'publicDataEdit'])->name('company.publicholidaytemplate.dataedit');
        Route::post('/company/public/holiday-template-dataedit/{id}', [PublicHolidayTemplateControllers::class, 'publicDataUpdate'])->name('company.publicholidaytemplate.dataupdate');
        Route::post('/company/public/delete-holiday-template-record', [PublicHolidayTemplateControllers::class, 'deletePublicHolidayRecord']);
        /* PUBLIC HOLIDAY creation END */
        /* EXCEPTION HOLIDAY creation START */
        Route::get('/company/exception/list-template', [ExceptionHolidayTemplateControllers::class, 'companyaddcustomTemplate'])->name('company.exception.addtemplate');
        Route::get('/company/exception/add-template', [ExceptionHolidayTemplateControllers::class, 'createtemplate'])->name('company.exception.template.create');
        Route::post('/company/exception/add-template', [ExceptionHolidayTemplateControllers::class, 'submittemplate'])->name('company.exception.template.submit');
        Route::get('/company/exception/template-edit/{id}', [ExceptionHolidayTemplateControllers::class, 'edittemplate'])->name('company.exception.template.edit');
        Route::post('/company/exception/template-edit/{id}', [ExceptionHolidayTemplateControllers::class, 'updatetemplate'])->name('company.exception.template.update');
        Route::get('/company/exception/template-delete/{id}', [ExceptionHolidayTemplateControllers::class, 'destroytemplate'])->name('company.exception.template.destroy');

        Route::get('/company/exception/holiday-template-dataedit/{id}', [ExceptionHolidayTemplateControllers::class, 'DataEdit'])->name('company.exception.holidaytemplate.dataedit');
        Route::post('/company/exception/holiday-template-dataedit/{id}', [ExceptionHolidayTemplateControllers::class, 'DataUpdate'])->name('company.exception.holidaytemplate.dataupdate');
        Route::post('/company/exception/delete-holiday-template-record', [ExceptionHolidayTemplateControllers::class, 'deleteHolidayRecord']);
        /* EXCEPTION HOLIDAY creation END */

        Route::get('/company/customer/club', [CompanyController::class, 'customerClubList'])->name('company.customer.club');
        Route::get('/company/customer/club/export', [CompanyController::class, 'exportCustomerClub'])->name('company.customer.club.export');
        Route::get('/company/product-edit/{id}', [CompanyController::class, 'companyedit'])->name('company.product.edit');
        Route::post('/company/product-edit/{id}', [CompanyController::class, 'companyupdate'])->name('company.product.update');
        Route::get('/company/product-delete/{id}', [CompanyController::class, 'destroy'])->name('company.product.destroy');
        Route::post('/company/delete-gallery-img', [App\Http\Controllers\Admin\Product\ProductControllers::class, 'deleteGalleryImageDelete']);
        Route::post('/company/get-amenity-by-amenitycategory', [CompanyController::class, 'getAmenity']);
        Route::post('/redeem-voucher', [CompanyController::class, 'redeemvoucher'])->name('redeem.voucher');


        /* Trademark creation */
        Route::get('/company/trademarks', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'index'])->name('company.trademarks.index')->middleware('ModuleAuth');
        Route::post('/company/trademarks', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'index'])->name('company.trademarks.indexsearch');
        Route::get('/company/add-trademarks', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'create'])->name('company.trademarks.create')->middleware('ModuleAuth');
        Route::post('/company/add-trademarks', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'submit'])->name('company.trademarks.submit');
        Route::get('/company/trademarks-edit/{id}', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'edit'])->name('company.trademarks.edit');
        Route::post('/company/trademarks-edit/{id}', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'update'])->name('company.trademarks.update');
        Route::get('/company/trademarks-delete/{id}', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'destroy'])->name('company.trademarks.destroy');

        Route::post('/check-trademark', [App\Http\Controllers\Account\Trademark\TrademarksControllers::class, 'checktrademark'])->name('check.trademark');

        /* Trademark Category creation */
        Route::get('/company/trademarks-category', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'index'])->name('company.trademarkcategory.index')->middleware('ModuleAuth');
        Route::post('/company/trademarks-category', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'index'])->name('company.trademarkcategory.indexsearch');
        Route::get('/company/add-trademarks-category', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'create'])->name('company.trademarkcategory.create');
        Route::post('/company/add-trademarks-category', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'submit'])->name('company.trademarkcategory.submit');
        Route::get('/company/trademarks-category-edit/{id}', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'edit'])->name('company.trademarkcategory.edit');
        Route::post('/company/trademarks-category-edit/{id}', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'update'])->name('company.trademarkcategory.update');
        Route::get('/company/trademarks-category-delete/{id}', [App\Http\Controllers\Account\Trademark\TrademarkCategoryController::class, 'destroy'])->name('company.trademarkcategory.destroy');

        //Reports
        Route::get('/company/reports', [CompanyDashboardContoller::class, 'reportsview'])->name('company.reports.view');
        Route::get('/company/reports/dashboard', [CompanyDashboardContoller::class, 'reportsdashboard'])->name('company.report.dashboard')->middleware('ModuleAuth');
        Route::post('/company/reports/dashboard', [CompanyDashboardContoller::class, 'reportsdashboard'])->name('company.filter.reports.dashboard');
        Route::get('/company/affiliate/dashboard', [App\Http\Controllers\Account\AffiliateVendorController::class, 'dashboard'])->name('vendor.affiliate.dashboard');
        Route::get('/company/affiliate/settings', [App\Http\Controllers\Account\AffiliateVendorController::class, 'edit'])->name('vendor.affiliate.settings');
        Route::post('/company/affiliate/settings', [App\Http\Controllers\Account\AffiliateVendorController::class, 'update'])->name('vendor.affiliate.settings.update');
        Route::get('/company/ordersfilter', [CompanyDashboardContoller::class, 'ordersfilter'])->name('company.orders.ordersfilter');
        Route::post('/company/liked-data', [CompanyDashboardContoller::class, 'vendorlikedDetails']);
        Route::post('/company/visitor-data', [CompanyDashboardContoller::class, 'vendorVisitorsDetails']);

        Route::post('/vendor/paypal/save', [CompanyDashboardContoller::class, 'paypalstoredetail'])->name('vendor.paypal.save');

        //Manual Reports
        Route::get('/company/offline-reports', [CompanyManualReportsContoller::class, 'manualreportsview'])->name('company.offline.reports')->middleware('ModuleAuth');
        Route::post('/company/offline-reports', [CompanyManualReportsContoller::class, 'create'])->name('company.offline.submit.reports');
        Route::get('/company/allvisitors', [CompanyManualReportsContoller::class, 'allvisitors'])->name('company.all.visitors')->middleware('ModuleAuth');
        Route::post('/company/allvisitors', [CompanyManualReportsContoller::class, 'allvisitors'])->name('company.all.visitorsfilter');
        Route::get('/company/allreceiptclosing', [CompanyManualReportsContoller::class, 'allreceiptclosing'])->name('company.all.receiptclosing')->middleware('ModuleAuth');
        Route::post('/company/allreceiptclosing', [CompanyManualReportsContoller::class, 'allreceiptclosing'])->name('company.all.receiptclosingfilter');
        Route::get('/company/allextra/reports', [CompanyManualReportsContoller::class, 'allextrareports'])->name('company.allextra.reports')->middleware('ModuleAuth');
        Route::post('/company/allextra/reports', [CompanyManualReportsContoller::class, 'allextrareports'])->name('company.allextra.reportsfilter');
        Route::get('/company/offline-reports-edit/{id}', [CompanyManualReportsContoller::class, 'edit'])->name('company.offline.reports.edit');
        Route::post('/company/offline-reports-edit/{id}', [CompanyManualReportsContoller::class, 'update'])->name('company.offline.reports.update');
        Route::get('/company/offline-reports-delete/{id}', [CompanyManualReportsContoller::class, 'destroy'])->name('company.offline.reports.destroy');
        Route::get('/company/offline-reports/changeStatus', [CompanyManualReportsContoller::class, 'changeStatus']);

        //Shopping Card
        Route::get('/company/shoppingcard', [CompanyShoppingCardController::class, 'shoppingcardList'])->name('company.shoppingcard')->middleware('ModuleAuth');
        Route::post('/company/shoppingcard', [CompanyShoppingCardController::class, 'shoppingcardList'])->name('company.shoppingcard.indexsearch');
        Route::get('/company/shoppingcard/customerprize/{id}', [CompanyShoppingCardController::class, 'shoppingcardListcustomerprize'])->name('company.shoppingcard.customerprize');

        Route::get('/company/add-shoppingcard', [CompanyShoppingCardController::class, 'companyaddshoppingcard'])->name('company.addshoppingcard')->middleware('ModuleAuth');
        Route::post('/company/add-shoppingcard', [CompanyShoppingCardController::class, 'companyaddshoppingcardsubmit'])->name('company.shoppingcard.submit');
        Route::get('/company/shoppingcard-edit/{id}', [CompanyShoppingCardController::class, 'companyshoppingcardedit'])->name('company.shoppingcard.edit');
        Route::post('/company/shoppingcard-edit/{id}', [CompanyShoppingCardController::class, 'companyshoppingcardupdate'])->name('company.shoppingcard.update');
        Route::get('/company/shoppingcard-delete/{id}', [CompanyShoppingCardController::class, 'destroy'])->name('company.shoppingcard.destroy');
        Route::post('/company/delete-shoppingcardgallery-img', [App\Http\Controllers\Admin\ShoppingCard\ShoppingCardController::class, 'deleteGalleryImageDelete']);
        Route::post('/company/get-product-by-directory', [CompanyShoppingCardController::class, 'getProductByDirecroty']);
        Route::post('/company/shoppingcard-seo', [CompanyShoppingCardController::class, 'checkShoppingcardSeo']);
        Route::get('/company/changeShoppingWinnerStatus', [CompanyShoppingCardController::class, 'changeShoppingWinnerStatus']);

        //Events
        Route::get('/company/events', [CompanyEventsController::class, 'eventList'])->name('company.events')->middleware('ModuleAuth');
        Route::get('/company/add-events', [CompanyEventsController::class, 'companyaddevents'])->name('company.addevents')->middleware('ModuleAuth');
        Route::post('/company/add-events', [CompanyEventsController::class, 'companyaddeventssubmit'])->name('company.events.submit');
        Route::get('/company/events-edit/{id}', [CompanyEventsController::class, 'companyeventedit'])->name('company.events.edit');
        Route::post('/company/events-edit/{id}', [CompanyEventsController::class, 'companyeventupdate'])->name('company.events.update');
        Route::get('/company/events-delete/{id}', [CompanyEventsController::class, 'destroy'])->name('company.events.destroy');
        Route::post('/company/delete-event-gallery', [CompanyEventsController::class, 'deleteGalleryImage']);

        //Blog
        Route::get('/company/blog', [CompanyBlogController::class, 'blogList'])->name('company.blog')->middleware('ModuleAuth');
        Route::get('/company/add-blog', [CompanyBlogController::class, 'companyaddblog'])->name('company.addblog')->middleware('ModuleAuth');
        Route::post('/company/add-blog', [CompanyBlogController::class, 'companyaddblogsubmit'])->name('company.blog.submit');
        Route::get('/company/blog-edit/{id}', [CompanyBlogController::class, 'companyblogedit'])->name('company.blog.edit');
        Route::post('/company/blog-edit/{id}', [CompanyBlogController::class, 'companyblogupdate'])->name('company.blog.update');
        Route::get('/company/blog-delete/{id}', [CompanyBlogController::class, 'destroy'])->name('company.blog.destroy');
        Route::post('/company/blogseo', [CompanyBlogController::class, 'checkBlogSeo']);
        Route::get('/changeBlogStatus', [CompanyBlogController::class, 'changeStatus']);

        //Advertisement
        Route::get('/company/advertisement', [CompanyAdvertisementController::class, 'advertisementList'])->name('company.advertisement')->middleware('ModuleAuth');
        Route::post('/company/advertisement', [CompanyAdvertisementController::class, 'advertisementList'])->name('company.advertisement.filter')->middleware('ModuleAuth');

        Route::get('/company/add-advertisement', [CompanyAdvertisementController::class, 'companyaddadvertisement'])->name('company.addadvertisement')->middleware('ModuleAuth');
        Route::post('/company/add-advertisement', [CompanyAdvertisementController::class, 'companyaddadvertisementsubmit'])->name('company.advertisement.submit');
        Route::get('/company/advertisement-edit/{id}', [CompanyAdvertisementController::class, 'companyadvertisementedit'])->name('company.advertisement.edit');
        Route::post('/company/advertisement-edit/{id}', [CompanyAdvertisementController::class, 'companyadvertisementupdate'])->name('company.advertisement.update');
        Route::get('/company/advertisement-delete/{id}', [CompanyAdvertisementController::class, 'destroy'])->name('company.advertisement.destroy');
        Route::post('/company/advertisement/status', [CompanyAdvertisementController::class, 'updateCompetitionStatus'])->name('company.advertisement.status');
        Route::get('/company/competition-participants/{id}', [CompanyAdvertisementController::class, 'competitionParticipants'])->name('company.competition.participants');

        Route::get('/company/questionnaire', [CompanyAdvertisementController::class, 'vendorquestionnaire'])->name('vendor.questionnaire');
        Route::get('/company/advertisementquestionnaire', [CompanyAdvertisementController::class, 'advertisementVendorQuestionnaire'])->name('advertisement.company.questionnaire');
        Route::post('/advertisement/{id}/vendoranswers', [CompanyAdvertisementController::class, 'store'])->name('advertisement.vendor.answers.store');
        Route::get('/company/vendorprizes', [CompanyAdvertisementController::class, 'vendorprizes'])->name('vendor.prizes');


        /* Role managment */
        Route::get('/company/role', [App\Http\Controllers\Account\VRoleController::class, 'index'])->name('company.role.index')->middleware('ModuleAuth');
        Route::post('/company/role', [App\Http\Controllers\Account\VRoleController::class, 'index'])->name('company.role.indexsearch');
        Route::get('/company/role-create', [App\Http\Controllers\Account\VRoleController::class, 'create'])->name('company.role.create');
        Route::post('/company/role-submit', [App\Http\Controllers\Account\VRoleController::class, 'submit'])->name('company.role.submit');
        Route::get('/company/role-edit/{id}', [App\Http\Controllers\Account\VRoleController::class, 'edit'])->name('company.role.edit');
        Route::post('/company/role-edit/{id}', [App\Http\Controllers\Account\VRoleController::class, 'update'])->name('company.role.update');
        Route::get('/company/role-delete/{id}', [App\Http\Controllers\Account\VRoleController::class, 'destroy'])->name('company.role.destroy');
        Route::get('/company/changeRoleStatus', [App\Http\Controllers\Account\VRoleController::class, 'changeStatus']);

        /* Permission managment */
        Route::get('/company/permission', [App\Http\Controllers\Account\VendorController::class, 'index'])->name('company.permission.index');
        Route::post('/company/permission', [App\Http\Controllers\Account\VendorController::class, 'index'])->name('company.permission.indexsearch');
        Route::get('/company/permission-create', [App\Http\Controllers\Account\VendorController::class, 'create'])->name('company.permission.create');
        Route::post('/company/permission-submit', [App\Http\Controllers\Account\VendorController::class, 'submit'])->name('company.permission.submit');
        Route::get('/company/permission-edit/{id}', [App\Http\Controllers\Account\VendorController::class, 'edit'])->name('company.permission.edit');
        Route::post('/company/permission-edit/{id}', [App\Http\Controllers\Account\VendorController::class, 'update'])->name('company.permission.update');
        Route::get('/company/permission-delete/{id}', [App\Http\Controllers\Account\VendorController::class, 'destroy'])->name('company.permission.destroy');
        Route::get('/company/getModulePermissionByRoleId/{id}', [App\Http\Controllers\Account\VendorController::class, 'getModulePermissionByRoleId']);

        /* User Status */
        Route::get('/company/changeStatus', [App\Http\Controllers\Admin\UserController::class, 'changeStatus']);

        /* Order creation */
        Route::get('/company/orders', [CompanyOrderController::class, 'index'])->name('company.order.index')->middleware('ModuleAuth');
        Route::post('/company/orders', [CompanyOrderController::class, 'index'])->name('company.order.indexsearch');
        Route::get('/company/view/{id}', [CompanyOrderController::class, 'view'])->name('company.order.view');
        Route::get('/company/track/order/{id}', [CompanyOrderController::class, 'view'])->name('company.order.track');
        Route::post('/company/view/{id}', [CompanyOrderController::class, 'changeOrderStatus']);
        Route::post('/company/sendBulkMessage', [CompanyOrderController::class, 'sendBulkMessage']);

        /*Company directory own Tags creation */
        Route::get('/company/directory-own-tags', [CompanyDirectoryOwnTagsControllers::class, 'index'])->name('company.directoryowntags.index')->middleware('ModuleAuth');
        Route::post('/company/directory-own-tags', [CompanyDirectoryOwnTagsControllers::class, 'index'])->name('company.directoryowntags.indexsearch');
        Route::get('/company/add-directory-own-tags', [CompanyDirectoryOwnTagsControllers::class, 'create'])->name('company.directoryowntags.create')->middleware('ModuleAuth');
        Route::post('/company/add-directory-own-tags', [CompanyDirectoryOwnTagsControllers::class, 'submit'])->name('company.directoryowntags.submit');
        Route::get('/company/directory-own-tags-edit/{id}', [CompanyDirectoryOwnTagsControllers::class, 'edit'])->name('company.directoryowntags.edit');
        Route::post('/company/directory-own-tags-edit/{id}', [CompanyDirectoryOwnTagsControllers::class, 'update'])->name('company.directoryowntags.update');
        Route::get('/company/directory-own-tags-delete/{id}', [CompanyDirectoryOwnTagsControllers::class, 'destroy'])->name('company.directoryowntags.destroy');
        Route::get('/company/changedirectoryownTagStatus', [CompanyDirectoryOwnTagsControllers::class, 'changedirectoryownTagStatus']);

        /* Company directory own Tags Category creation */
        Route::get('/company/directory-own-tags-category', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'index'])->name('company.directoryowntagscategory.index');
        Route::post('/company/directory-own-tags-category', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'index'])->name('company.directoryowntagscategory.indexsearch');
        Route::get('/company/add-directory-own-tags-category', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'create'])->name('company.directoryowntagscategory.create');
        Route::post('/company/add-directory-own-tags-category', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'submit'])->name('company.directoryowntagscategory.submit');
        Route::get('/company/directory-own-tags-category-edit/{id}', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'edit'])->name('company.directoryowntagscategory.edit');
        Route::post('/company/directory-own-tags-category-edit/{id}', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'update'])->name('company.directoryowntagscategory.update');
        Route::get('/company/directory-own-tags-category-delete/{id}', [App\Http\Controllers\Account\CompanyDirectoryOwnTagsCategoryController::class, 'destroy'])->name('company.directoryowntagscategory.destroy');

        /* Discount creation */
        //Voucher Creation
        Route::get('/company/discount/voucher', [CompanyDiscountController::class, 'index'])->name('company.discount.voucher.index')->middleware('ModuleAuth');
        Route::post('/company/discount/voucher', [CompanyDiscountController::class, 'index'])->name('company.discount.voucher.indexsearch');
        Route::get('/company/discount/voucher/add-voucher', [CompanyDiscountController::class, 'create'])->name('company.discount.voucher.create')->middleware('ModuleAuth');
        Route::post('/company/discount/voucher/add-voucher', [CompanyDiscountController::class, 'submitVoucher'])->name('company.discount.voucher.submitVoucher');
        Route::get('/company/discount/voucher/voucher-edit/{id}', [CompanyDiscountController::class, 'editVoucher'])->name('company.discount.voucher.editVoucher');
        Route::post('/company/discount/voucher/voucher-edit/{id}', [CompanyDiscountController::class, 'updateVoucher'])->name('company.discount.voucher.updateVoucher');
        Route::get('/company/discount/voucher/voucher-delete/{id}', [CompanyDiscountController::class, 'destroyVoucher'])->name('company.discount.voucher.destroyVoucher');

        //Gift Voucher Creation
        Route::get('/company/discount/giftvoucher', [CompanyDiscountController::class, 'indexGiftVoucher'])->name('company.discount.giftvoucher.indexGiftVoucher')->middleware('ModuleAuth');
        Route::post('/company/discount/giftvoucher', [CompanyDiscountController::class, 'indexGiftVoucher'])->name('company.discount.giftvoucher.indexsearch');
        Route::get('/company/discount/giftvoucher/add-giftvoucher', [CompanyDiscountController::class, 'createGiftVoucher'])->name('company.discount.giftvoucher.createGiftVoucher')->middleware('ModuleAuth');
        Route::post('/company/discount/giftvoucher/add-giftvoucher', [CompanyDiscountController::class, 'submitGiftVoucher'])->name('company.discount.giftvoucher.submitGiftVoucher');
        Route::get('/company/discount/giftvoucher/giftvoucher-edit/{id}', [CompanyDiscountController::class, 'editGiftVoucher'])->name('company.discount.giftvoucher.editGiftVoucher');
        Route::post('/company/discount/giftvoucher/giftvoucher-edit/{id}', [CompanyDiscountController::class, 'updateGiftVoucher'])->name('company.discount.giftvoucher.updateGiftVoucher');
        Route::get('/company/discount/giftvoucher/giftvoucher-delete/{id}', [CompanyDiscountController::class, 'destroyGiftVoucher'])->name('company.discount.giftvoucher.destroyGiftVoucher');

        //Free Products Discount
        Route::get('/company/discount/freeproductsdiscount', [CompanyDiscountController::class, 'indexProductDiscount'])->name('company.discount.freeproductsdiscount.indexProductDiscount')->middleware('ModuleAuth');
        Route::post('/company/discount/freeproductsdiscount', [CompanyDiscountController::class, 'indexProductDiscount'])->name('company.discount.freeproductsdiscount.indexsearch');
        Route::get('/company/discount/freeproductsdiscount/add-productDiscount', [CompanyDiscountController::class, 'createProductDiscount'])->name('company.discount.freeproductsdiscount.createProductDiscount')->middleware('ModuleAuth');
        Route::post('/company/discount/freeproductsdiscount/add-productDiscount', [CompanyDiscountController::class, 'submitProductDiscount'])->name('company.discount.freeproductsdiscount.submitProductDiscount');
        Route::get('/company/discount/freeproductsdiscount/productdiscount-edit/{id}', [CompanyDiscountController::class, 'editProductDiscount'])->name('company.discount.freeproductsdiscount.editProductDiscount');
        Route::post('/company/discount/freeproductsdiscount/productdiscount-edit/{id}', [CompanyDiscountController::class, 'updateProductDiscount'])->name('company.discount.freeproductsdiscount.updateProductDiscount');
        Route::get('/company/discount/freeproductsdiscount/productdiscount-delete/{id}', [CompanyDiscountController::class, 'destroyProductDiscount'])->name('company.discount.freeproductsdiscount.destroyProductDiscount');
        Route::post('/company/get-directory-product', [CompanyDiscountController::class, 'getProduct']);

        /* offer creation */
        Route::get('/company/product/offer', [CompanyOfferControllers::class, 'index'])->name('company.offer.index')->middleware('ModuleAuth');
        Route::post('/company/product/offer', [CompanyOfferControllers::class, 'index'])->name('company.offer.indexsearch');
        Route::get('/company/product/add-offer', [CompanyOfferControllers::class, 'create'])->name('company.offer.create')->middleware('ModuleAuth');
        Route::post('/company/product/add-offer', [CompanyOfferControllers::class, 'submit'])->name('company.offer.submit');
        Route::get('/company/product/offer-edit/{id}', [CompanyOfferControllers::class, 'edit'])->name('company.offer.edit');
        Route::post('/company/product/offer-edit/{id}', [CompanyOfferControllers::class, 'update'])->name('company.offer.update');
        Route::get('/company/product/offer-delete/{id}', [CompanyOfferControllers::class, 'destroy'])->name('company.offer.destroy');
        Route::post('/company/product/get-product-by-directory', [CompanyOfferControllers::class, 'getProduct']);
        Route::post('/company/product/delete-offergallery-img', [CompanyOfferControllers::class, 'deleteGalleryImageDelete']);
        Route::post('/company/offerseo', [CompanyOfferControllers::class, 'checkOfferSeo']);

        Route::post('/get-offer-subprocat-by-productcatid', [CompanyOfferControllers::class, 'getOfferProductsubCategory']);

        /* appointment booking creation */
        Route::get('/company/booking', [CompanyBookingControllers::class, 'index'])->name('company.booking.index')->middleware('ModuleAuth');
        Route::post('/company/booking', [CompanyBookingControllers::class, 'index'])->name('company.booking.indexsearch');
        Route::get('/company/booking-edit/{id}', [CompanyBookingControllers::class, 'edit'])->name('company.booking.edit');
        Route::post('/company/booking-edit/{id}', [CompanyBookingControllers::class, 'update'])->name('company.booking.update');
        Route::get('/company/booking-delete/{id}', [CompanyBookingControllers::class, 'destroy'])->name('company.booking.destroy');
        Route::get('/company/request/newticket', [CompanyBookingControllers::class, 'requestNewticket'])->name('company.request.newticket');
        Route::post('/company/request/newticket', [CompanyBookingControllers::class, 'submitNewticket'])->name('company.submitNewticket');
        Route::get('/company/accept/{id}/{uid}/{tid}', [CompanyBookingControllers::class, 'accept'])->name('accept');
        Route::get('/company/notifications', [CompanyBookingControllers::class, 'notifications'])->name('notifications');
        Route::get('/changeVisibilityDateTimeStatus', [CompanyBookingControllers::class, 'changeVisibilityDateTimeStatus']);

        Route::get('/get-replied-data', [CompanyBookingControllers::class, 'getRepliedData']);

        Route::get('/delete-selected-booking', [CompanyBookingControllers::class, 'deleteBookingChecked']);/* delete multiple records by selection checkbox */

        /* mail mailchimp creation */
        Route::get('/company/mail', [MailVendorController::class, 'index'])->name('company.mail.index');
        Route::post('/company/mail', [MailVendorController::class, 'index'])->name('company.mail.indexsearch');
        Route::get('/company/add-mail', [MailVendorController::class, 'create'])->name('company.mail.create');
        Route::post('/company/add-mail', [MailVendorController::class, 'submit'])->name('company.mail.submit');
        Route::get('/company/view-mail/{id}', [MailVendorController::class, 'view'])->name('company.mail.view');
        /* Template mail creation */
        Route::get('/company/add-mail-template', [MailtemplateVendorController::class, 'create'])->name('company.mailtemplate.create');
        Route::post('/company/add-mail-template', [MailtemplateVendorController::class, 'submit'])->name('company.mailtemplate.submit');
        Route::post('/company/get-maildata-by-templateId', [MailVendorController::class, 'getMailByTemplateId']);
    });

    /* Account section */

    Route::get('/affiliate/register',[AffiliateController::class, 'create'])->name('affiliate.register');
    Route::post('/affiliate/register',[AffiliateController::class, 'store'])->name('affiliate.register.store');
    Route::get('/account/affiliate/dashboard', [AffiliateController::class, 'dashboard'])->name('affiliate.dashboard');
    Route::get('/account/affiliate/product-suggestions', [AffiliateController::class, 'productSuggestions'])->name('affiliate.products.suggestions');
    Route::post('/account/affiliate/links', [AffiliateController::class, 'storeLink'])->name('affiliate.links.store');

    Route::get('/account', [AccountController::class, 'profile'])->name('account');
    Route::post('/profile/upload', [AccountController::class, 'uploadProfilePic'])->name('profile.upload');

    Route::post('/account/update-profile', [AccountController::class, 'submitProfile'])->name('account.submitProfile');
    Route::get('/account/reset-password', [AccountController::class, 'reset_password'])->name('ForgetPassword');
    Route::post('/account/reset-password', [AccountController::class, 'submitPassword'])->name('account.submitPassword');

    Route::get('/account/questionnaire', [AccountController::class, 'questionnaire'])->name('user.questionnaire');
    Route::get('/account/userprizes', [AccountController::class, 'userprizes'])->name('user.prizes');
    Route::get('/account/shopping-valid-offer', [AccountController::class, 'shoppingValidOffer'])->name('account.shopping.valid.offer');
    Route::post('/account/send-offer-reply', [AccountController::class, 'sendOfferReply'])->name('account.offer.reply');
    Route::get('/account/changeWinnerStatus', [AccountController::class, 'changeWinnerStatus'])->name('admin.changeWinnerStatus');
    Route::get('/account/changeShoppingWinnerStatus', [AccountController::class, 'changeShoppingWinnerStatus'])->name('admin.changeShoppingWinnerStatus');

    Route::get('/account/advertisementquestionnaire', [AccountController::class, 'advertisementQuestionnaire'])->name('advertisement.user.questionnaire');
    Route::post('/advertisement/{id}/answers', [AccountController::class, 'store'])->name('advertisement.answers.store');
    Route::post('/account/questionnaire/reply', [AccountController::class, 'questionnaireReply'])->name('account.questionnaire.reply');

    Route::get('/account/address', [AddressController::class, 'index'])->name('account.address.list');
    Route::get('/account/add-address', [AddressController::class, 'addAddress'])->name('address.add');
    Route::post('/account/add-address', [AddressController::class, 'storeAddress'])->name('address.storeAddress');
    Route::get('/account/edit-address/{id}', [AddressController::class, 'editAddress'])->name('address.edit');
    Route::post('/account/edit-address', [AddressController::class, 'updateAddress'])->name('address.updateAddress');
    Route::get('/account/delete-address/{id}', [AddressController::class, 'deleteAddress'])->name('address.deleteAddress');
    Route::get('/account/order', [OrderController::class, 'index'])->name('account.order.list');
    Route::get('/account/order-detail/{id}', [OrderController::class, 'orderDeatil'])->name('account.order.detail');
    Route::get('account/wishlist-product', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('account/wishlist-directory', [WishlistController::class, 'directoryWislishDetails'])->name('directory.wishlist');
    Route::get('account/product-wislist-delete/{id}', [WishlistController::class, 'productWishlistDelete']);

    Route::get('account/cart-savelatter-delete', [WishlistController::class, 'cartSavelatterDelete'])->name('remove.item.savelatter');
    Route::get('account/comparison-delete', [WishlistController::class, 'comparisonDelete'])->name('remove.item.comparison');
    Route::get('account/move-to-cart', [WishlistController::class, 'moveToCart'])->name('move.item.cart');

    Route::get('account/directory-wislist-delete/{id}', [WishlistController::class, 'directoryWishlistDelete']);
    Route::post('/submit-comment', [Blog::class, 'submitComment'])->name('submit-Comment');
    Route::post('/submit-eventcomment', [Event::class, 'submitEventComment'])->name('submit-EventComment');
    //Route::get('thank-you', [ThankuController::class, 'index'])->name('thankyou');
    Route::get('thank-you', [CheckoutController::class, 'thankyou'])->name('thankyou');
    Route::get('paypal-cancel', [ThankuController::class, 'paypalcancel'])->name('paypal.cancel');
    Route::get('stripe', [StripeController::class, 'stripe']);
    Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');
});


Route::group(['middleware' => 'Language'], function () {
    Route::get('/change-language/{lang}', [Pages::class, 'changeLang']);
});
Route::get('/change-currency/{code}', [Pages::class, 'changeCurrency']);
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('order-place', [CheckoutController::class, 'placeOrder'])->name('order.place');
