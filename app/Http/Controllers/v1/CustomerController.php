<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Orders;
use App\Models\v1\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
        $request->validate([
            'customer-name' => 'required|string',
            'contact' => 'required|numeric',
            'customer-image' => 'required|file|mimes:png,jpg,jpeg,webp',
        ]);
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
        $data = Orders::where('customer_id', $id)->where('created_at', Carbon::now()->format('Y-m-d'))->get();
        $customer = Customers::find($id);
        $order_date = Orders::where('customer_id', $id)->where('created_at', Carbon::now()->format('Y-m-d'))->value('created_at');
        return view("pages.view-stock", ["customer" => $customer, "title" => "Create Order", "orders" => $data, 'order_date'=>$order_date]);
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
    public function filter_and_update(Request $request)
    {
        $query = DB::table('orders')->where('customer_id', $request->id)->where('created_at', $request->date)->get();
        $date = '';
        foreach ($query as $q) {
            $date = $q->created_at;
        }
        return count($query) !== 0 ? response()->json(['success' => route('order.show.save', [$request->id]), 'date' => $date]) : response()->json(['empty' => 'No Records Found']);
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
