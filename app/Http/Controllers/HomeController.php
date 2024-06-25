<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\sections;

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
        $invoices_count=invoices::count();
        $invoices_sum=invoices::sum('total');

        $paidinvoices_sum=invoices::where('value_status',1)->sum('total');
        $paidinvoices_count=invoices::where('value_status',1)->count();
        $paidinvoices_rate=($paidinvoices_count/$invoices_count)*100;

        $unpaidinvoices_sum=invoices::where('value_status',2)->sum('total');
        $unpaidinvoices_count=invoices::where('value_status',2)->count();
        $unpaidinvoices_rate=($unpaidinvoices_count/$invoices_count)*100;
       
        $partialinvoices_sum=invoices::where('value_status',3)->sum('total');
        $partialinvoices_count=invoices::where('value_status',3)->count();
        $partialinvoices_rate=($partialinvoices_count/$invoices_count)*100;

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$unpaidinvoices_rate]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$paidinvoices_rate]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$partialinvoices_rate]
                ],


            ])
            ->options([]);


        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [$unpaidinvoices_rate,$paidinvoices_rate,$partialinvoices_rate] 
                ]
            ])
            ->options([]);


        return view('home',compact('chartjs','chartjs_2','invoices_count','invoices_sum','paidinvoices_count','paidinvoices_sum','unpaidinvoices_count','unpaidinvoices_sum','partialinvoices_count','partialinvoices_sum'));
    }
}
