<?php

namespace App\Http\Controllers\v1;

use Nette\Utils\Random;
use App\Models\v1\Orders;
use App\Models\v1\Invoice;
use App\Models\v1\Products;
use App\Models\v1\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CustomerInvoices;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

include_once 'constant.php';
class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Orders::select('*')->where('quantity', '>=', 0);
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Action', function ($row) {
                        $div = "<form id='form-return' method='post'>
                        <div class='form-group d-flex'>
                            <input style='min-width: 75px;' required type='text' id='$row->quantity' class='return_input form-control form-control-sm me-1' placeholder='quantity' />
                            <input type='hidden' name='customer_name' value='$row->customer'/>
                            <input type='hidden' name='customer_id' value='$row->customer_id'/>
                            <input type='hidden' name='order_id' value='$row->id'/>
                            <input type='hidden' name='product' value='$row->product'/>
                            <input type='hidden' name='order_date' value='$row->created_at'/>
                            <button type='submit' class='btn btn-sm btn-primary btn-return text-capitalize ms-1'>Return</button>
                        </div>
                    </form>";
                        return $div;
                    })
                    ->rawColumns(['Action'])
                    ->make(true);
            }
        }
        return view("pages.orders", ["title" => "Customer Orders"]);
    }
    public function supplierOrders(Request $request)
    {
        // dd($request)
        if ($request->ajax()) {
            $data = DB::table('supplier_stock');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);
                return DataTables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('Action', function ($row) {
                    //     $btn = "<button type='button' id='$row->id' class='btn_return_order btn btn-primary'>Return</button>";
                    //     return $btn;
                    // })
                    // ->rawColumns(['Action'])
                    ->make(true);
            }
        }
        return view("pages.order_suppliers", ["title" => "Supplier Orders"]);
    }
    public function search(Request $request)
    {
        $res = Products::select("name")->where("name", "LIKE", "%{$request->term}%")->get();
        return response()->json($res);
    }
    public function show($id)
    {
        $order = Orders::find($id);
        return response()->json($order);
    }
    public function create(Request $request)
    {
        // dd($request);
        $request->validate([
            "customer" => "required|exists:customers,name",
            "product.*" => "required|exists:products,name",
        ]);
        $product = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $id = $request->id;
        $customer =  $request->customer;
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
                'order_token' => _token,
                'customer' => $request->customer,
                'product' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$week_day],
                'invoice_time' => Date('Y-m-d-H-i'),
                'created_at' => Carbon::now()->format('Y-m-d')
            ];
            $invoice = [
                "customer_id" => $id,
                "product" => $product[$i],
                "quantity" => $quantity[$i],
                "price" => $price[$i],
                "amount" => ($price[$i] * $quantity[$i]),
                "created_at" => Carbon::now()->format('Y-m-d')
            ];
            Orders::insert($data);
            DB::table('customer_stock')->insert($data);
            DB::table('products')
                ->where('name', $product[$i])
                ->decrement('quantity', $quantity[$i]);
            CustomerInvoices::insert($invoice);
        }
        $total = DB::table('customer_stock')->where('order_token', _token)->sum('amount');

        Invoice::create([
            "token" => _token,
            "name" => $request->customer,
            "for" => "customer",
            "for_id" => $id,
            "type" => "order",
            "invoice_number" => mt_rand(100001, 999990),
            "amount" => $total,
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);
       return redirect()->route('customer.show', [$id])->with('success', 'Invoice Saved');
    }

    public function show_and_update(Request $request, $customer_id)
    {
        $customer = Customers::find($customer_id);
        $date = $request->date;
        // dd($date);
        $data = Orders::where('customer_id', $customer_id)->where('created_at', $date)->get();
        return count($data) !== 0 ? view('pages.view-order', ['orders' => $data, 'date' => $date, 'customer' => $customer, 'title' => 'Saved Orders']) : redirect()->route('customers');
    }
    public function update_and_save(Request $request, $id)
    {
        $order_date = $request->order_date;

        $product = $request->product;
        $quantity = $request->quantity;
        $price = $request->price;
        $customer =  $request->customer;

        $id = $request->id;
        $data = Orders::where('customer_id', $id)->where('created_at', $order_date)->get();
        foreach ($data as $key => $order) {
            $q = DB::table('products')->where('name', $order->product)->increment('quantity', $order->quantity);
        }
        // dd($q, $data);

        DB::table('customer_invoices')->where('customer_id', $id)->where('created_at', $order_date)->delete();
        DB::table('customer_stock')->where('customer_id', $id)->where('created_at', $order_date)->delete();
        Invoice::where('for', 'customer')->where('type', 'order')->where('name', $customer)->where('for_id', $id)->where('created_at', $order_date)->delete();
        $delete = DB::table('orders')->where('customer_id', $id)->where('created_at', $order_date)->delete();
        // dd($request->all());


        if ($delete) {
            $request->validate([
                "customer" => "required|exists:customers,name",
                "product.*" => "required|exists:products,name",
            ]);

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
                    'order_token' => _token,
                    'customer' => $customer,
                    'product' => $product[$i],
                    'price' => $price[$i],
                    'quantity' => $quantity[$i],
                    'amount' => ($price[$i] * $quantity[$i]),
                    'day' => $days[$week_day],
                    'invoice_time' => Date('Y-m-d-H-i'),
                    'created_at' => $request->order_date,
                    'updated_at' => Carbon::now()->format('Y-m-d')
                ];
                $invoice = [
                    "customer_id" => $id,
                    "product" => $product[$i],
                    "quantity" => $quantity[$i],
                    "price" => $price[$i],
                    "amount" => ($price[$i] * $quantity[$i]),
                    "created_at" => $request->order_date,
                    "updated_at" => Carbon::now()->format('Y-m-d')
                ];
                Orders::insert($data);
                DB::table('customer_stock')->insert($data);
                DB::table('products')->where('name', $product[$i])->decrement('quantity', $quantity[$i]);
                CustomerInvoices::insert($invoice);
            }
            $total = DB::table('customer_stock')->where('order_token', _token)->sum('amount');

            Invoice::create([
                "token" => _token,
                "name" => $customer,
                "for" => "customer",
                "for_id" => $id,
                "type" => "order",
                "invoice_number" => mt_rand(100001, 999990),
                "amount" => $total,
                "created_at" => $request->order_date,
                "updated_at" => Carbon::now()->format('Y-m-d')
            ]);
        }
        return back()->with('success', 'Invoice Updated');
    }

    public function destroy($id)
    {
        $order = Orders::find($id);
        $qty = $order->quantity;
        $customer_id = $order->customer_id;
        $update = Products::where('name', $order->product)->increment('quantity', $qty);
        if ($update) {
            DB::table('customer_invoices')->where('customer_id', $customer_id)->where('product', $order->product)->where('quantity', $order->quantity)->where('created_at', $order->created_at)->delete();
            DB::table('customer_stock')->where('customer_id', $customer_id)->where('product', $order->product)->where('quantity', $order->quantity)->where('created_at', $order->created_at)->delete();
            Orders::destroy($order->id);
            return response()->json(["success" => "Order Deleted"]);
        }
    }
}
