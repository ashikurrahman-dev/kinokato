<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Exports\OrderdataExport;
use App\Models\Courier;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Orderproduct;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function fileExport(Request $request)
    {
        $curierid =$request->cour_Id;
        $couriername = Courier::where('id', $curierid)->select('id','courierName')->first();
        $ordersentry= Order::where('status', 'Ready to Ship')->get();
         
        foreach($ordersentry as $entry){
            $completeentry= Order::where('id', $entry->id)->first();
            $completeentry->entry_complete = $couriername->courierName;
            $completeentry->save();
        }

        if($curierid){
            if($curierid==28){
                return Excel::download(new OrderExport($curierid), date('Y-m-d').'pathao.csv');
            }else{
                return Excel::download(new OrderExport($curierid), date('Y-m-d').'order.xlsx');
            }
        }else{
            return redirect()->back()->with('error','Please Select Any Courier');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getdata()
    {
        return view('admin.content.report.download');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function downloadexcle(Request $request)
    {
        $startdate =$request->startDate;
        $enddate =$request->endDate;
        $status =$request->orderStatus; 
       if($status=='All'){
            $ids= Order::whereBetween('deliveryDate', [$startdate, $enddate])->pluck('id');
        }else{
            $ids= Order::whereBetween('deliveryDate', [$startdate, $enddate])->where('status',$status)->pluck('id');
        }
        
 
        $orderproducts= Orderproduct::with(['orders', 'orders.customers','orders.admins'])->whereIn('order_id',$ids)->get()->groupBy('order_id');
        $data=array();
        $i=1; 
        foreach($orderproducts as $key=>$product){
            
            for($j=0;$j<count($product);$j++){ 
                $js=$i;
                if($j==0){
                    $data[] = array( 
                       'id' => $i, 
                       'invoiceID' => $product[$j]->orders->invoiceID,
                       'customerPhone' => $product[$j]->orders->customers->customerPhone,
                       'consigment_id' => $product[$j]->orders->consigment_id, 
                       '',
                       '',
                       'subTotal' => $product[$j]->orders->subTotal, 
                       'quantity' => $product[$j]->quantity,  
                       'category' => Category::where('id',Product::where('id',$product[$j]->product_id)->first()->category_id)->first()->category_name, 
                       'productCode' => $product[$j]->productCode,
                       'sigment' => $product[$j]->sigment,
                       'size' => $product[$j]->size,   
                       'name' => $product[$j]->orders->admins->name,   
                       'status' => $product[$j]->orders->status, 
                       'web_id' => $product[$j]->orders->web_id,
                       'customerNote' => $product[$j]->orders->customerNote,
                    );
                    
                }else{
                     $data[] = array( 
                       'id' => $js, 
                       '',
                       '',
                       '', 
                       '',
                       '',
                       '', 
                       'quantity' => $product[$j]->quantity,  
                       'category' => Category::where('id',Product::where('id',$product[$j]->product_id)->first()->category_id)->first()->category_name, 
                       'productCode' => $product[$j]->productCode,
                       'sigment' => $product[$j]->sigment,
                       'size' => $product[$j]->size,   
                       '',   
                       '', 
                       '',
                       '',
                    );
                     
                }
                   
                 
            }
            $i++;  
        }  
        $collectionA = collect($data);
        return Excel::download(new OrderdataExport($collectionA), 'orderdata.xlsx');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
