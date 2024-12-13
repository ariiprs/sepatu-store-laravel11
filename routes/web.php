<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FrontController::class, 'index'])->name('front.index');

    Route::get('/all-category', [FrontController::class, 'allCategory'])->name('front.all_category');

    /* ini untuk search sepatu */
    Route::get('/search', [FrontController::class, 'search'])->name('front.search');

    Route::get('/contact', [FrontController::class, 'contact'])->name('front.contact');

    Route::get('/search-category', [FrontController::class, 'searchCategory'])->name('front.search_category');

    Route::get('/browse/{category:slug}', [FrontController::class, 'category'])->name('front.category');

    Route::get('/details/{shoe:slug}', [FrontController::class, 'details'])->name('front.details');

    /* ini dimasukan ke OrderController karna data2 yg diambil dari OrderController */
    Route::get('/check-booking', [OrderController::class, 'checkBooking'])->name('front.check_booking');

    Route::post('/check-booking/details', [OrderController::class, 'checkBookingDetails'])->name('front.check_booking_details');



    Route::post('/order/begin/{shoe:slug}', [OrderController::class, 'saveOrder'])->name('front.save_order');

    Route::get('/order/booking', [OrderController::class, 'booking'])->name('front.booking');


    Route::get('/order/booking/customer-data', [OrderController::class, 'customerData'])->name('front.customer_data');

    Route::post('/order/booking/customer-data/save', [OrderController::class, 'saveCustomerData'])->name('front.save_customer_data');

    Route::get('/order/payment', [OrderController::class, 'payment'])->name('front.payment');
    Route::post('/order/payment/confirm', [OrderController::class, 'paymentConfirm'])->name('front.payment_confirm');

    Route::get('/order/finished/{productTransaction:id}', [OrderController::class, 'orderFinished'])->name('front.order_finished');
});

require __DIR__.'/auth.php';
