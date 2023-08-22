<?php

namespace App\Http\Controllers;

use App\Exports\InvoicessExport;
use App\Models\Invoices;
use App\Models\Invoices_attachments;
use App\Models\InvoicesDetails;
use App\Models\Section;
use App\Notifications\InvoiceAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller

{
    function __construct()
    {

        $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['status_update','status_show']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['print_invoice']]);
        $this->middleware('permission:الفواتير المدفوعة', ['only' => ['invoices_paid']]);
        $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['invoices_partial']]);
        $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['invoices_unpaid']]);
        $this->middleware('permission:تصدير EXCEL', ['only' => ['export']]);



    }
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoices::all();

        return view('invoices.invoices',['invoices'=>$invoices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $sections = Section::all();
        return view('invoices.add_invoice' , ['sections' => $sections]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoices::create([
            'invoice_number'=>$request->invoice_number,
            'invoice_date'=>$request->invoice_Date,
            'due_date'=>$request->Due_date,
            'sections_id'=>$request->Section,
            'discount'=>$request->Discount,
            'rate_vat'=>$request->Rate_VAT,
            'value_vat'=>$request->Value_VAT,
            'product'=>$request->product,
            'amount_collection'=>$request->Amount_collection,
            'amount_comission'=>$request->Amount_Commission,
             'total'=>$request->Total,
             'status'=>'غير مدفوعة',
            'value_status'=>2,
             'note'=> $request->note,
             'user'=>auth()->user()->name,
        ]);
        $invoice = Invoices::latest()->first()->id;
        InvoicesDetails::create([
            'id_invoices'=>$invoice,
            'invoices_number'=>$request->invoice_number,
            'product'=>$request->product,
            'section'=>$request->Section,
            'status'=>'غير مدفوعة',
            'value_status'=>2,
            'note'=> $request->note,
            'user'=>auth()->user()->name,
        ]);
        $this->validate($request, [

            'pic' => 'mimes:pdf,jpeg,png,jpg',

        ], [
            'pic.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
        ]);
        if ($request->has('pic')){
            $file_name= $request->file('pic')->getClientOriginalName();
            $request->file('pic')->storeAs('invoices_recipts/'.$request->invoice_number,$file_name ,'public');

            Invoices_attachments::create([
                'file_name'=> $file_name,
                'invoice_number'=>$request->invoice_number,
                'user'=>auth()->user()->name,
                'id_invoices'=>$invoice,

            ]);
        }

        session()->flash('Add' ,'تم إضافة الفاتورة بنجاح');
        Notification::send(auth()->user(), new InvoiceAdd($invoice));

        return redirect()->route('invoices_all.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
      $invoice = Invoices::where('id' , $id)->first() ;
      $invoice_details = InvoicesDetails::where('id_invoices' , $id)->get();
      $invoice_attachment = Invoices_attachments::where('id_invoices' , $id)->get();
      return view('invoices.invoice_detail', ['invoice'=>$invoice, 'invoice_details'=>$invoice_details, 'invoice_attachment'=>$invoice_attachment]
      );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice= Invoices::findOrFail($id);
        $sections = Section::all();
        return view('invoices.edit_invoice', ['invoice'=>$invoice ,'sections'=>$sections]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , $id)
    {
        $invoice= Invoices::findOrFail($id);
        $invoice->update([
            'invoice_number'=>$request->invoice_number,
            'invoice_date'=>$request->invoice_Date,
            'due_date'=>$request->Due_date,
            'sections_id'=>$request->Section,
            'discount'=>$request->Discount,
            'rate_vat'=>$request->Rate_VAT,
            'value_vat'=>$request->Value_VAT,
            'product'=>$request->product,
            'amount_collection'=>$request->Amount_collection,
            'amount_comission'=>$request->Amount_Commission,
            'total'=>$request->Total,
            'note'=> $request->note,
        ]);
        InvoicesDetails::where('id_invoices', $request->invoice_id)->update([
            'invoices_number'=>$request->invoice_number,
            'product'=>$request->product,
            'section'=>$request->Section,

            'note'=> $request->note,

        ]);


        session()->flash('Add' ,'تم تعديل الفاتورة بنجاح');

        return redirect()->route('invoices_all.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id_page = $request->id_page;
        $id = $request->invoice_id;

        if(!$id_page){
            $invoice = Invoices::where('id' ,$id)->first();
            Storage::disk('public_upload')->deleteDirectory($invoice->invoice_number);
            session()->flash('delete','تم حذف الفاتورة');
            $invoice->forcedelete();

        }else{

            $invoice = Invoices::where('id' ,$id)->first();
            $invoice->delete();
            session()->flash('archive','تم أضافة الفاتورة إلي الأرشيف');
        }
        return back();
    }
    public function get_product($id){
        $product = DB::table('products')->where('section_id' , $id)-> pluck('product_name', 'id');
        return json_decode($product);
    }


    public function  status_show($id){
        $invoice = Invoices::where('id' ,$id)->first();
        return view('invoices.status_update' , ['invoice'=>$invoice]);
    }
    public function  status_update(Request $request){
        $invoice= Invoices::findOrFail($request->invoice_id);
        if($request->Status == 'مدفوعة'){
            $invoice->update([
                'status'=>$request->Status,
                'value_status'=>1,
                'payment_date'=>$request->Payment_Date
            ]);
            InvoicesDetails::create([
                'id_invoices'=>$request->invoice_id,
                'invoices_number'=>$request->invoice_number,
                'product'=>$request->product,
                'section'=>$request->Section,
                'status'=>$request->Status,
                'value_status'=>1,
                'payment_date'=>$request->Payment_Date,
                'note'=> $request->note,
                'user'=>auth()->user()->name,
            ]);

        }elseif ($request->Status =='مدفوعة جزئيا'){
            $invoice->update([
                'status'=>$request->Status,
                'value_status'=>3,
                'payment_date'=>$request->Payment_Date
            ]);
            InvoicesDetails::create([
                'id_invoices'=>$request->invoice_id,
                'invoices_number'=>$request->invoice_number,
                'product'=>$request->product,
                'section'=>$request->Section,
                'status'=>$request->Status,
                'value_status'=>3,
                'payment_date'=>$request->Payment_Date,
                'note'=> $request->note,
                'user'=>auth()->user()->name,
            ]);


        }

        return redirect()->route('invoices_all.index');


    }
    public function invoices_paid(){
        $invoices = Invoices::where('value_status',1)->get();
        return view('invoices.invoices_paid' ,['invoices' => $invoices]);
    }
    public function invoices_unpaid(){
        $invoices = Invoices::where('value_status',2)->get();
        return view('invoices.invoices_unpaid' ,['invoices' => $invoices]);
    }
    public function invoices_partial(){
        $invoices = Invoices::where('value_status',3)->get();

        return view('invoices.invoices_partial',['invoices' => $invoices]);
    }

    public function print_invoice($id){
        $invoice= Invoices::findOrFail($id);

        return view('invoices.print_invoice' ,['invoice'=>$invoice]);
    }

    public function export()
    {
        return Excel::download(new InvoicessExport(), 'invoices.xlsx');
    }
}
