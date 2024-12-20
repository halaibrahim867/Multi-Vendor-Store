<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrdersControllers;
use App\Http\Controllers\Front\PaymentsController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialeController;
use App\Http\Controllers\StripeWebhooksController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

Route::group([
    'prefix'=> LaravelLocalization::setLocale(),
], function () {

    Route::get('/', [HomeController::class,'index'])
        ->name('home');

    Route::get('/products',[ProductController::class,'index'])
        ->name('products.index');

    Route::get('/products/{product:slug}',[ProductController::class,'show'])
        ->name('products.show');

    Route::resource('cart',CartController::class);

    Route::get('checkout',[CheckoutController::class,'create'])->name('checkout');
    Route::post('checkout',[CheckoutController::class,'store']);

    Route::get('auth/user/2fa',[TwoFactorAuthenticationController::class,'index'])
        ->name('front.2fa');

    Route::post('currency',[CurrencyConverterController::class,'store'])
        ->name('currency.store');


    Route::get('/auth/{provider}',[SocialLoginController::class,'redirect'])
            ->name('auth.socilaite.redirect');

    Route::get('/auth/{provider}/callback',[SocialLoginController::class,'callback'])
        ->name('auth.socilaite.callback');


    Route::get('auth/{provider}/user',[SocialeController::class,'index']);

    Route::get('orders/{order}/pay',[PaymentsController::class,'create'])
            ->name('orders.payments.create');

    Route::post('orders/{order}/stripe/payment-intent',[PaymentsController::class,'createStripePaymentIntent'])
            ->name('stripe.paymentIntent.create');

    Route::get('orders/{order}/pay/stripe/callback',[PaymentsController::class,'confirm'])
        ->name('stripe.return');


    Route::any('stripe/webhook',[StripeWebhooksController::class,'handle']);

    Route::get('/orders/{order}',[OrdersControllers::class,'show'])
            ->name('orders.show');
});

/*
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
//require __DIR__.'/auth.php';

require __DIR__.'/dashboard.php';
