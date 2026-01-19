<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ToyyibPayController;
/*
|--------------------------------------------------------------------------
| DEFAULT REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/forgot', [LoginController::class, 'showForgotPasswordForm'])
    ->name('forgot');

Route::post('/forgot', [LoginController::class, 'sendResetLink'])
    ->name('forgot.submit');

Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [LoginController::class, 'updatePassword'])
    ->name('password.update');

Route::get('/verify-email/{token}', [LoginController::class, 'verifyEmail'])
    ->name('verify.email');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Require Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/home', [LoginController::class, 'home'])->name('home');

    // 🔐 Change Password page (used in admin & customer profile blades)
    Route::get('/change-password', fn() => view('changePassword'))->name('change.password');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (OWNER ONLY)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'owner'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    /*
    |--------------------------------------------------------------------------
    | STAFF MANAGEMENT (Owner only)
    |--------------------------------------------------------------------------
    */
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', function($id){return \App\Models\User::findOrFail($id);});
    Route::delete('/staff/delete/{id}', [StaffController::class, 'delete'])->name('staff.delete');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::post('/staff/{id}/update', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/{id}/deactivate', [StaffController::class, 'deactivate'])->name('staff.deactivate');
    Route::get('/staff/{id}/activate', [StaffController::class, 'activate'])->name('staff.activate');

  /*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

// Main services page (Category manager)
Route::get('/services', [AdminController::class, 'services'])
    ->name('admin.services');

// Create new category
Route::post('/services/category/store', [AdminController::class, 'storeCategory'])
    ->name('admin.services.category.store');


// ---------------- Add / Create Service ----------------
Route::get('/services/add', [AdminController::class, 'addService'])
    ->name('admin.services.add');

Route::post('/services/store', [AdminController::class, 'storeService'])
    ->name('admin.services.store');


// ---------------- Edit / Update Service ----------------
// IMPORTANT: MUST BE ABOVE /services/{category}
Route::get('/services/edit/{id}', [AdminController::class, 'editService'])
    ->name('admin.services.edit');

Route::post('/services/update/{id}', [AdminController::class, 'updateService'])
    ->name('admin.services.update');


// ---------------- Delete Service ----------------
Route::delete('/services/delete/{id}', [AdminController::class, 'deleteService'])
    ->name('admin.services.delete');


// ---------------- Service Listing by Category ----------------
// MUST BE LAST because it’s a wildcard
Route::get('/services/{category}', [AdminController::class, 'serviceListByCategory'])
    ->name('admin.services.byCategory');


// ---------------- Category Update / Delete ----------------
Route::post('/services/category/update/{id}', [AdminController::class, 'updateServiceCategory'])
    ->name('admin.services.category.update');

Route::delete('/services/category/delete/{id}', [AdminController::class, 'deleteServiceCategory'])
    ->name('admin.services.category.delete');

    /*
    |--------------------------------------------------------------------------
    | BOOKINGS
    |--------------------------------------------------------------------------
    */
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::put('/bookings/complete/{id}', [AdminController::class, 'markCompleted'])->name('admin.markCompleted');
    Route::get('/queue/pending', [AdminController::class, 'getPendingQueue'])->name('admin.queue.pending');

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */
    Route::get('/products/categories', [AdminController::class, 'productCategories'])->name('admin.products.categories');
    Route::get('/products/category/{category}', [AdminController::class, 'productsByCategory'])->name('admin.products.byCategory');
    Route::post('/products/category/store', [AdminController::class, 'storeProductCategory'])->name('admin.products.category.store');

    Route::get('/products/add', [AdminController::class, 'addProduct'])->name('admin.products.add');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');

    Route::get('/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/update/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');

    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // 🔧 Product Category edit / update / delete (used in admin.productCategory.blade.php)
    Route::get('/product-categories/{category}/edit', [AdminController::class, 'editProductCategory'])->name('admin.products.category.edit');
    Route::post('/product-categories/{category}/update', [AdminController::class, 'updateProductCategory'])->name('admin.products.category.update');
    Route::get('/product-categories/{category}/delete', [AdminController::class, 'deleteProductCategory'])->name('admin.products.category.delete');

   // PRODUCT PURCHASES (OWNER)
Route::get('/product-purchases', [AdminController::class, 'productPurchases'])
    ->name('admin.product.purchases');

Route::get('/product-purchases/{id}', [AdminController::class, 'productPurchaseDetail'])
    ->name('admin.product.purchase.detail');

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
   Route::get('/payments', [PaymentController::class, 'payments'])->name('admin.payments');
   Route::get('/admin/payment/approve/{id}', [PaymentController::class, 'approvePayment'])->name('payment.approve');
   Route::get('/admin/payment/reject/{id}', [PaymentController::class, 'rejectPayment'])->name('payment.reject');
});

