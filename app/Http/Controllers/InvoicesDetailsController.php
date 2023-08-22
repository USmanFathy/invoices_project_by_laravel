<?php

namespace App\Http\Controllers;

use App\Models\Invoices_attachments;
use App\Models\InvoicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:حذف المرفق', ['only' => ['delete_file']]);

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoicesDetails $invoicesDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicesDetails $invoicesDetails)
    {
        //
    }

    public function view_file($id , $filename){

        $file = Storage::disk('public_upload')->path( $id .'/'.$filename);
        return response()->file($file);
    }
    public function download_file($id , $filename){
        $file = Storage::disk('public_upload')->path( $id .'/'.$filename);
        return response()->download($file);
    }
    public function delete_file(Request $request)
    {
      $invoice = Invoices_attachments::findOrFail($request->id_file);
      $invoice->delete();
      Storage::disk('public_upload')->delete($request->invoice_number .'/'.$request->file_name);
      session()->flash('Delete' , 'تم حذف المرفق بنجاح');
      return back();
    }

}
