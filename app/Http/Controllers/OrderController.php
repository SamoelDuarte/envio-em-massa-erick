<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(){
        return view('admin.order.index');
    }

    public function getOrders()
    {
        $orders = Order::with('customer')->orderBy('id');
        return DataTables::of($orders)->make(true);
    }

    public function getOrder(Request $request){
        $order = Order::with('customer')->where('id',$request->id)->first();
        echo json_encode($order);
    }
}
