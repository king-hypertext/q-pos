<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\v1\Orders;
use App\Models\v1\Products;
use App\Models\v1\Customers;
use App\Models\v1\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;

class AppController extends Controller
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
