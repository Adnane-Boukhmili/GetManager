<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pricingController;
use App\Http\Controllers\managingController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\ZoomController;


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

Route::get('/', function () {
    return view('welcome');
});
//----------------------------------DASHBOARD--------------------------------------

Route::get('/pricing',[pricingController::class,'pricing']);
Route::post('/set_pricing',[pricingController::class,'set_pricing']);
Route::get('/managing',[managingController::class,'managing'])->name('managing');
Route::get('/invoices',[invoiceController::class,'invoices'])->name('invoices');
Route::get('/stripe_invoices',[invoiceController::class,'stripe_invoices'])->name('stripe.invoices');
Route::get('/paypal_invoices',[invoiceController::class,'paypal_invoices'])->name('paypal.invoices');




//----------------------------------ZOOM-------------------------------------------
Route::get('/live',[pricingController::class,'live'])->name('live');
Route::get('/zoomlive', [ZoomController::class,'zoomlive'])->name('zoomlive');
Route::get('/auth/zoom/callback', [ZoomController::class, 'handleZoomCallback']);
Route::match(['get', 'post'], '/auth/zoom', [ZoomController::class, 'redirectToZoom']);
Route::post('/createMeeting', [ZoomController::class, 'createMeeting']);

Route::get('/meetings/start/{meetingId}', [ZoomController::class, 'startMeeting'])
    ->name('start.meeting');
Route::get('/meetings/iframe/{meetingId}', function ($meetingId) {
    return view('meeting-iframe', ['meetingId' => $meetingId]);
})->name('meeting.iframe');
Route::delete('/meetings/{meetingId}', [ZoomController::class, 'deleteMeeting'])
    ->name('meetings.delete');

//----------------------------------MANAGING-------------------------------------------

Route::post('/create_employer',[managingController::class,'create_employer']);
Route::delete('/delete_employee/{id}',[managingController::class,'delete_employee']);
Route::get('/edit_employee/{id}',[managingController::class,'edit_employee']);
Route::put('/update_employee/{id}',[managingController::class,'update_employee']);

//----------------------------------STRIPE-------------------------------------------

Route::get('stripe/payment/{nbremp}', [pricingController::class,'stripeCheckout'])->name('stripe.checkout');
Route::get('stripe/checkout/success', [pricingController::class,'stripeCheckoutSuccess'])->name('stripe.checkout.success');

Route::get('stripe/upgrade', [pricingController::class,'stripeCheckoutUpgrade'])->name('stripe.checkout.upgrade');
Route::get('stripe/upgrade/success', [pricingController::class,'stripeCheckoutUpgradeSuccess'])->name('stripe.checkout.upgrade.success');

Route::get('stripe/addemp/success', [pricingController::class,'stripeCheckoutAddSuccess'])->name('stripe.checkout.add.success');
Route::get('stripe/addemp/{nbremp}', [pricingController::class,'stripeCheckoutAdd'])->name('stripe.checkout.add');


//----------------------------------PAYPAL-------------------------------------------

Route::get('paypal/payment/{nbremp}', [pricingController::class,'paypalPayment'])->name('paypal');
Route::get('paypal/success', [pricingController::class,'paypalPaymentSuccess'])->name('paypal_success');


Route::get('paypal/upgrade', [pricingController::class,'paypalUpgradePayment'])->name('paypalUpgrade');
Route::get('paypal/upgrade/success', [pricingController::class,'paypalPaymentUpgradeSuccess'])->name('paypal_upgrade_success');

Route::get('paypal/addemp/success', [pricingController::class,'paypalAddPaymentSuccess'])->name('paypal_add_success');
Route::get('paypal/addemp/{nbremp}', [pricingController::class,'paypalAddPayment'])->name('paypalAdd');





Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
