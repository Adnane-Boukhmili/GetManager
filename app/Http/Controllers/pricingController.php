<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Employeecounthistory;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Stripe;
use Auth;

class pricingController extends Controller
{
    public function pricing(){
        $user =Auth::user();
        $e = $user->employeeCountHistory ? $user->employeeCountHistory->total_employee_count : 0;
        
        return view('pricing',compact('e','user'));
       
    }
    public function live(){  
        return view('live');
       
    }


    public function stripeCheckout($nbremp)
    {
        $user = Auth::user();
        $plan = Plan::find(2);
        $employeeCount = $nbremp;
        $totalprice = (float)$plan->each_employee_price * (int)$employeeCount;;
        
        
        $data = [
            'nbremp' => $employeeCount,
            'totalprice' => $totalprice,
            'plan' => $plan
        ];
        session(['myData'=>$data]);

        $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");
  
        $redirectUrl = route('stripe.checkout.success').'?session_id={CHECKOUT_SESSION_ID}';
        $response =  $stripe->checkout->sessions->create([
                'success_url' => $redirectUrl,
                'customer_email' => $user->email,
                'invoice_creation' => ['enabled' => true],
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data'  => [
                            'product_data' => [
                                'name' => 'Monthly plan',
                            ],
                            'unit_amount'  => 100 * $plan->each_employee_price,
                            'currency'     => 'USD',
                        ],
                        'quantity'    => $nbremp
                    ],
                ],
                'mode' => 'payment',
                
                'allow_promotion_codes' => true
                
            ]);
  
        return redirect($response['url']);
    }

    public function stripeCheckoutSuccess(Request $request)
    {
        
        $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");
        $user = Auth::user();
        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        info($session);

        $invoice = $stripe->invoices->retrieve(
            $session->invoice,
            []
          );


       
        $data = session('myData');
        $employeeCount = $data['nbremp'];
        $totalprice = $data['totalprice'];
        $plan = $data['plan'];

        $sale = new Sale();
        $sale->user_id = $user->id;
        $sale->plan_id = $plan->id;
        $sale->employee_count = $employeeCount;
        $sale->total_price = $totalprice;
        $sale->payment_status = 'Paid Successfuly';
        $sale->type = 'Plan Purchase';
        $sale->invoice = $invoice->invoice_pdf;
        $sale->save();

        $employeeCountHistory = Employeecounthistory::where('user_id', $user->id)->first();

        if ($employeeCountHistory) {

            $employeeCountHistory->total_employee_count += $employeeCount;
            $employeeCountHistory->save();

        } else {

            $employeeCountHistory = new Employeecounthistory();
            $employeeCountHistory->user_id = $user->id;
            $employeeCountHistory->total_employee_count = $employeeCount;
            $employeeCountHistory->save();
        }
        $addingdays = now()->addDays(30);
        $user->plan_id = 2;
        $user->subscription_end_date = $addingdays;
        $user->save();

  
        return redirect()->route('dashboard')->with('success', 'Payment successful.');
    }

// ----------------------------------------------------------------------------------------------

    public function stripeCheckoutUpgrade()
    {
        $user = Auth::user();
        $plan = Plan::find($user->plan_id);
        $employeeCount= $user->employeecounthistory->total_employee_count;

        $totalprice = $plan->each_employee_price * $employeeCount;
        
        $data = [
            'nbremp' => $employeeCount,
            'totalprice' => $totalprice,
            'plan' => $plan
        ];
        session(['myData2'=>$data]);

        $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");
  
        $redirectUrl = route('stripe.checkout.upgrade.success').'?session_id={CHECKOUT_SESSION_ID}';
        $response =  $stripe->checkout->sessions->create([
                'success_url' => $redirectUrl,
                'customer_email' => $user->email,
                'invoice_creation' => ['enabled' => true],
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data'  => [
                            'product_data' => [
                                'name' => 'Upgrading Monthly Plan',
                            ],
                            'unit_amount'  => 100 * $plan->each_employee_price,
                            'currency'     => 'USD',
                        ],
                        'quantity'    => $employeeCount
                    ],
                ],
                'mode' => 'payment',
                'allow_promotion_codes' => true
            ]);
  
        return redirect($response['url']);
    }

    public function stripeCheckoutUpgradeSuccess(Request $request)
    {
        $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");
        $user = Auth::user();
        $session = $stripe->checkout->sessions->retrieve($request->session_id);
        info($session);

        $invoice = $stripe->invoices->retrieve(
            $session->invoice,
            []
          );

        
        $data = session('myData2');
        $employeeCount = $data['nbremp'];
        $totalprice = $data['totalprice'];
        
        $currentSubscriptionEndDate = $user->subscription_end_date;
        if ($currentSubscriptionEndDate <= now()) {
            $newEndDate = now()->addDays(30);
        } else {
            $newEndDate = (new carbon($currentSubscriptionEndDate))->addDays(30);
        }

        $user->subscription_end_date = $newEndDate;
        $user->save();

        $sale = new Sale();
        $sale->user_id = $user->id;
        $sale->plan_id = $user->plan_id;
        $sale->employee_count = $employeeCount;
        $sale->total_price = $totalprice; 
        $sale->payment_status = 'Paid Successfuly';
        $sale->type = 'Plan Upgrade';
        $sale->invoice = $invoice->invoice_pdf;
        $sale->save();


  
        return redirect()->route('dashboard')->with('success', 'Payment successful.');
    }


