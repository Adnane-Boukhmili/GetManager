<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Employeecounthistory;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Auth;

class invoiceController extends Controller
{
    public function invoices(){
        $user = auth()->user();
        $invoices=Sale::where('user_id',$user->id)->orderBy('created_at', 'desc')->get();
        return view('invoices',compact('invoices'));
    }
}