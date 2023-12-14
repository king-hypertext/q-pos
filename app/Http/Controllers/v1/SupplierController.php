<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Suppliers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::all();
        return view("pages.suppliers", ["suppliers" => $suppliers, "title" => "Suppliers"]);
    }
    public function create(Request $request)
    {

        $request->validate([
            "supplier-name" => "required|string|unique:suppliers,name",
            // "product-name"=> "string",
            "contact" => "required|string",
            "address" => "required|string",
        ]);
        Suppliers::insert([
            "name" => $request->input("supplier-name"),
            // "product"=> $request->input("product-name"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "created_at" => Carbon::now()->format('Y-m-d')
        ]);
        return back()->with("success", "Supplier Added");
    }
    public function createInvoice(Request $request)
    {
    }
    public function showInvoice()
    {
        return view('pages.create_supplier_invoice', ['title' => 'Create Suppier Invoice']);
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
            "supplier-name" => "required|string",
            "contact" => "required|string",
            "address" => "required|string",
        ]);
        Suppliers::where('id', $request->id)->update([
            "name" => $request->input("supplier-name"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "updated_at" => Carbon::now()->format("Y-m-d"),
        ]);
        return back()->with("success", "Supplier Added");
    }
    public function destroy($id)
    {
        Suppliers::destroy($id);
        return back()->with("success", "Supplier Deleted");
    }
}
