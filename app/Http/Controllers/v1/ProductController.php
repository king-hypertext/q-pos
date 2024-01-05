<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Products;
use App\Models\v1\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::all();
        return view("pages.products", ["products" => $products, "title" => "Inventories"]);
    }
    public function view($enquiry)
    {
        $low_stock = Products::where('quantity', '<=', '5')->get();
        $out_of_stock = Products::where('quantity', '<=', '0')->get();
        $expired_products = Products::where('expiry_date', '<=', Carbon::now()->addMonth())->get();
        if ($enquiry == 'low_stock') {
            return view('pages.product-enquiry', ['title' => 'Products-Low Stock', 'data' => $low_stock]);
        } else if ($enquiry == 'out_of_stock') {
            return view('pages.product-enquiry', ['title' => 'Products-Out of Stock', 'data' => $out_of_stock]);
        } else if ($enquiry == 'expired') {
            return view('pages.product-enquiry', ['title' => 'Expired Products', 'data' => $expired_products]);
        }
    }
    public function AddProduct()
    {

        return view('pages.add_product', compact(''));
    }
    /** insert new data into database */
    public function create(Request $request)
    {
        $category = Suppliers::where('name', $request->supplier)->value('category');
        $request->validate([
            "product-name" => "required|unique:products,name",
            "unit-price" => "required|numeric",
            "supplier" => "required|string|exists:suppliers,name",
            "expiry-date" => "required",
        ]);
        $image = '';
        if ($request->hasFile("product-image")) {
            $request->validate([
                "product-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            $file = $request->file("product-image");
            $image = time() . "." . $request->input("product-name") . '.' . $file->extension();
            $file->move(public_path('assets/images/products'),  $image);
        }
        Products::insert([
            "name" => $request->input("product-name"),
            "price" => $request->input("unit-price"),
            "batch_number" => $request->input("batch-number"),
            "supplier" => $request->input("supplier"),
            "prod_date" => $request->input("prod-date"),
            "image" => $image,
            "category" => $category,
            "expiry_date" => $request->input("expiry-date"),
            "created_at" => Carbon::now()->format("Y-m-d H-i-s"),
        ]);
        return back()->with("success", "Product Added");
    }
    /**  */
    public function store(Request $request)
    {
        // dd($request->id);
        $data = DB::table('products')->where('id', $request->id)->increment("quantity", $request->input("quantity"));
        if ($data) {
            Products::where('id', $request->id)->update([
                "quantity_note" => $request->input("update_info"),
                "updated_at" => Carbon::now()->format("Y-m-d"),
            ]);
        }
        return redirect()->route('products')->with("success", "Quantity Updated");
    }
    /** retrieve a product from database */
    public function show($id)
    {
        $data = Products::find($id);
        return view("pages.view-product", compact("data"));
    }
    /**  */
    public function price(Request $request)
    {
        $data = Products::where('name', $request->query('q'))->get(['price', 'quantity']);
        return response()->json(["data" => $data], 200);
    }
    public function edit($id)
    {
        $product = Products::find($id);
        return response()->json($product);
    }
    public function update(Request $request)
    {
        $product = Products::find($request->id);
        // dd($product);
        $category = Suppliers::where('name', $request->input("edit-supplier"))->value('category');
        $request->validate([]);
        $image = '';
        if ($request->hasFile("edit-product-image")) {
            $request->validate([
                "edit-product-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            if (file_exists(public_path() . "/assets/images/products" . $product->image)) {
                @unlink(public_path() . "/assets/images/products" . $product->image);
            }
            $file = $request->file("edit-product-image");
            $image = time() . $request->input("edit-product-name") . '.' . $file->extension();
            $file->move(public_path('assets/images/products'),  $image);
            Products::where('id', $request->id)->update([
                "name" => $request->input("edit-product-name"),
                "price" => $request->input("edit-unit-price"),
                "batch_number" => $request->input("edit-batch-number"),
                "supplier" => $request->input("edit-supplier"),
                "prod_date" => $request->input("edit-prod-date"),
                "image" => $image,
                "category" => $category,
                "expiry_date" => $request->input("edit-expiry-date"),
                "updated_at" => Carbon::now()->format("Y-m-d"),
            ]);
        } else {
            Products::where('id', $request->id)->update([
                "name" => $request->input("edit-product-name"),
                "price" => $request->input("edit-unit-price"),
                "batch_number" => $request->input("edit-batch-number"),
                "supplier" => $request->input("edit-supplier"),
                "prod_date" => $request->input("edit-prod-date"),
                "category" => $category,
                "expiry_date" => $request->input("edit-expiry-date"),
                "updated_at" => Carbon::now()->format("Y-m-d"),
            ]);
        }
        return back()->with('success', 'Product Updated');
    }
    public function destroy($id)
    {
        $product = Products::find($id);
        if (file_exists(public_path() . "/assets/images/products" . $product->image)) {
            @unlink(public_path() . "/assets/images/products" . $product->image);
        }
        Products::destroy($id);
        return back()->with("success", "Product Deleted");
    }
}
