<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\invoices_details;

class InvoicesReportController extends Controller
{
    /**
     * Display the invoices report view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Return the invoices report view.
        
        return view('reports.invoices_report');
    }

    // public function Search_invoices(Request $request)
    // {
    //     $rdio=$request->rdio;
    //     if( $rdio==1){
                
    //         if($request->type && $request->start_at =='' && $request->end_at ==''){
               
    //             // $invoices=invoices::select('*')->where('Status','=',$request->type)->get();
    //             // $type=$request->type;
    //             // return view('reports.invoices_report',compact('type'))->withDetails($invoices);
    //             $invoices = invoices::select('*')->where('Status','=',$request->type)->get();
    //             $type = $request->type;
    //             return view('reports.invoices_report',compact('type'))->withDetails($invoices);
               

    //         }else{
    //             $start_at = date($request->start_at);
    //             $end_at = date($request->end_at);
    //             $type = $request->type;
    //             $invoices=invoices::select('*')->whereBetween('invoice_Date',[$start_at,$start_at])->where('Status','=',$request->type)->get();
    //             return view('reports.invoices_report',compact('type'))->withdetails($invoices);
    //         }


    //     }else{

    //         //$invoices=invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
    //         //return view('reports.invoices_report')->withdetails($invoices);
    //         $details=invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
    //         return view('reports.invoices_report',compact('details'));

    //     }
    // }
    public function Search_invoices(Request $request){

        $rdio = $request->rdio;
    
    
     // في حالة البحث بنوع الفاتورة
        
        if ($rdio == 1) {
           
           
     // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at =='' && $request->end_at =='') {
                
               $invoices = invoices::select('*')->where('Status','=',$request->type)->get();
               $type = $request->type;
                dd($invoices);
               return view('reports.invoices_report',compact('type'))->withDetails($invoices);
            }
            
            // في حالة تحديد تاريخ استحقاق
            else {
               
              $start_at = date($request->start_at);
              $end_at = date($request->end_at);
              $type = $request->type;
              
              $invoices = invoices::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
              return view('reports.invoices_report',compact('type','start_at','end_at'))->withDetails($invoices);
              
            }
    
     
            
        } 
        
    //====================================================================
        
    // في البحث برقم الفاتورة
        else {
            
            $invoices = invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoices_report')->withDetails($invoices);
            
        }
    
        
         
        }
        
}
