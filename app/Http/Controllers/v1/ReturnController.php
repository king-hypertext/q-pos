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
            $data = Returns::select('*')->latest();
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);
                return DataTables::of($data)->make(true);
            }
        }
        return view("pages.returns", ['title' => 'Returns']);
    }
    public function create(Request $request)
    {
        $order_id = $request->order_id;
        $return_quantity = $request->return_quantity;
        $order_quantity = $request->order_quantity;
        $date = $request->order_date;
        $returns = $request->returns;
        $product = $request->product;
        $customer = $request->customer;
        $customer_id = $request->customer_id;
        $product_id = Products::where('name', $request->product)->value('id');
        $order = Orders::findOrFail($order_id);
        if (intval($returns) === 0) {
            Products::where('name', $product)->increment('quantity', $return_quantity);
            Returns::insert([
                "return_id" => mt_rand(000000, 999999),
                "product" => $product,
                "product_id" => $product_id,
                "customer" => $customer,
                "customer_id" => $customer_id,
                "quantity" => $return_quantity,
                "price" => $order->price,
                "amount" => ($order->quantity * $order->price),
                "order_id" => $order_id,
                "created_at" => now()->format('Y-m-d')
            ]);
            // DB::table('customer_invoices')
            //     ->where('product', $request->product)
            //     ->where('customer_id', $request->customer_id)
            //     ->where('created_at', $date)
            //     ->update([
            //         'quantity' => ($order->quantity - $return_quantity),
            //         'amount' => (($order->quantity - $return_quantity) * $order->price),
            //         'updated_at' => now()->format('Y-m-d')
            //     ]);
            DB::table('customer_stock')
                ->where('product', $request->product)
                ->where('customer_id', $request->customer_id)
                ->where('created_at', $date)
                ->update([
                    'quantity' => ($order->quantity - $return_quantity),
                    'amount' => (($order->quantity - $return_quantity) * $order->price),
                    'updated_at' => now()->format('Y-m-d')
                ]);
            $order->update([
                'quantity' => ($order->quantity - $return_quantity),
                'amount' => (($order->quantity - $return_quantity) * $order->price),
                'updated_at' => now()->format('Y-m-d'),
                'return_quantity' => $return_quantity
            ]);
        } elseif (intval($return_quantity) === intval($order->return_quantity)) {
            // dd($return_quantity, $order->return_quantity);
            return response()->json(['success' => __('Return Success')]);
        } elseif (intval($returns) > 0) {
            return response()->json(['failed' => __('No more returns allowed')]);
        }
        return response()->json(['success' => __('Return Success')]);
    }
    public function ResetReturn(Request $request, $ID, $order_id)
    {
        $order = Orders::findOrFail($order_id);
        $return = Returns::where('order_id', $order_id)->first();
        $date = Carbon::parse($return->created_at)->format('Y-m-d');
        $order_quantity = $request->order_quantity;
        $order->update([
            'quantity' => ($order_quantity + $ID),
            'amount' => (($order_quantity + $ID) * $order->price),
            'updated_at' => now()->format('Y-m-d'),
            'return_quantity' => 0
        ]);
        $return->delete();
        // DB::table('customer_invoices')
        //     ->where('product', $order->product)
        //     ->where('customer_id', $order->customer_id)
        //     ->where('created_at', $date)
        //     ->update([
        //         'quantity' => $order_quantity + $ID,
        //         'amount' => (($order_quantity + $ID) * $order->price),
        //         'updated_at' => now()->format('Y-m-d')
        //     ]);
        DB::table('customer_stock')
            ->where('product', $order->product)
            ->where('customer_id', $order->customer_id)
            ->where('created_at', $date)
            ->update([
                'quantity' => $order_quantity + $ID,
                'amount' => (($order_quantity + $ID) * $order->price),
                'updated_at' => now()->format('Y-m-d')
            ]);
        Products::where('name', $order->product)->decrement('quantity', $ID);
        return response()->json(['success' => __('Return Reset')]);
    }
}
