<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pricingController;
use App\Http\Controllers\managingController;


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

Route::get('/pricing',[pricingController::class,'pricing']);
Route::post('/set_pricing',[pricingController::class,'set_pricing']);
Route::get('/managing',[managingController::class,'managing'])->name('managing');
Route::get('/invoices',[managingController::class,'invoices'])->name('invoices');



Route::post('/create_employer',[managingController::class,'create_employer']);
Route::delete('/delete_employee/{id}',[managingController::class,'delete_employee']);
Route::get('/edit_employee/{id}',[managingController::class,'edit_employee']);
Route::put('/update_employee/{id}',[managingController::class,'update_employee']);

Route::get('payment/{nbremp}', [pricingController::class,'stripeCheckout'])->name('stripe.checkout');
Route::get('stripe/checkout/success', [pricingController::class,'stripeCheckoutSuccess'])->name('stripe.checkout.success');

Route::get('stripe/checkout/upgrade', [pricingController::class,'stripeCheckoutUpgrade'])->name('stripe.checkout.upgrade');
Route::get('stripe/checkout/upgrade/success', [pricingController::class,'stripeCheckoutUpgradeSuccess'])->name('stripe.checkout.upgrade.success');

Route::get('addemp/success', [pricingController::class,'stripeCheckoutAddSuccess'])->name('stripe.checkout.add.success');
Route::get('addemp/{nbremp}', [pricingController::class,'stripeCheckoutAdd'])->name('stripe.checkout.add');








Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
