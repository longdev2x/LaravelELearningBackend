<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaypalPaymentController;

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

Route::get('/flutter', function() {
    return view('test');
});

// Route::group(['prefix' => 'payment-mobile'], function () {
//     Route::get('/', 'PaymentController@payment')->name('payment-mobile');
//     Route::get('set-payment-method/{name}', 'PaymentController@set_payment_method')->name('set-payment-method');
// });
Route::group([
    'prefix' => 'payment-mobile',
], function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/', 'payment')->name('payment-mobile');
        Route::get('set-payment-method/{name}', 'set-payment-method')->name('set-payment-method');
    });
});
Route::post('pay-paypal', [PaypalPaymentController::class, 'payWithpaypal'])->name('pay-paypal');
Route::get('paypal-status', [PaypalPaymentController::class, 'getPaymentStatus'])->name('paypal-status');
Route::get('payment-success', [PaymentController::class, 'success'])->name('payment-success');
Route::get('payment-fail', [PaymentController::class, 'fail'])->name('payment-fail');
// Route::post('pay-paypal', 'PaypalPaymentController@payWithpaypal')->name('pay-paypal');
// Route::get('paypal-status', 'PaypalPaymentController@getPaymentStatus')->name('paypal-status');
// Route::get('payment-success', 'PaymentController@success')->name('payment-success');
// Route::get('payment-fail', 'PaymentController@fail')->name('payment-fail');