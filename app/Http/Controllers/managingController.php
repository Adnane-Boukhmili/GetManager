<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Employeecounthistory;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Auth;

class managingController extends Controller
{
    public function managing(){
        $user = auth()->user();
        $employees=Employee::where('user_id',$user->id)->get();
        if($employees){
            return view('managing', compact('employees'));
        }else{
            return view ('managing');
        }
    }
    public function invoices(){
        $user = auth()->user();
        $invoices=Sale::where('user_id',$user->id)->get();
        return view('invoices',compact('invoices'));
    }


    public function create_employer(Request $request)
    {
        
        $user = Auth::user();

        $isFreeTrial = $user->plan->name === 'Free trial';
        $hasCreatedEmployer = $user->employees->count() > 0;

        $employeeCountHistory = $user->employeecounthistory;

        if ($employeeCountHistory) {
            $maxAllowedEmployees = $employeeCountHistory->total_employee_count;
            if ($user->employees->count() >= $maxAllowedEmployees) {
                return view('error2');
            }
        }

        if ($isFreeTrial && $hasCreatedEmployer) {
           return view('error');
        } else {
            $employer = new Employee();
            $employer->name = $request->input('name');
            $employer->email = $request->input('email');
            $employer->password = bcrypt($request->input('password'));
            $employer->user_id = $user->id;
            $employer->save();
            return redirect()->route('managing');
        }
    }

    public function delete_employee($id){
        $user = Auth::user();
        $employee = Employee::where('id', $id)->where('user_id', $user->id)->first();
        $employee->delete();
        return redirect()->back();
    }

    public function edit_employee($id){
        $e=Employee::find($id);
        return view('editemployee', compact('e'));
    }

    public function update_employee(Request $request,$id){
        $update=Employee::find($id);
        $update->name = $request->name;
        $update->email = $request->email;
        $update->password = $request->password;
        $update->save();
        return redirect()->route('managing');
    }
}
