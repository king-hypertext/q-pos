<?php

namespace App\Http\Controllers\v1;

use Nette\Utils\Random;
use App\Models\v1\Orders;
use App\Models\v1\Products;
use App\Models\v1\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
// use Barryvdh\DomPDF\PDF;

// use Barryvdh\DomPDF\PDF;

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
    public function search(Request $request)
    {
        $res = Products::select("name")->where("name", "LIKE", "%{$request->term}%")->get();
        return response()->json($res);
        // $query = $request->get('term', '');
        // return response()->json($query);
        // $products = Products::where('name', 'LIKE', '%' . $query . '%')->get();

        // $data = [];

        // foreach ($products as $product) {
        //     $data[] = [
        //         'id' => $product->id,
        //         'value' => $product->name,
        //     ];
        // }

        // return response()->json($data);
    }
    public function show($id)
    {
        $order = Orders::find($id);
        return response()->json($order);
    }
    public function create(Request $request)
    {
        dd($request);
        // $request->validate([
        //     "customer.*" => "required|exists:customers,name",
        //     "product.*" => "required|exists:products,name",
        // ]);
        // $product = $request->product;
        // $price = $request->price;
        // $quantity = $request->quantity;
        // $id = $request->id;
        // $days = [
        //     "Sunday",
        //     "Monday",
        //     "Tuesday",
        //     "Wednesday",
        //     "Thursday",
        //     "Friday",
        //     "Saturday",
        // ];
        // for ($i = 0; $i < count($product); $i++) {
        //     dd($request);
        // }
        // $week_day = Carbon::now()->dayOfWeek;
        // define('_token', Random::generate(120, '0-9a-z.A-Z'));
        // for ($i = 0; $i < count($product); $i++) {
        //     $data = [
        //         'order_number' => mt_rand(000011, 990099),
        //         'customer_id' => $id,
        //         'order_token' => _token,
        //         'customer' => $request->customer,
        //         'product' => $product[$i],
        //         'price' => $price[$i],
        //         'quantity' => $quantity[$i],
        //         'amount' => ($price[$i] * $quantity[$i]),
        //         'day' => $days[$week_day],
        //         'created_at' => Carbon::now()->format('Y-m-d')
        //     ];
        //     Orders::insert($data);
        //     DB::table('products')
        //         ->where('name', $product[$i])
        //         ->decrement('quantity', $quantity[$i]);
        // }


        $pdf = app('dompdf.wrapper');
        $pdf->loadView('invoice.template', ['data', '$data']);
        return $pdf->setPaper('a4')->stream($request->customer . '-order-' . Date('Y-m-d'), ['Attachment' => false]);
        // $pdf->setPaper('a4')->stream('filename.pdf', ['Attachment' => false])
        // return redirect()->route("orders")->with("success", "Invoice Saved");
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
