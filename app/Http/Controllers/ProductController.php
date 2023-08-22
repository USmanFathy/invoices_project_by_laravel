<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products' ,[
            'sections'=>$sections,
            'products'=>$products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'product_name'=>'required',
            'section_name'=>'required'
        ],[
            'product_name.required'=>'يرجي أضافة اسم للمنتج',
            'section_name.required'=>'يرجي اختيار القسم '
        ]);

        Product::create([
            'product_name'=>$request->product_name,
            'description'=>$request->description,
            'section_id'=>$request->section_name
        ]);
        session()->flash('Add' ,'تم إضافة المنتج');
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id =$request->id;
        $section_id = Section::where('section_name', $request->section_name)->first()->id;
        $validation = $request->validate([
        'product_name'=>'required',
    ],[
        'product_name.required'=>'يرجي اختيار اسم  للمنتج',
    ]);
        $product = Product::findOrFail($id);
        $product->update([
            'product_name'=>$request->product_name,
            'description'=>$request->description,
            'section_id'=>$section_id
        ]);
        session()->flash('Edit' ,'تم تعديل المنتج');
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id =$request->id;
        $product = Product::findOrFail($id);
        $product->delete();
        session()->flash('Delete' ,'تم حذف المنتج');
        return redirect()->route('products.index');
    }
}
