<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Orders;
use App\Models\v1\Returns;
use App\Models\v1\Products;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
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
        $id = $request->id;
        $order_id = $request->order_id;
        $return_qty = $request->qty;
        Products::where('name', $request->product)->increment('quantity', $return_qty);
        $amount = Orders::where('id', $order_id)->value('amount');
        $quatity = Orders::where('id', $order_id)->value('quantity');
        $price = Orders::where('id', $order_id)->value('price');
        Orders::where('id', $order_id)->update([
            'quantity' => ($quatity - $return_qty),
            'amount' => $amount - ($return_qty * $price),
        ]);
        Returns::insert([
            "return_id" => mt_rand(000000, 999999),
            "product" => $request->product,
            "product_id" => $id,
            "customer" => $request->input('customer-name'),
            "customer_id" => $request->input('customer_id'),
            "quantity" => $return_qty,
            "price" => $price,
            "amount" => ($return_qty * $price),
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);
        DB::table('customer_invoices')->where('product', $request->product)->where('customer_id', $request->customer_id)->decrement('quantity', $return_qty);
        DB::table('customer_stock')->where('product', $request->product)->where('customer_id', $request->customer_id)->decrement('quantity', $return_qty);
        Orders::where('id', $order_id)->increment('return_quantity', $return_qty);
        return response()->json(['success' => 'Return Success']);
    }
}
