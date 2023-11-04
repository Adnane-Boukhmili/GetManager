<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Employeecounthistory;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Auth;

class pricingController extends Controller
{
    public function set_pricing(Request $request)
    {
        $user = Auth::user();
    
        $employeeCount = $request->input('employeeRange');
    
        $plan = Plan::find(2);
    
        $totalPrice = $plan->each_employee_price * $employeeCount;

        $sale = new Sale();
        $sale->user_id = $user->id;
        $sale->plan_id = $plan->id;
        $sale->employee_count = $employeeCount;
        $sale->total_price = $totalPrice;
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
        
        $user->plan_id = 2;
        $user->save();

        return view('dashboard');
    }
}
