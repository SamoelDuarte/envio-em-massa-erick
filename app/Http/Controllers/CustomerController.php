<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(){
        return view('admin.customer.index');
    }

    public function getCustomers(){
        $customer = Customer::orderBy('id');
        return DataTables::of($customer)->make(true);
    }
    
}
