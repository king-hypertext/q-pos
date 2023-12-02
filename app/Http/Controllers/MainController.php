<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Nette\Utils\Random;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Orders;
use App\Models\Stocks;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class MainController extends Controller
{
    public function login()
    {
        return view("auth.login", ['title' => 'Login']);
    }
    public function handleLogin(Request $request)
    {
        $credentials = $request->only("username", "password");
        if (Auth::attempt($credentials)) {
            DB::table("users")->update(["login" => Carbon::now()]);
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        return back()->with("error", "Invalid credentials");
    }
    public function handleLogout(Request $request)
    {
        DB::table("users")->update(["logout" => Carbon::now()]);
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function resetPassword(Request $request)
    {
        $dob = $request->input("dob");
        // dd($dob);
        $validate = $request->validate([
            'date_of_birth' => "required|exists:users,date_of_birth"
        ]);
        if ($validate == true && DB::table("users")->where("date_of_birth", $dob)->first()) {
            return redirect()->to('/auth/new-password')->with("authorize", "true");
        }
        return back()->with("error", "Invalid date of birth");
    }
    public function NewPassword(Request $request)
    {
        $new_password = $request->input("new-password");
        DB::table("users")->update([
            "password" => Hash::make($new_password)
        ]);
        return redirect("/auth/login")->with("login", "Login with your new password");
    }
    public function index()
    {
        $products = Products::all()->count("id");
        $low_stock = Products::where('quantity', '<', '5')->count('id');
        $out_of_stock = Products::where('quantity', '<=', '0')->count('id');
        $expired_products = Products::where('expiry_date', '<=', Date('Y-m-d'))->count('id');
        $suppliers = Suppliers::all()->count('id');
        return view("pages.dashboard", ['title' => 'Dashboard', 'suppliers' => $suppliers, 'all_products' => $products, 'low_stock' => $low_stock, 'out_of_stock' => $out_of_stock, 'expired' => $expired_products]);
    }
    public function products()
    {
        $products = DB::table('products')->get();
        return view("pages.products", ['title' => 'Products', 'products' => $products]);
    }
    public function viewProduct(Request $request)
    {
        $products = Products::where('id', $request->id)->first();
        // dd($products);
        return view("pages.view-product", ["title" => "View Product", "data" => $products]);
    }
    public function productEnquiries(Request $request)
    {
        $request->q;
        $product = DB::table('products')->where($request->query, $request->id)->get();
        return view('pages.product-enquiry', ['title' => 'Product Enquiry', 'product' => $product]);
    }
    public function addProduct(Request $request)
    {
        $request->validate([
            'product-name' => 'required|string',
            'unit-price' => 'required|numeric',
            'qty' => 'required|numeric',
            'supplier' => 'required|string|exists:suppliers,category',
            'prod-date' => 'required',
            'exp-date' => 'required',
            'product-image' => 'file|mimes:png,jpeg,webp,jpg',
        ]);
        $filename = '';
        if ($request->hasFile('product-image')) {
            $file = $request->file('product-image');
            $filename = join('-', explode(' ', trim($request->input('product-name') . "-" . Random::generate(15) . "." . $file->extension())));
            $file->move(public_path('assets/images/products'),  $filename);
        }
        DB::table('products')->insert([
            'product_name' => $request->input('product-name'),
            'price' => $request->input('unit-price'),
            'quantity' => $request->input('qty'),
            'batch_number' => $request->input('batch-number'),
            'supplier_name' => $request->input('supplier'),
            'prod_date' => $request->input('prod-date'),
            'expiry_date' => $request->input('exp-date'),
            'product_image' => $filename,
            'date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return back()->with('product-added', 'Product Added Succesfully');
    }
    public function updateProduct(Request $request)
    {
        dd($request->id);
        $product = Products::findOrFail($request->id)->value('id');
        $request->validate([
            'product-name' => 'required|string',
            'unit-price' => 'required|numeric',
            'qty' => 'required|numeric',
            'supplier' => 'required|string|exists:suppliers,category',
            'prod-date' => 'required',
            'exp-date' => 'required',
            'product-image' => 'file|mimes:png,jpeg,webp,jpg',
        ]);
        $filename = '';
        if ($request->hasFile('product-image')) {
            if (file_exists(public_path() . 'assets/images/products/' . $product->product_image)) {
                @unlink(public_path() . 'assets/images/products/' . $product->product_image);
            }
            $file = $request->file('product-image');
            $filename = join('-', explode(' ', trim($request->input('product-name') . "-" . Random::generate(25) . "." . $file->extension())));
            $file->move(public_path('assets/images/products'),  $filename);
        }
        Products::findOrFail($request->id)->update([
            'product_name' => $request->input('product-name'),
            'price' => $request->input('unit-price'),
            'quantity' => $request->input('qty'),
            'batch_number' => $request->input('batch-number'),
            'supplier_name' => $request->input('supplier'),
            'prod_date' => $request->input('prod-date'),
            'expiry_date' => $request->input('exp-date'),
            'update_info' => $request->input('update_info'),
            'product_image' => $filename,
            'date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now()->format('Y-m-d'),
            'updated_at' => Carbon::now()->format('Y-m-d'),
        ]);
        return back()->with('success', 'Product Updated');
    }
    public function orders(Request $request)
    {
        $data = DB::table('orders')->select('*');
        if ($request->ajax()) {
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('date', [$request->from_date, $request->to_date]);
                return DataTables::of($data)->addIndexColumn()/* ->addColumn('Action', function ($row) {
                    $button = "<a href='{{ url('/orders/" . $row->id . "/edit') }}' class='btn btn-primary text-lowercase me-2'>Edit</a>";
                    return $button; /* .''. $row->id .''. *\/
                })->rawColumns(['Action']) */->make(true);
            }
        } 
        /* else {
            $orders = DB::table('orders')->orderBy('date', 'desc')->paginate(10);
 
        }*/
        return view('pages.orders');

    }
    // public function getOrders(Request $request)
    // {
    //     $orders = DB::table('orders')->orderBy('created_at', 'desc')->paginate(10);
    //     if ($request->ajax()) {
    //         if ($request->filled('from_date') && $request->filled('to_date')) {
    //             $data = DB::table('orders')->select()->whereBetween('created_at', [$request->from_date, $request->to_date]);
    //             return DataTables::of($data)->addIndexColumn()->addColumn('Action', function ($row) {
    //             })->rawColums(['Action'])->make(true);
    //         }
    //     }
    //     return view('pages.orders', ['title' => 'Orders', 'data' => $orders]);
    // }
    public function suppliers()
    {
        $suppliers = Suppliers::all();
        return $suppliers ? view("pages.suppliers", ['title' => 'Suppliers', 'suppliers' => $suppliers]) : view("pages.suppliers", ['title' => 'Suppliers']);
    }
    public function addSupplier(Request $request)
    {
        $request->validate([
            'supplier-name' => 'required|string',
            'product-name' => 'required|string',
            'cat' => 'required|string',
            'contact' => 'required',
            'address' => 'required|string',
        ]);
        DB::table('suppliers')->insert([
            'supplier_name' => $request->input('supplier-name'),
            'product_name' => $request->input('product-name'),
            'category' => $request->input('cat'),
            'contact' => $request->input('contact'),
            'address' => $request->input('address'),
            'date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return back()->with('supplier-added', 'Supplier Has Been Added');
    }
    public function salesReport()
    {
        return view("pages.sales-report", ['title' => 'Sales Report']);
    }
    public function customers()
    {
        $customers = Customers::latest('date')->get();
        return $customers ? view("pages.customers", ["title" => "Customers", 'customers' => $customers]) : view('pages.customers', ['title' => 'Customers']);
    }
    public function addCustomer(Request $request)
    {
        $request->validate([
            'customer-name' => 'required|string',
            'customer-dob' => 'required',
            'gender' => 'required|string',
            'customer-contact' => 'required',
            'customer-address' => 'required|string',
            'customer-image' => 'file|mimes:png,jpeg,webp,jpg',
        ]);
        $file = $request->file('customer-image');
        $filename = join('-', explode(' ', trim($request->input('customer-name') . "-" . Random::generate(25) . "." . $file->extension())));
        if ($request->hasFile('customer-image')) {
            $file->move(public_path('assets/images/customers'),  $filename);
        }
        DB::table('customers')->insert([
            'customer_name' => $request->input('customer-name'),
            'date_of_birth' => $request->input('customer-dob'),
            'gender' => $request->input('gender'),
            'contact' => $request->input('customer-contact'),
            'address' => $request->input('customer-address'),
            'customer_image' => $filename,
            'date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return back()->with('customer-added', 'Customer Has Been Added');
    }
    public function editCustomerView(Request $request)
    {
        $customer = Customers::findOrFail($request->id)->first();
        return view('pages.edit-customer', ['title' => 'Update Customer', 'data' => $customer]);
    }
    public function editCustomer(Request $request)
    {
        $customer = Customers::findOrFail($request->id)->value('customer_name');
        $request->validate([
            'customer-name' => 'required|string',
            'customer-dob' => 'required',
            'gender' => 'required|string',
            'customer-contact' => 'required',
            'customer-address' => 'required|string',
            'customer-image' => 'file|mimes:png,jpeg,webp,jpg',
        ]);
        $filename = '';
        if ($request->hasFile('customer-image')) {
            if (file_exists(public_path() . 'assets/images/customers/' . $customer->customer_image)) {
                @unlink(public_path() . 'assets/images/customers/' . $customer->customer_image);
            }
            $file = $request->file('customer-image');
            $filename = join('-', explode(' ', trim($request->input('customer-name') . "-" . Random::generate(25) . "." . $file->extension())));
            $file->move(public_path('assets/images/customers'),  $filename);
        }
        Customers::findOrFail($request->id)->update([
            'customer_name' => $request->input('customer-name'),
            'date_of_birth' => $request->input('customer-dob'),
            'gender' => $request->input('gender'),
            'contact' => $request->input('customer-contact'),
            'address' => $request->input('customer-address'),
            'customer_image' => $filename,
            'date' => Carbon::now()->format('Y-m-d'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        return back()->with('success', 'Customer Updated');
    }
    public function productPrice(Request $request)
    {
        $query = Products::where('product_name', $request->product)->value('price');
        return $query;
    }
    public function addStock(Request $request)
    {
        $product = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $total = $request->total;
        $id = $request->id;
        // $date = Carbon:
        $days = [
            "Sun",
            "Mon",
            "Tue",
            "Wed",
            "Thur",
            "Fri",
            "Sat",
        ];
        $week_day = Carbon::now()->dayOfWeek;
        $customer = Customers::find($id)->value('id');
        $customer_name = Customers::find($id)->value('customer_name');
        for ($i = 0; $i < count($product); $i++) {
            $data = [
                'customer_id' => $customer,
                'day_name' => $days[$week_day],
                'product_name' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'total' => $total[$i],
                'date' => $request->input('date'),
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d')
            ];
            Orders::insert([
                'product_name' => $product[$i],
                'quantity' => $quantity[$i],
                'customer_name' => $customer_name,
                'price' => $price[$i],
                'day' => $days[$week_day],
                'date' => Carbon::now()->format('Y-m-d'),
                'total' => ($price[$i] * $quantity[$i])
            ]);
            Stocks::insert([
                'customer_name' => $customer_name,
                'day' => $days[$week_day],
                'product_name' => $product[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'total' => $total[$i],
                'date' => $request->input('date'),
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d')
            ]);
            DB::table('products')
                ->where('product_name', $product[$i])
                ->decrement('quantity', $quantity[$i]);
        }
        return back()->with("success", "Invoice Saved");
    }
    public function viewCustomerStock(Request $request)
    {

        return view('pages.view-stock', ['title' => 'Stocks : Customer', 'customer' => Customers::where("id", $request->id)->first()]);
    }
    public function globalSearch(Request $request)
    {
        $keyword = $request->q;
        return "query " . $keyword;
    }
    public function viewCustomer(Request $request)
    {
        return view("pages.view-customer", ['title' => 'View Customer']);
    }
    public function viewSupplier(Request $request)
    {
        return view("pages.view-supplier", ['title' => 'View Supplier']);
    }
    public function deleteProduct(Request $request)
    {
        try {
            /** $file - the existing product image */
            $file = public_path() . "/assets/images/products/" . Products::find($request->id)->product_image;
            if (file_exists($file)) {
                @unlink($file);
            }
            Products::where("id", $request->id)->delete();
            return back()->with('product-deleted', 'Product Deleted Successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function deleteCustomer(Request $request)
    {
        try {
            Customers::where('id', $request->id)->delete();
            return back()->with('customer-deleted', 'Customer Has Been Deleted');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function settings(Request $request)
    {
        return view("pages.settings", ["title" => "Settings"]);
    }
    public function stocks()
    {
        return view("pages.stocks", ["title" => "Stocks"]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "string",
            "user-name " => "string",
            "dob" => "date",
            "user-image" => "file|mimes:png,jpg,jpeg,webp",
        ]);
        $filename = '';
        if ($request->hasFile("user-image")) {
            $file = $request->file("user-image");
            $filename = join('-', explode(' ', trim($request->input('name') . "-" . Random::generate(25) . "." . $file->extension())));
            $file->move(public_path("/assets/images/admin"),  $filename);
        }
        DB::table("users")->update([
            "name" => $request->input("name"),
            "username" => $request->input("user-name"),
            "date_of_birth" => $request->input("dob"),
            "user_image" => $filename,
        ]);
        return back()->with("success", "Profile Updated");
    }
}
