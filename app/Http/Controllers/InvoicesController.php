<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Models\invoice_attachments;
use App\Models\invoices_details;
use Illuminate\Support\Facades\Auth;
use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\sections;
use App\Models\products;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AddInvoice;
use App\Models\users;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoices::all();
       
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections=sections::all();
        $products=products::all();
       return  view('invoices.add_invoice',compact('sections','products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

      
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // $user=User::get();
        // $invoice_id = invoices::latest()->first();
        // Notification::send($user, new \App\Notifications\Add_invoice_new($invoice_id));


        //$notification->notify(new AddInvoice($invoice_id));
        // foreach($user as $notification){
        //     $notification->notify(new AddInvoice($invoice_id));

        // }
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $details = invoices_details::where('id_Invoice', $id)->get();
        return view('invoices.status_update', compact('invoices', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
       
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        $products = products::all();
        return view('invoices.edit_invoice', compact('invoices', 'sections', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([

            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,

        ]);


        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        
        return back();


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $details = invoice_attachments::where('invoice_id', $id)->first();

        if(!empty($details)){
            Storage::disk('public_uploads')->deleteDirectory($details->invoice_number);
    }

        $invoices->delete();
        session()->flash('delete_invoice');
        return back();
    }



    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }
    public function status_update($id, Request $request)
    {
        

        $invoices = invoices::findOrFail($id);

        if($request->Status === 'مدفوعة'){

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date
            ]);

    
           
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => 'مدفوعة',
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
    }else

    {
        $invoices->update([
            'Value_Status' => 3,
            'Status' => $request->Status,
            'Payment_Date' => $request->Payment_Date
        ]);
        invoices_Details::create([
            'id_Invoice' => $request->invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'Status' => 'مدفوعةجزئيا',
            'Value_Status' => 3,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => (Auth::user()->name),
        ]);
    }
    session()->flash('Status_Update');
    return redirect('/invoices');

    }

    public function invoice_Partial()
    {
        $invoices = invoices::where('Value_Status', 3)->get();
        return view('invoices.invoices_Partial', compact('invoices', 'details'));
    }

    public function invoices_paid()
    {
        $invoices = invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoices_unpaid() 
    {

        $invoices = invoices::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    public function print_invoice($id){

        $invoices = invoices::where('id', $id)->first();
        $details = invoices_details::where('id_Invoice', $id)->get();
        return view('invoices.print_invoice', compact('invoices', 'details'));
    }
    public function export() 
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
