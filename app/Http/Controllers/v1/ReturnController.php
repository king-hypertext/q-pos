<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Orders;
use App\Models\v1\Returns;
use App\Models\v1\Products;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Returns::select('*');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);
                return DataTables::of($data)->make(true);
            }
        }
        return view("pages.returns", ['title' => 'Returns']);
    }
    public function create(Request $request)
    {
        $product_id = Products::where('name', $request->input('product-name'))->value('id');
        $request->validate([
            "customer_id" => "required",
            "product-name" => "required",
            "customer-name" => "required",
            "price" => "required",
            "quantity" => "required",
            "return-quantity" => "required",
        ]);
        Products::where('id', $product_id)->increment('quantity', $request->input('return-quantity'));
        Orders::where('order_number', $request->input('r_id'))->update(['return_quantity' => $request->input('return-quantity')]);
        $amount = Orders::where('order_number', $request->input('r_id'))->value('amount');
        Orders::where('order_number', $request->input('r_id'))->update([
            'quantity' => $request->input('quantity') - $request->input('return-quantity'),
            'amount' => $amount - ($request->input('return-quantity') * $request->input('price')),
        ]);
        Returns::insert([
            "return_id" => mt_rand(000000, 999999),
            "product" => $request->input('product-name'),
            "product_id" => $product_id,
            "customer" => $request->input('customer-name'),
            "customer_id" => $request->input('customer_id'),
            "quantity" => $request->input('return-quantity'),
            "price" => $request->input('price'),
            "amount" => ($request->input('return-quantity') * $request->input('price')),
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);
        return back()->with('success', 'Return Succeed');
    }
}
