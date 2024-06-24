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

        $paidinvoices_count=invoices::where('value_status',1)->count();
        $paidinvoices_sum=invoices::where('value_status',1)->sum('total');

        $unpaidinvoices_count=invoices::where('value_status',2)->count();
        $unpaidinvoices_sum=invoices::where('value_status',2)->sum('total');

        $partialinvoices_count=invoices::where('value_status',3)->count();
        $partialinvoices_sum=invoices::where('value_status',3)->sum('total');

        return view('home',compact('invoices_count','invoices_sum','paidinvoices_count','paidinvoices_sum','unpaidinvoices_count','unpaidinvoices_sum','partialinvoices_count','partialinvoices_sum'));
    }
}