/*
|--------------------------------------------------------------------------
| STAFF ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('staff')
    ->middleware(['auth']) // if you later add StaffMiddleware, you can put it here
    ->group(function () {

    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');

    // Bookings (if implemented in StaffController)
    Route::get('/bookings', [StaffController::class, 'bookings'])->name('staff.bookings');
    Route::post('/bookings/{id}/{status}', [StaffController::class, 'updateBookingStatus'])->name('staff.bookings.updateStatus');

    // Payments (if implemented)
    Route::get('/payments', [StaffController::class, 'payments'])->name('staff.payments');
    Route::post('/payments/{id}/{status}', [StaffController::class, 'updatePaymentStatus'])->name('staff.payments.updateStatus');

    // Products (if implemented)
    Route::get('/products', [StaffController::class, 'products'])->name('staff.products');

    // Profile
    Route::get('/profile', [StaffController::class, 'profile'])->name('staff.profile');
    Route::get('/profile/edit', [StaffController::class, 'editProfile'])->name('staff.profile.edit');
    Route::post('/profile/update', [StaffController::class, 'updateProfile'])->name('staff.profile.update');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES (Customer only)
|--------------------------------------------------------------------------
*/
Route::prefix('customer')
    ->middleware(['auth', 'customer'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::get('/profile/edit', [CustomerController::class, 'editProfile'])->name('customer.profile.edit');
    Route::post('/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Services
    Route::get('/services', [CustomerController::class, 'viewServices'])->name('customer.services');
    Route::get('/services/{id}', [CustomerController::class, 'servicesByCategory'])->name('customer.services.byCategory');

    // Bookings
    Route::get('/book-services/{id}', [CustomerController::class, 'bookServices'])->name('customer.bookServices');
    Route::post('/booking/store', [CustomerController::class, 'storeBooking'])->name('customer.booking.store');
    Route::get('/bookings', [CustomerController::class, 'showBookings'])->name('customer.bookings');
    Route::put('/booking/cancel/{id}', [CustomerController::class, 'cancelBooking'])->name('customer.cancelBooking');

    // 🔁 Available slots API (used in customer.bookServices Blade)
    Route::get('/booking/available-times', [CustomerController::class, 'getAvailableTimes'])->name('booking.availableTimes');

    // 💳 AJAX payment actions (used in bookings page JS)
    Route::post('/customer/payment/confirm/{bookingId}', [CustomerController::class, 'confirmPayment'])->name('customer.payment.confirm');
    Route::post('/booking/{booking}/pay', [CustomerController::class, 'markPaid'])->name('booking.markPaid');
    
    // TOYYIB PAY
Route::post('/payment/toyyibpay/create/{bookingId}', [ToyyibPayController::class, 'createBill'])->name('toyyibpay.create');
Route::post('/payment/toyyibpay/callback', [ToyyibPayController::class, 'callback'])->name('toyyibpay.callback');
Route::get('/payment/toyyibpay/return', [ToyyibPayController::class, 'returnUrl'])->name('toyyibpay.return');

    // Products
    Route::get('/products', [CustomerController::class, 'productCategories'])->name('customer.products.categories');
    Route::get('/products/category/{category}', [CustomerController::class, 'productsByCategory'])->name('customer.products.byCategory');

    // Cart
    Route::post('/products/add-to-cart', [CustomerController::class, 'addToCart'])->name('customer.products.addToCart');
    Route::get('/cart', [CustomerController::class, 'showCart'])->name('customer.cart');
    Route::put('/cart/update/{id}', [CustomerController::class, 'updateCart'])->name('customer.cart.update');
    Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeCartItem'])->name('customer.cart.remove');

    // Checkout
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
    Route::post('/checkout/confirm', [CustomerController::class, 'checkoutConfirm'])->name('customer.checkout.confirm');
    Route::get('/checkout/review', [CustomerController::class, 'checkoutReview'])->name('customer.checkout.review');

    // Purchases & Payments (links in layouts.customer)
    Route::get('/mypurchase', [CustomerController::class, 'myPurchases'])->name('customer.mypurchase');
    Route::get('/mypayments', [PaymentController::class, 'myPayments'])->name('customer.mypayments');
    Route::get('/payment/form/{bookingId}', [PaymentController::class, 'showPaymentForm'])->name('customer.payment.form');

    // Stripe (used in bookings.blade via route('stripe.booking.form'))
    Route::get('/stripe/booking/{bookingId}', [StripeController::class, 'index'])->name('stripe.booking.form');
    Route::post('/stripe/charge', [StripeController::class, 'charge'])->name('stripe.charge');

  /*
|--------------------------------------------------------------------------
| SHOP (Buy Now + Light Delivery)
|--------------------------------------------------------------------------
*/
Route::get('/shop', [PurchaseController::class, 'productList'])
    ->name('customer.shop');

Route::get('/shop/buy-now/{id}', [PurchaseController::class, 'buyNow'])
    ->name('customer.shop.buy');

Route::post('/shop/confirm', [PurchaseController::class, 'confirmPurchase'])
    ->name('customer.shop.confirm');

// PRODUCT PAYMENT
Route::get('/purchase/stripe/{purchaseId}', function ($purchaseId) {
    return app(\App\Http\Controllers\StripeController::class)
        ->index(null, $purchaseId);
})->name('stripe.purchase.form');

Route::post('/purchase/toyyibpay/{purchaseId}', [ToyyibPayController::class, 'createPurchaseBill'])
    ->name('toyyibpay.purchase.create');


});

/*
|--------------------------------------------------------------------------
| TEST + FALLBACK
|--------------------------------------------------------------------------
*/
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Connected to: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "DB error: " . $e->getMessage();
    }
});

Route::fallback(function () {
    return "<h2 style='text-align:center; color:red;'>⚠️ PAGE NOT FOUND (404)</h2>";
});
