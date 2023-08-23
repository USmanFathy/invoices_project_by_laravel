<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_invoices = Invoices::count() ;
                    if(Invoices::where('value_status' , 2)->count()){
                        $unpaid_invoices = round(Invoices::where('value_status' , 2)->count() / $total_invoices *100 );
                    }else{
                        $unpaid_invoices = 0;
                    }
        if(Invoices::where('value_status' , 1)->count()){
            $paid_invoices = round(Invoices::where('value_status' ,1)->count() / $total_invoices *100 );
        }else{
            $paid_invoices = 0;
        }


        if (Invoices::where('value_status' , 3)->count() ){
            $partial_invoices = round(Invoices::where('value_status' , 3)->count() / $total_invoices *100 );
        }else{
            $partial_invoices = 0;
        }

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير غير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا' ])
            ->datasets([
                [
                    "label" => "الفواتير غير المدفوعة",
                    'backgroundColor' => ['#BB2525', 'rgba(54, 162, 235, 0.5)','#FD8D14'],
                    'data' => [$unpaid_invoices]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                    'data' => [$paid_invoices]
                ],
                [
                "label" => "الفواتير المدفوعة جزئيا",
                'backgroundColor' => ['#FD8D14', '#FD8D14'],
                'data' => [$partial_invoices]
            ]
            ])
            ->options([]);
        $chartjs2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير المدفوعة', 'الفواتير غير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['rgb(54, 162, 235)', '#BB2525','#FD8D14'],
                    'hoverBackgroundColor' => ['rgb(54, 162, 235)', '#BB2525','#FD8D14'],
                    'data' => [Invoices::where('value_status',1)->sum('amount_collection'), Invoices::where('value_status',2)->sum('amount_collection'),Invoices::where('value_status',3)->sum('amount_collection')]
                ]
            ])
            ->options([]);


        return view('home' ,['chartjs'=>$chartjs , 'chartjs2'=>$chartjs2 ,'partial'=>$partial_invoices , 'paid' =>$paid_invoices , 'unpaid' =>$unpaid_invoices]);
    }
}
