<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Orders;
use App\Models\v1\Invoice;
use App\Models\v1\Products;
use App\Models\v1\Customers;
use App\Models\v1\Suppliers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Response;

class AppController extends Controller
{
    public function test()
    {
        return view('pages.test');
    }
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
        $request->session()->regenerateToken();
        $request->session()->invalidate();
        return redirect()->to('/');
    }
    public function resetPassword(Request $request)
    {
        $dob = $request->input("dob");
        // dd($dob);
        $validate = $request->validate([
            'dob' => "required|exists:users,date_of_birth"
        ]);
        if ($validate == true && DB::table("users")->where("date_of_birth", $dob)->first()) {
            return redirect()->to('/auth/verify-secret-code')->with("authorize", "true");
        }
        return back()->with("error", "Invalid date of birth");
    }
    public function checkSecretCode(Request $request)
    {
        $secret_code = $request->input("secret-code");
        $validate = $request->validate([
            'secret-code' => 'required|exists:users,secret_code'
        ]);
        if ($validate == true && DB::table("users")->where("secret_code", $secret_code)->first()) {
            return redirect()->to('/auth/new-password')->with("authorize", "true");
        }
    }
    public function NewPassword(Request $request)
    {
        $new_password = $request->input("new-password");
        DB::table("users")->update([
            "password" => Hash::make($new_password)
        ]);
        return redirect("/auth/login")->with("login", "Password was changed\nLogin with your new password");
    }
    public function index()
    {
        $products = Products::all()->count("id");
        $low_stock = Products::where('quantity', '<=', '5')->where('quantity', '>', '1')->count('id');
        $out_of_stock = Products::where('quantity', '<=', '0')->count('id');
        $expired_products = Products::where('expiry_date', '<=', Carbon::now()->addMonth())->count('id');
        $suppliers = Suppliers::all()->count('id');
        $t_orders = Orders::where('created_at', Date('Y-m-d'))->count('id');
        $orders = Orders::all()->count('id');
        $customers = Customers::all()->count('id');
        return view("pages.dashboard", ['title' => 'Dashboard', 'suppliers' => $suppliers, 'all_products' => $products, 'low_stock' => $low_stock, 'out_of_stock' => $out_of_stock, 'expired' => $expired_products, 'today_orders' => $t_orders, 'orders' => $orders, 'customers' => $customers]);
    }

    public function invoiceOrders()
    {
        $invoices = Invoice::where('for', 'customer')->where('type', 'order')->orderBy('created_at', 'DESC')->get();
        return view('pages.order_invoice', ['title' => 'Invoices-Orders', 'invoices' => $invoices]);
    }
    public function invoiceSuppliers()
    {
        $data = Invoice::where('for', 'supplier')->latest()->get();
        return view('pages.supplier_invoice', ['title' => 'Invoices-Suppliers', 'invoices' => $data, 'supplier' => $data]);
    }
    public function getInvoiceOrders($token)
    {
        $customer =  DB::table('customer_stock')->select('customer')->where('order_token', $token)->value('supplier');
        $date =  DB::table('customer_stock')->select('invoice_time')->where('order_token', $token)->value('invoice_date');
        $invioce_date =  DB::table('customer_stock')->select('created_at')->where('order_token', $token)->value('created_at');
        $id = Orders::where('order_token', $token)->value('customer_id');
        $customer_data = Customers::find($id);
        $customer_data->fresh();
        Pdf::loadView(
            'pages.invoices',
            [
                'invoices' => DB::table('customer_stock')->where('order_token', $token)->get(),
                'supplier' => $customer_data,
                'total' => DB::table('customer_stock')->where('order_token', $token)->sum('amount'),
                'date' => $invioce_date
            ]
        )->save(public_path("/assets/pdf/invoices/" . $customer . "-" . $date . ".pdf"));

        $file = public_path("/assets/pdf/invoices/$customer-$date.pdf");
        return Response::make(file_get_contents($file, true), 200, ['content-type' => 'application/pdf']);
    }
    public function getInvoiceSuppliers($token)
    {
        $id = DB::table('supplier_stock')->where('order_token', $token)->value('supplier_id');

        $supplier =  DB::table('supplier_stock')->select('supplier')->where('order_token', $token)->value('supplier');
        $date =  DB::table('supplier_stock')->select('invoice_time')->where('order_token', $token)->value('invoice_date');
        $supplier_data = Suppliers::find($id);
        $supplier_data->fresh();
        Pdf::loadView(
            'invoice.template',
            [
                'invoices' => DB::table('supplier_stock')->where('order_token', $token)->get(),
                'supplier' => $supplier_data,
                'total' => DB::table('supplier_stock')->where('order_token', $token)->sum('amount'),
                'invoice_number' => DB::table('supplier_stock')->where('order_token', $token)->first('invoice_number'),
                'order_number' => DB::table('supplier_stock')->where('order_token', $token)->first('order_number'),
                'date' => DB::table('supplier_stock')->where('order_token', $token)->first('created_at'),
            ]
        )->save(public_path("/assets/pdf/invoices/" . $supplier . "-" . $date . ".pdf"));

        $file = public_path("/assets/pdf/invoices/$supplier-$date.pdf");
        return Response::make(file_get_contents($file, true), 200, ['content-type' => 'application/pdf']);
    }
    public function globalSearch(Request $request)
    {
        $keyword = $request->q;
        return back();
    }
    public function settings(Request $request)
    {
        return view("pages.settings", ["title" => "Settings"]);
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
            $filename = join('-', explode(' ', trim($request->input('name') . "." . $file->extension())));
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
