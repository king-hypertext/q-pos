<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Orders;
use App\Models\v1\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\v1\Products;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Orders::select('*');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date])->orderBy('created_at', 'DESC');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Action', function ($row) {
                        $btn = "<button type='button' id='$row->id' class='btn_return_order btn btn-primary'>Return</button>";
                        return $btn;
                    })
                    ->rawColumns(['Action'])
                    ->make(true);
            }
        }
        return view("pages.orders", ["title" => "Orders"]);
    }

    public function show($id)
    {
        $order = Orders::find($id);
        return response()->json($order);
    }
    public function create(Request $request)
    {
        $request->validate([
            "customer.*" => "required|exists:customers,name",
            "product.*" => "required|exists:products,name",
        ]);
        $product = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $id = $request->id;
        $days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];
        $week_day = Carbon::now()->dayOfWeek;
        for ($i = 0; $i < count($product); $i++) {
            $data = [
                'order_number' => mt_rand(000011, 990099),
                'customer_id' => $id,
                'customer' => $request->customer,
                'product' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$week_day],
                'created_at' => Carbon::now()->format('Y-m-d')
            ];
            Orders::insert($data);
            DB::table('products')
                ->where('name', $product[$i])
                ->decrement('quantity', $quantity[$i]);
        }
        return redirect()->route("orders")->with("success", "Invoice Saved");
    }

    public function show_and_update($customer_id)
    {
        $customer = Customers::find($customer_id);
        $data = Orders::where('customer_id', $customer_id)->where('created_at', Carbon::now()->format('Y-m-d'))->get();
        return count($data) !== 0 ? view('pages.view-order', ['orders' => $data, 'customer' => $customer, 'title' => 'Saved Orders']) : redirect()->route('customers');
    }
    public function update_and_save(Request $request)
    {
        // dd($request);
        $products = $request->product;
        $quantity = $request->quantity;
        for ($i = 0; $i < count($products); $i++) {
            DB::table('products')->where('name', $products[$i])->increment('quantity', $quantity[$i]);
        }
        return redirect()->route('orders')->with('success', '');
    }

    public function destroy($id)
    {
        $order = Orders::find($id);
        $qty = $order->quantity;
        $update = Products::where('name', $order->product)->increment('quantity', $qty);
        if ($update) {
            Orders::destroy($id);
            return response()->json(["success" => "Order Deleted"]);
        }
    }
    // public function deleteAll(Request $request)
    // {
    //     $order_ids = $request->input('order_ids');
    //     $ids = array_map('intval', $order_ids);
    //     for ($i = 0; $i < count($ids); $i++) {
    //         $orders = Orders::where('id', $ids[$i])->get();
    //         for ($i = 0; $i < count($orders); $i++) {
    //             DB::table('products')->where('name', $orders[$i]->product)->increment('quantity', $orders[$i]->quantity);
    //         }
    //     }
    //     return response()->json(["success" => "Order Deleted"]);
    // }
}