// ----------------------------------------------------------------------------------------------


public function stripeCheckoutAdd($nbremp)
{
    $user = Auth::user();
    $plan = Plan::find(2);
    
    $additionalEmployees = $nbremp - $user->employeeCountHistory->total_employee_count;
    $dailyRate = $user->plan->each_employee_price / 30;
    $currentExpirationDate = $user->subscription_end_date;
    $remainingDays = Carbon::parse($currentExpirationDate)->diffInDays(Carbon::now());
    $additionalPrice=$dailyRate * $remainingDays;
    $additionalCost = $dailyRate * $remainingDays * $additionalEmployees;
    
    
    $data = [
        'nbremp' => $additionalEmployees,
        'totalprice' => $additionalCost,
        'plan' => $plan
    ];
    session(['myData3'=>$data]);

    $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");

    $redirectUrl = route('stripe.checkout.add.success');
    $redirectUrl .= '?session_id={CHECKOUT_SESSION_ID}';
    $response =  $stripe->checkout->sessions->create([
            'success_url' => $redirectUrl,
            'customer_email' => $user->email,
            'invoice_creation' => ['enabled' => true],
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data'  => [
                        'product_data' => [
                            'name' => 'Adding Employees To The Plan',
                        ],
                        'unit_amount'  => round(100 * $additionalPrice),
                        'currency'     => 'USD',
                    ],
                    'quantity'    => $additionalEmployees
                ],
            ],
            'mode' => 'payment',
            'allow_promotion_codes' => true
        ]);

    return redirect($response['url']);
}

public function stripeCheckoutAddSuccess(Request $request)
{
    $stripe = new \Stripe\StripeClient("sk_test_51O9kwwJX2IHTqWpZq5k6o1ORESbIcIiZBWd5vhj1mlnGAcDv0Xb2J0rfFLcsquTfT47yf9YqMiT3vz5JMMynVcak001G7gyCQ0");
    $user = Auth::user();
    $session = $stripe->checkout->sessions->retrieve($request->session_id);
    info($session);

    $invoice = $stripe->invoices->retrieve(
        $session->invoice,
        []
      );

    
    $data = session('myData3');
    $employeeCount = $data['nbremp'];
    $totalprice = $data['totalprice'];

    $sale = new Sale();
    $sale->user_id = $user->id;
    $sale->plan_id = $user->plan_id;
    $sale->employee_count = $employeeCount;
    $sale->total_price = $totalprice;
    $sale->payment_status = 'Paid Successfuly';
    $sale->type = 'Employee Addition';
    $sale->invoice = $invoice->invoice_pdf;
    $sale->save();

    $employeeCountHistory = $user->employeeCountHistory;
    $employeeCountHistory->total_employee_count += $employeeCount;
    $employeeCountHistory->save();
     



    return redirect()->route('dashboard')->with('success', 'Payment successful.');
}

// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// ----------------------------------------PAYPAL------------------------------------------------
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------

public function paypalPayment($nbremp)
{
    
    $user = Auth::user();
    $plan = Plan::find(2);
    $employeeCount = $nbremp;
    $totalprice = (float)$plan->each_employee_price * (int)$employeeCount;;
    
    
    $data = [
        'nbremp' => $employeeCount,
        'totalprice' => $totalprice,
        'plan' => $plan
    ];
    session(['myDataa'=>$data]);

    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $paypalToken = $provider->getAccessToken();
                                
    
    $response = $provider->createOrder([
        "intent" => "CAPTURE",
        "application_context" => [
            "return_url" => route('paypal_success'),
        ],
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $totalprice
                ],
                "description" => "Monthly Plan"
            ]
        ]
    ]);
    
    if(isset($response['id']) && $response['id']!=null) {
        foreach($response['links'] as $link) {
            if($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }
    } 

}

