<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Section;
use Illuminate\Http\Request;

class CutomerReportsController extends Controller
{
    public function index(){
        $sections = Section::all();
        return view('reports.customers_report' , ['sections' =>$sections]);
    }
    public function search_customers(Request $request){


            if ($request->start_at =="" && $request->end_at == "" && $request->product && $request->Section){


                    $details = Invoices::where('product' , $request->product)->where('sections_id' , $request->Section)->get();
                $sections = Section::all();

                return view('reports.customers_report' , ['details'=>$details ,'sections' =>$sections ]);


            }else{
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                if (!$request->product && !$request->Section){
                    $details = Invoices::all()->whereBetween('invoice_date' ,[$start_at , $end_at]);
                }else{
                    $details = Invoices::all()->whereBetween('invoice_date' ,[$start_at , $end_at])->where('product' , $request->product)->where('sections_id' , $request->Section);

                }
                $sections = Section::all();

                return view('reports.customers_report' , ['details'=>$details ,'sections' =>$sections ,'start_at'=>$start_at ,'end_at'=>$end_at]);


            }
        }


}
