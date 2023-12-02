<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Orders::select('*');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);
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

    public function show(Request $request, $id)
    {
        $order = Orders::find($id);
        return response()->json($order);
        // dd(count(Orders::where('order_number', $id)->get()));
        // $data = Orders::where('order_number', $id)->get();
        // return count($data) > 0 ? response()->json($data) : response()->json([
        //     "response" => "invalid order number"
        // ], 404);
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
    public function destroy($id)
    {
        Orders::destroy($id);
        return back()->with("success", "Order Deleted");
    }
}
