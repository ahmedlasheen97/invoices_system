<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\sections;

class CusromerReportController extends Controller
{
    public function index()
    {
        $sections = sections::all();
       
        return view('reports.customer_report',compact('sections'));
    }

    /**
     * Search for customers based on the provided request parameters.
     *
     * @param Request $request The request object containing the search parameters.
     *                        The request must have the following parameters:
     *                        - Section: The section ID to filter by.
     *                        - product: The product to filter by.
     *                        - start_at: The start date of the date range to filter by.
     *                        - end_at: The end date of the date range to filter by.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view for the customer report,
     *                                                               with the search results and sections.
     */
    public function Search_customers(Request $request)
    {
        if($request->Section && $request->product && $request->start_at=='' && $request->end_at==''){

            $invoices=invoices::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections=sections::all();
            return view('reports.customer_report',compact('sections'))->withDetails($invoices);
        }else{
           
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
     
           $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            $sections = sections::all();
            return view('reports.customer_report',compact('sections'))->withDetails($invoices);
        }
    }
         
}
