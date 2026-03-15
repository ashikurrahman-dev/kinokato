<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Models\Purcheseproduct;
use App\Models\SupplierPayment;
use App\Models\Basicinfo;
use App\Models\Costhistory;
use DataTables;
use Illuminate\Support\Facades\Auth;
use DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('status', 'Active')->get();
        $suppliers = Supplier::where('id',1)->where('status', 'Active')->get();
        return view('admin.content.purchase.purchase', ['products' => $products, 'suppliers' => $suppliers]);
    }


    public function purchasedata(Request $request)
    {
        $purchases = Purchase::with(['purcheseproducts', 'suppliers'])->where('supplier_id',1)->get();
        if ($request['startDate'] != '' && $request['endDate'] != '') {
            $purchases = $purchases->whereBetween('date', [$request['startDate'], $request['endDate']]);
        }
        
        return Datatables::of($purchases)
            ->addColumn('invoice', function ($purchases) {
                return $purchases->date . '<br>' . $purchases->invoiceID;
            })
            ->addColumn('supplier', function ($purchases) {
                return $purchases->suppliers->supplierName . '<br>' . $purchases->suppliers->supplierAddress . '<br>' . $purchases->suppliers->supplierPhone;
            })
            ->addColumn('quantityall', function ($purchases) {
                return $purchases->purcheseproducts->sum('quantity');
            })
            ->editColumn('products', function ($purchases) {
                $orderProducts = '';
                foreach ($purchases->purcheseproducts as $product) {
                    $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->product_name . '<br><span style="color:black;"> Size: ' . $product->size . ', Quantity: ' . $product->quantity . ', Price: ' . $product->product_price . '</span><br>';
                }
                return rtrim($orderProducts, '<br>');
            })
            ->addColumn('action', function ($purchases) {
                return '<a href="purchases/'. $purchases->id .'/edit" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square" ></i></a>';
            })
            ->escapeColumns([])->make(true);
    }

    public function create()
    {
        $uniqueId = $this->uniqueID();
        $suppliers = Supplier::where('status', 'Active')->where('id',1)->get();
        return view('admin.content.purchase.create', ['uniqueId' => $uniqueId, 'suppliers' => $suppliers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purcheseproducts = $request['data']['products'];
        $purchase = new Purchase();
        $purchase->invoiceID = $this->uniqueID();
        $purchase->deliveryCharge = $request['data']['deliveryCharge'];
        $purchase->date = $request['data']['orderDate'];
        $purchase->supplier_id = $request['data']['supplier_id'];
        $purchase->paid = $request['data']['paid'];
        $purchase->due = $request['data']['due'];
        $purchase->totalAmount = $request['data']['paid'] + $request['data']['due'];
        $purchase->admin_id = Auth::guard('admin')->user()->id;
        $result = $purchase->save();


        if ($result) {
            foreach ($purcheseproducts as $product) {
                $orderProducts = new Purcheseproduct();
                $orderProducts->purchese_id = $purchase->id;
                $orderProducts->product_id = $product['productID'];
                $orderProducts->product_code = $product['productCode'];
                $orderProducts->size_id = $product['sizeID'];
                $orderProducts->size = $product['productSize'];
                $orderProducts->product_name = $product['productName'];
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->product_price = $product['productPrice'];
                $orderProducts->total = $product['productPrice'] * $product['productQuantity'];
                $success = $orderProducts->save();
                if ($success) {
                    $size = Size::where('id', $product['sizeID'])->first();

                    $stock = new Stock();
                    $stock->purchese_product_id = $purchase->id;
                    $stock->product_id = $product['productID'];
                    $stock->product_name = $product['productName'];
                    $stock->size_id = $product['sizeID'];
                    $stock->size = $product['productSize'];
                    $stock->purchase = $product['productQuantity'];
                    $stock->stock = $size->available_stock + $product['productQuantity'];
                    $stock->initial_stock = $size->available_stock;
                    $stock->total_stock = $size->total_stock + $product['productQuantity'];
                    $stock->save();

                    $size->total_stock += $product['productQuantity'];
                    $size->available_stock += $product['productQuantity'];
                    $size->update();
                }
            }
        }



        if ($request['data']['paid'] != '' && $request['data']['paymentTypeID'] != '') {
            $supplierPayment = new SupplierPayment();
            $supplierPayment->supplier_id = $request['data']['supplier_id'];
            $supplierPayment->purchese_id = $purchase->id;;
            $supplierPayment->date = $request['data']['orderDate'];
            $supplierPayment->amount = $request['data']['paid'];
            $supplierPayment->trx_id = $request['data']['trx_id'];
            $supplierPayment->payment_type_id = $request['data']['paymentTypeID'];
            $supplierPayment->payment_id = $request['data']['paymentID'];
            $supplierPayment->admin_id = Auth::guard('admin')->user()->id;
            $supplierPayment->comments = $request['data']['comments'];
            $supplierPaymentUpdate = $supplierPayment->save();
            if ($supplierPaymentUpdate) {
                $account = Basicinfo::first();
                $account->account_balance -= $request['data']['paid'];
                $account->update();
                $cost = new Costhistory();
                $cost->paymesnt_for = 'Supplier Payment';
                $cost->date = date('Y-m-d');
                $cost->amount = $request['data']['paid'];
                $cost->admin_id = Auth::guard('admin')->user()->id;
                $cost->comments = 'Supplier payment for  INV: ' . $purchase->invoiceID . 'Paid: ' . $purchase->paid . 'Due: ' . $purchase->due;
                $cost->save();
            }
        }

        $supplier = Supplier::find($request['data']['supplier_id']);
        $supplier->supplierPaidAmount += $request['data']['paid'];
        $supplier->supplierDueAmount += $request['data']['due'];
        $supplier->supplierTotalAmount += $request['data']['paid'] + $request['data']['due'];
        $supplier->update();
        $response['status'] = 'success';
        $response['message'] = 'Successfully Complete Purchese';
        return json_encode($response);
        die();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $purchases = DB::table('purchases')
            ->select('purchases.*', 'suppliers.supplierName', 'suppliers.supplierPhone', 'suppliers.supplierAddress', 'admins.name')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->leftJoin('admins', 'purchases.admin_id', '=', 'admins.id')
            ->where('purchases.id', '=', $id)->get()->first();
        $products = DB::table('purcheseproducts')->where('purchese_id', '=', $id)->get();
        $purchases->products = $products;
        $purchases->id = $id;
        return view('admin.content.purchase.edit')->with('purchase', $purchases);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $purcheseproducts = $request['data']['products'];
        $purchase = Purchase::findOrfail($id); 
        $purchase->deliveryCharge = $request['data']['deliveryCharge']; 
        $purchase->paid = $request['data']['paid'];
        if($purchase->due==$request['data']['due']){
            
        }else{
            if($purchase->due>$request['data']['due']){ 
                $supplier = Supplier::find($request['data']['supplier_id']); 
                $supplier->supplierDueAmount -= $purchase->due-$request['data']['due'];
                $supplier->supplierTotalAmount -= $purchase->due-$request['data']['due'];
                $supplier->update();
                
                $purchase->due = $request['data']['due'];
            }else{
                $supplier = Supplier::find($request['data']['supplier_id']); 
                $supplier->supplierDueAmount += $request['data']['due']-$purchase->due;
                $supplier->supplierTotalAmount += $request['data']['due']-$purchase->due;
                $supplier->update();
                $purchase->due = $request['data']['due'];
            }
        }
         
        $purchase->totalAmount = $request['data']['paid'] + $request['data']['due'];
        $purchase->admin_id = Auth::guard('admin')->user()->id;
        $result = $purchase->save();


        if ($result) {
            foreach ($purcheseproducts as $product) {
                $orderProducts = Purcheseproduct::where('id',$product['ppid'])->first();   
                
                if($orderProducts->quantity == $product['productQuantity']){
                    
                }else{
                    if($orderProducts->quantity > $product['productQuantity']){
                        $size = Size::where('id', $product['sizeID'])->first(); 
                        $size->total_stock -= $orderProducts->quantity-$product['productQuantity'];
                        $size->available_stock -= $orderProducts->quantity-$product['productQuantity'];
                        $size->update();
                    }else{
                        $size = Size::where('id', $product['sizeID'])->first(); 
                        $size->total_stock += $product['productQuantity']-$orderProducts->quantity;
                        $size->available_stock += $product['productQuantity']-$orderProducts->quantity;
                        $size->update();
                    }
                }
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->product_price = $product['productPrice'];
                $orderProducts->total = $product['productPrice'] * $product['productQuantity'];
                $success = $orderProducts->save(); 
            }
        }

 
        $response['status'] = 'success';
        $response['message'] = 'Successfully Complete Purchese';
        return json_encode($response);
        die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::where('id', $id)->first();
        $purchase->delete();
        return response()->json('delete success');
    }

    public function suppliers(Request $request)
    {
        if (isset($request['q'])) {
            $suppliers = Supplier::query()->where([
                ['supplierName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->where('id',1)->get();
        } else {
            $suppliers = Supplier::query()->where('status', 'like', 'Active')->where('id',1)->get();
        }
        $supplier = array();
        foreach ($suppliers as $item) {
            $supplier[] = array(
                "id" => $item['id'],
                "text" => $item['supplierName']
            );
        }
        return json_encode($supplier);
    }

    public function uniqueID()
    {
        $lastPurchase = Purchase::latest('id')->first();
        if ($lastPurchase) {
            $PurchaseID = $lastPurchase->id + 1;
        } else {
            $PurchaseID = 1;
        }

        return 'PINV' . $PurchaseID;
    }
}
