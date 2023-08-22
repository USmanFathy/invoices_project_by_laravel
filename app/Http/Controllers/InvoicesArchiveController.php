<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesArchiveController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:ارشيف الفواتير', ['only' => ['index']]);


    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoices::onlyTrashed()->get();
        return view('invoices.invoices_archive', ['invoices' => $invoices]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;

        $invoice = Invoices::withTrashed()->where('id',$id)->restore();
        session()->flash('invoice','تم أضافة الفاتورة إلي  الفواتير');

            return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;

            $invoice = Invoices::withTrashed()->where('id',$id)->first();
            Storage::disk('public_upload')->deleteDirectory($invoice->invoice_number);
            session()->flash('delete','تم حذف الفاتورة');
            $invoice->forcedelete();


        return back();
    }
}