public function paypalPaymentSuccess(Request $request)
    {
        $user = Auth::user();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        $data = session('myDataa');
        $employeeCount = $data['nbremp'];
        $totalprice = $data['totalprice'];
        $plan = $data['plan'];

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
         
            $sale = new Sale();
            $sale->user_id = $user->id;
            $sale->plan_id = $plan->id;
            $sale->employee_count = $employeeCount;
            $sale->total_price = $totalprice;
            $sale->payment_status = 'Paid Successfuly';
            $sale->type = 'Plan Purchase';
            $sale->invoice = 'jknkn';
            $sale->save();
    
            $employeeCountHistory = Employeecounthistory::where('user_id', $user->id)->first();
    
            if ($employeeCountHistory) {
    
                $employeeCountHistory->total_employee_count += $employeeCount;
                $employeeCountHistory->save();
    
            } else {
    
                $employeeCountHistory = new Employeecounthistory();
                $employeeCountHistory->user_id = $user->id;
                $employeeCountHistory->total_employee_count = $employeeCount;
                $employeeCountHistory->save();
            }
            $addingdays = now()->addDays(30);
            $user->plan_id = 2;
            $user->subscription_end_date = $addingdays;
            $user->save();
    
    
            return redirect()->route('dashboard')->with('success', 'Payment successful.');
        } 
    }

  // ----------------------------------------------------------------------------------------------
  public function paypalUpgradePayment()
{
    
    $user = Auth::user();
    $plan = Plan::find($user->plan_id);
    $employeeCount= $user->employeecounthistory->total_employee_count;

    $totalprice = $plan->each_employee_price * $employeeCount;
        
    $data = [
        'nbremp' => $employeeCount,
        'totalprice' => $totalprice,
        'plan' => $plan
    ];
    session(['myDataa2'=>$data]);

    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $paypalToken = $provider->getAccessToken();
                                
    
    $response = $provider->createOrder([
        "intent" => "CAPTURE",
        "application_context" => [
            "return_url" => route('paypal_upgrade_success'),
        ],
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $totalprice
                ],
                "description" => "Monthly Plan"
            ]
        ]
    ]);
    
    if(isset($response['id']) && $response['id']!=null) {
        foreach($response['links'] as $link) {
            if($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }
    } 

}

public function paypalPaymentUpgradeSuccess(Request $request)
    {
        $user = Auth::user();
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        $data = session('myDataa2');
        $employeeCount = $data['nbremp'];
        $totalprice = $data['totalprice'];

        if(isset($response['status']) && $response['status'] == 'COMPLETED') {
          
            
            $currentSubscriptionEndDate = $user->subscription_end_date;
            if ($currentSubscriptionEndDate <= now()) {
                $newEndDate = now()->addDays(30);
            } else {
                $newEndDate = (new carbon($currentSubscriptionEndDate))->addDays(30);
            }
    
            $user->subscription_end_date = $newEndDate;
            $user->save();
    
            $sale = new Sale();
            $sale->user_id = $user->id;
            $sale->plan_id = $user->plan_id;
            $sale->employee_count = $employeeCount;
            $sale->total_price = $totalprice; 
            $sale->payment_status = 'Paid Successfuly';
            $sale->type = 'Plan Upgrade';
            $sale->invoice = 'k;fam;f';
            $sale->save();
    
           
    
            return redirect()->route('dashboard')->with('success', 'Payment successful.');
        } 
    }
    // ----------------------------------------------------------------------------------------------

    public function paypalAddPayment($nbremp)
    {         
    
    $user = Auth::user();
    $plan = Plan::find(2);
    
    $additionalEmployees = $nbremp - $user->employeeCountHistory->total_employee_count;
    $dailyRate = $user->plan->each_employee_price / 30;
    $currentExpirationDate = $user->subscription_end_date;
    $remainingDays = Carbon::parse($currentExpirationDate)->diffInDays(Carbon::now());
    $additionalPrice=$dailyRate * $remainingDays;
    $additionalCost = $dailyRate * $remainingDays * $additionalEmployees;
    
    
    $data = [
        'nbremp' => $additionalEmployees,
        'totalprice' => $additionalCost,
        'plan' => $plan
    ];
    session(['myDataa3'=>$data]);
    
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
                                    
        
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal_add_success'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => round($additionalPrice)
                    ],
                    "description" => "Monthly Plan"
                ]
            ]
        ]);
         
        
        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } 
    
    }
    
    public function paypalAddPaymentSuccess(Request $request)
        {
            $user = Auth::user();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request->token);

            $data = session('myDataa3');
            $employeeCount = $data['nbremp'];
            $totalprice = $data['totalprice'];
        
            if(isset($response['status']) && $response['status'] == 'COMPLETED') {
               
                $sale = new Sale();
                $sale->user_id = $user->id;
                $sale->plan_id = $user->plan_id;
                $sale->employee_count = $employeeCount;
                $sale->total_price = $totalprice;
                $sale->payment_status = 'Paid Successfuly';
                $sale->type = 'Employee Addition';
                $sale->invoice = 'fajfka';
                $sale->save();
            
                $employeeCountHistory = $user->employeeCountHistory;
                $employeeCountHistory->total_employee_count += $employeeCount;
                $employeeCountHistory->save();
        
        
                return redirect()->route('dashboard')->with('success', 'Payment successful.');
            } 
        }
    
}
