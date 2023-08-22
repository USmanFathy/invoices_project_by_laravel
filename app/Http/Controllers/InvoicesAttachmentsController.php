<?php

namespace App\Http\Controllers;

use App\Models\Invoices_attachments;
use Illuminate\Http\Request;

class InvoicesAttachmentsController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:اضافة مرفق', ['only' => ['store']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $this->validate($request, [

                'file_name' => 'mimes:pdf,jpeg,png,jpg',

            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);
        if ($request->has('file_name')){
            $file_name= $request->file('file_name')->getClientOriginalName();
            $request->file('file_name')->storeAs('invoices_recipts/'.$request->invoice_number,$file_name ,'public');

            Invoices_attachments::create([
                'file_name'=> $file_name,
                'invoice_number'=>$request->invoice_number,
                'user'=>auth()->user()->name,
                'id_invoices'=>$request->invoice_id,

            ]);
            session()->flash('Add','تم إضافة المرفق بنجاح');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices_attachments $invoices_attachments)
    {
        //
    }
}
