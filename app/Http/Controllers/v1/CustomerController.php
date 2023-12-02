<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customers::all();
        return view("pages.customers", ["customers" => $customers, "title" => "Workers Van"]);
    }
    public function create(Request $request)
    {
        $image = '';
        if ($request->hasFile("customer-image")) {
            $request->validate([
                "customer-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            $file = $request->file("customer-image");
            $image = time() . "." . $request->input("name") . '.' . $file->extension();
            $file->move(public_path('assets/images/customers'),  $image);
        }
        $request->validate([]);
        Customers::insert([
            "name" => $request->input("customer-name"),
            "gender" => $request->input("gender"),
            "date_of_birth" => $request->input("dob"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "image" => $image,
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);

        return back()->with("success", "Customer Added");
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
        $customer = Customers::find($id);
        return view("pages.view-stock", ["customer" => $customer, "title" => "Create Order"]);
    }
    public function edit($id)
    {
        $customer = Customers::find($id);
        return response()->json($customer);
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $customer = Customers::find($request->id);
        $request->validate([]);
        $image = '';
        if ($request->hasFile("customer-image")) {
            $request->validate([
                "customer-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            if (file_exists(public_path() . "/assets/images/customers" . $customer->image)) {
                @unlink(public_path() . "/assets/images/customers" . $customer->image);
            }
            $file = $request->file("customer-image");
            $image = time() . $request->input("customer-name") . '.' . $file->extension();
            $file->move(public_path('assets/images/customers'),  $image);
            Customers::where('id', $request->id)->update([
                'name' => $request->input('customer-name'),
                'date_of_birth' => $request->input('dob'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'image' => $image,
                'updated_at' => Carbon::now()->format('Y-m-d')
            ]);
        } else {
            Customers::where('id', $request->id)->update([
                'name' => $request->input('customer-name'),
                'date_of_birth' => $request->input('dob'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'updated_at' => Carbon::now()->format('Y-m-d')
            ]);
        }
        return back()->with('success', 'Customer Updated');
    }
    public function destroy($id)
    {
        $customer = Customers::find($id);
        if (file_exists(public_path() . "/assets/images/customers/" . $customer->image)) {
            @unlink(public_path() . "/assets/images/customers/" . $customer->image);
        }
        Customers::destroy($id);
        return back()->with("success", "Customer Deleted");
    }
}
