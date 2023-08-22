<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoicesReportsController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:تقرير الفواتير', ['only' => ['index']]);

    }
    public function index(){
        return view('reports.invoices_report');
    }

    public function search_invoices(Request $request){
        $radio = $request->rdio;
        if ($radio == 2){
            $invoice = $request->invoice_number;
            $details = Invoices::where('invoice_number',$invoice)->get();
            return view('reports.invoices_report' , ['details'=>$details]);
        }else{
            if ($request->start_at =="" && $request->end_at == "" && $request->type){

                $status = $request->type ;
                if ($status != "الفواتير"){
                    $details = Invoices::where('status' , $status)->get();
                }else{
                    $details = Invoices::all();
                }

                return view('reports.invoices_report' , ['details'=>$details ,'type'=>$status]);


            }else{
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $status = $request->type ;
                if ($status == "الفواتير" || !$status){
                    $details = Invoices::all()->whereBetween('invoice_date' ,[$start_at , $end_at]);
                }else{
                    $details = Invoices::all()->whereBetween('invoice_date' ,[$start_at , $end_at])->where('status' ,$status);

                }
                return view('reports.invoices_report' , ['details'=>$details ,'type'=>$status ,'start_at'=>$start_at ,'end_at'=>$end_at]);


            }
        }
    }

}
