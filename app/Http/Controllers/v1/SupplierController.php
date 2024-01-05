<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Invoice;
use App\Models\v1\Products;
use App\Models\v1\Suppliers;
use Illuminate\Http\Request;
use App\Models\SupplierInvoices;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\v1\Orders;

include_once 'constant.php';
class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::all();
        return view("pages.suppliers", ["suppliers" => $suppliers, "title" => "Suppliers"]);
    }
    public function getSupplier($supplier)
    {
        // dd($request);
        // $supplier = $request->supplier;
        $data = Suppliers::where('name', $supplier)->get();
        return response()->json(['data' => $data]);
    }
    public function create(Request $request)
    {
        $request->validate([
            "supplier-name" => "required|string|unique:suppliers,name",
            "contact" => "required|string",
            "address" => "required|string",
        ]);
        Suppliers::insert([
            "name" => $request->input("supplier-name"),
            "address" => $request->input("address"),
            "category" => $request->input("category"),
            "contact" => $request->input("contact"),
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);
        return back()->with("success", "Supplier Added");
    }
    public function createInvoice(Request $request)
    {
        // dd($request);
        $request->validate([
            "supplier" => "required|exists:suppliers,name",
            "product.*" => "required|exists:products,name",
        ]);
        $product = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $id = $request->input('supplier-id');
        $supplier = $request->supplier;
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
                'supplier_id' => $id,
                'order_token' => _token,
                'invoice_number' => $request->input('supplier-invoice'),
                'supplier' => $request->supplier,
                'product' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$week_day],
                'category' => $request->category,
                'invoice_time' => Date('Y-m-d-H-i'),
                'created_at' => Carbon::now()->format('Y-m-d')
            ];
            $invoice = [
                "supplier_id" => $id,
                "product" => $product[$i],
                "quantity" => $quantity[$i],
                "price" => $price[$i],
                "amount" => ($price[$i] * $quantity[$i]),
                "created_at" => Carbon::now()->format('Y-m-d')
            ];

            DB::table('supplier_stock')->insert($data);
            DB::table('products')
                ->where('name', $product[$i])
                ->increment('quantity', $quantity[$i]);
            DB::table('products')
                ->where('name', $product[$i])->update(['price' => $price[$i]]);
            SupplierInvoices::insert($invoice);
        }
        $total = DB::table('supplier_stock')->where('order_token', _token)->sum('amount');
        Invoice::create([
            "token" => _token,
            "name" => $supplier,
            "for" => "supplier",
            "for_id" => $id,
            "type" => "stock",
            "invoice_number" => mt_rand(100000, 999990),
            "amount" => $total
        ]);
        return back()->with('success', 'Invoice Saved');
    }
    public function showCreateInvoice(Request $request)
    {
        // dd($request);
        $products = Products::all();
        $suppliers = Suppliers::all();
        $title = "Create Invoice";
        // $orders = Orders::where('customer_id', $id)->where('created_at', Carbon::now()->format('Y-m-d'))->get();
        return view('pages.create_supplier_invoice', compact('title', 'products', 'suppliers'));
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $supplier = Suppliers::find($id);
        return response()->json($supplier);
    }
    public function update(Request $request)
    {
        // dd($request->id);
        $request->validate([
            "edit-supplier" => "required|string|unique:suppliers,name",
            "edit-category" => "required|string|unique:suppliers,category",
            "edit-contact" => "required|string",
            "edit-address" => "required|string",
        ]);
        Suppliers::where('id', $request->id)->update([
            "name" => $request->input("edit-supplier"),
            "address" => $request->input("edit-address"),
            "category" => $request->input("edit-category"),
            "contact" => $request->input("edit-contact"),
            "updated_at" => Carbon::now()->format('Y-m-d')
        ]);
        return back()->with("success", "Supplier Added");
    }
    public function filter_order(Request $request)
    {
        // dd($request->all());
        $query = DB::table('supplier_stock')->where('supplier', $request->supplier)->where('created_at', $request->date)->get();
        $date = '';
        // dd($query);
        foreach ($query as $q) {
            $date = $q->created_at;
            $id = $q->supplier_id;
        }
        return count($query) !== 0 ? response()->json(['success' => route('order.supplier.show', [$id]), 'date' => $date]) : response()->json(['empty' => 'No Records Found']);
    }
    public function show_saved_order(Request $request, $id)
    {
        // dd($request->date, $id);
        $orders = DB::table('supplier_invoices')->where('supplier_id', $id)->where('created_at', $request->date)->get();
        $supplier = Suppliers::find($id);
        $date = $request->date;
        $invoice_number = DB::table('supplier_stock')->where('supplier_id', $id)->where('created_at', $request->date)->value('invoice_number');
        $category = DB::table('supplier_stock')->where('supplier_id', $id)->where('created_at', $request->date)->value('category');
        // dd($invoice_number);
        return count($orders) !== 0 ? view('pages.view-suppplier-order', compact('orders', 'supplier', 'date', 'invoice_number', 'category')) : redirect()->route('supplier.invoice.add');
    }
    public function update_order(Request $request, $id)
    {
        $order_date = $request->order_date;

        $product = $request->product;
        $quantity = $request->quantity;
        $price = $request->price;
        $supplier =  $request->supplier;

        $id = $request->id;
        $orders = DB::table('supplier_invoices')->where('supplier_id', $id)->where('created_at', $order_date)->get();

        foreach ($orders as $key => $order) {
            $q = DB::table('products')->where('name', $order->product)->decrement('quantity', $order->quantity);
        }
        // dd($request->all(), $q);
        $q1 = DB::table('supplier_invoices')->where('supplier_id', $id)->where('created_at', $order_date)->delete();
        $q2 = DB::table('supplier_stock')->where('supplier_id', $id)->where('created_at', $order_date)->delete();
        $q3 = Invoice::where('for', 'supplier')->where('type', 'stock')->where('name', $supplier)->where('for_id', $id)->where('created_at', $order_date)->delete();

        // dd($q, $q1, $q2, $q3, $request->all());

        $request->validate([
            "supplier" => "required|exists:suppliers,name",
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
                'supplier_id' => $id,
                'order_token' => _token,
                'invoice_number' => $request->input('supplier-invoice'),
                'supplier' => $supplier,
                'product' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$week_day],
                'category' => $request->category,
                'invoice_time' => Date('Y-m-d-H-i'),
                'created_at' => $request->order_date,
                'updated_at' => Carbon::now()->format('Y-m-d')
            ];
            $invoice = [
                "supplier_id" => $id,
                "product" => $product[$i],
                "quantity" => $quantity[$i],
                "price" => $price[$i],
                "amount" => ($price[$i] * $quantity[$i]),
                "created_at" => $request->order_date,
                "updated_at" => Carbon::now()->format('Y-m-d')
            ];
            DB::table('supplier_stock')->insert($data);
            DB::table('products')->where('name', $product[$i])->increment('quantity', $quantity[$i]);
            SupplierInvoices::insert($invoice);
        }
        $total = DB::table('supplier_stock')->where('order_token', _token)->sum('amount');

        Invoice::create([
            "token" => _token,
            "name" => $supplier,
            "for" => "supplier",
            "for_id" => $id,
            "type" => "stock",
            "invoice_number" => mt_rand(100001, 999990),
            "amount" => $total,
            "created_at" => $request->order_date,
            "updated_at" => Carbon::now()->format('Y-m-d')
        ]);
        return back()->with('success', 'Invoice Saved');
    }
    public function deleteOrder($id)
    {
        $order = DB::table('supplier_invoices')->where('id', $id)->first();
        $update = Products::where('name', $order->product)->decrement('quantity', $order->quantity);
        if ($update) {
            DB::table('supplier_invoices')->where('supplier_id', $order->supplier_id)->where('product', $order->product)->where('quantity', $order->quantity)->where('created_at', $order->created_at)->delete();
            DB::table('supplier_stock')->where('supplier_id', $order->supplier_id)->where('product', $order->product)->where('quantity', $order->quantity)->where('created_at', $order->created_at)->delete();
            return response()->json(["success" => "Order Deleted"]);
        }
    }
    public function destroy($id)
    {
        Suppliers::destroy($id);
        return back()->with("success", "Supplier Deleted");
    }
}
