<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <strong>Customer Info</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group" id="storenamepart">
                            <label for="storeID">Store Name</label><br>
                            <select id="storeID" class="form-control" disabled>
                                <option value="1">{{ env('APP_NAME') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="invoiceID">Invoice Number</label>
                            <input type="text" readonly class="form-control" style="cursor: not-allowed;"
                                id="invoiceID" value="{{ $order->invoiceID }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="customerName">Customer Name</label>
                            <input type="text" class="form-control" id="customerName"
                                value="{{ $order->customerName }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="customerPhone">Customer Phone</label>
                            <input type="text" class="form-control" id="customerPhone"
                                value="{{ $order->customerPhone }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="customerAddress">Customer Address</label>
                            <textarea name="" class="form-control" placeholder="Customer Address" id="customerAddress" rows="2">{{ $order->customerAddress }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mb-1">
                        <div class="form-group" id="courierdatatbl">
                            <label for="courierID">Courier Name</label><br>
                            <select id="courierID" class="form-control">
                                <option value="{{ $order->courier_id }}">{{ $order->courierName }}</option>
                            </select>
                            <?php
                            use App\Models\Courier;
                            $couriers = Courier::all();

                            ?>
                            <script>
                                var couriers = <?php echo json_encode($couriers); ?>;
                            </script>
                        </div>
                    </div>
                    <div class="col-lg-12 hasCity mb-1" style="display:none">
                        <div class="form-group" id="citydatatbl">
                            <label for="cityID">City Name</label><br>
                            <select id="cityID" type="text" class="form-control">
                                <option value="{{ $order->city_id }}">{{ $order->cityName }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 hasZone mb-1" style="display:none">
                        <div class="form-group" id="xonedatatbl">
                            <label for="zoneID">Zone Name</label><br>
                            <select id="zoneID" type="text" class="form-control">
                                <option value="{{ $order->zone_id }}">{{ $order->zoneName }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 hasArea mb-1" style="display:none">
                        <div class="form-group" id="xonedatatbld">
                            <label for="areaID">Area Name</label><br>
                            <select id="areaID" type="text" class="form-control">
                                <option value="{{ $order->area_id }}">{{ $order->areaName }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-1">
                        <div class="form-group">
                            <label for="customerNote">Customer Notes</label>
                            <textarea name="" class="form-control" placeholder="Customer Notes" id="customerNote" rows="2">{{ $order->customerNote }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="customerAddress">Courier Tracking Link</label>
                            <textarea name="courier_tracking_link" class="form-control" placeholder="Courier Tracking Link" id="courier_tracking_link" rows="1">{{ $order->courier_tracking_link }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="consigment_id">Courier ID</label>
                            <input type="text" class="form-control" id="consigment_id"
                                value="{{ $order->consigment_id }}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="orderDate">Order Date</label>
                            <input type="text" class="form-control datepicker" value="{{ $order->orderDate }}"
                                id="orderDate">
                        </div>
                    </div>
                    @if ($order->completeDate)
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="completeDate">Complete Date</label>
                                <input type="text" class="form-control datepicker" id="completeDate"
                                    value="{{ $order->completeDate }}">
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <strong>Product Info</strong>
            </div>
            <div class="card-body">
                <table id="productTable" style="width: 100% !important;"
                    class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->products as $product)
                            <tr>
                                <td style="display: none">
                                    <input type="hidden" id="prd" value="old"><input type="text" class="productID" style="width:80px;" value="{{ $product->product_id }}"></td>
                                <td>
                                    <span class="Color"> <input type="text" name="color" id="ProductColor"  value="{{ $product->color }}" style="    max-width: 60px;"><br><a target="_blank" href="{{url('view-product',App\Models\Product::where('id', $product->product_id)->first()->ProductSlug)}}"><img src="{{asset(App\Models\Product::where('id',$product->product_id)->first()->ProductImage)}}" style="width:60px;margin-top:6px;"></a> </span>
                                </td>
                                <td>
                                    <span class="Size">
                                        <select class="form-control" name="size" id="ProductSize" style="width: 70px;">
                                            <option value="">Choose</option>
                                            @foreach(App\Models\Size::where('product_id',$product->product_id)->get() as $sz)
                                                <option value="{{$sz->size}}" @if($product->size==$sz->size) selected @endif>{{$sz->size}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </td>
                                <td><span class="productCode">{{ $product->productCode }}</span></td>
                                <td>
                                    <span class="productName">{{ $product->productName }}<br>
                                        <select class="form-control" name="sigment" id="sigment" style="width: 250px;">
                                            <option value="">Choose</option>
                                            @foreach(App\Models\Weight::where('product_id',$product->product_id)->get() as $wi)
                                                <option value="{{$wi->weight}}" @if($product->sigment==$wi->weight) selected @endif>{{$wi->weight}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </td>
                                <td><input type="number" class="productQuantity form-control" style="width:80px;" value="{{ $product->quantity }}"></td>
                                <td><input type="text" name="productPrice" class="productPrice" value="{{ $product->productPrice }}" style="max-width: 60px;"></td>
                                <td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <select id="productID" type="text" style="width: 100%;" class="form-control">
                                    <option value="">Select Product</option>
                                </select>
                            </td>
                        </tr>
                    </tfoot>

                </table>
                <br>

                <br>

                <div class="row">
                  
                    
                    <div class="col-md-6">
                        
                        <div class="form-group mb-2" id="paymntidname">
                            <label>Payment</label> <br>
                            <select id="paymentTypeID" class="form-control select2">
                                <option value="{{ $order->payment_type_id }}">{{ $order->paymentTypeName }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group paymentID mb-2" id="paymentIDname">
                            <select id="paymentID" class="form-control mb-2" style="width: 100%;">
                                <option value="{{ $order->payment_id }}">{{ $order->paymentNumber }}</option>
                            </select>
                        </div>
                        <div class="form-group paymentAgentNumber">
                            <input type="text" class="form-control" id="paymentAgentNumber"
                                placeholder="Enter Bkash Agent Number" value="{{ $order->paymentAgentNumber }}">
                        </div>
                        <div class="form-group d-none">
                            <label>Memo Number</label>
                            <input type="text" class="form-control" id="memo"
                                placeholder="Enter Memo Number"
                                @if ($order->memo) value="{{ $order->memo }}"
                            @else @endif>
                        </div>
                        <div class="form-group">
                            <label for="inputState" class="col-form-label">Choose Status</label>
                            <select id="orderStatus" class="form-control"> 
                                <option value="Pending" @if($order->status=='Pending') selected @endif >Pending</option>
                                <option value="Hold" @if($order->status=='Hold') selected @endif >Hold</option>
                                <option value="Ready to Ship" @if($order->status=='Ready to Ship') selected @endif >Ready to Ship</option>
                                <option value="Packaging" @if($order->status=='Packaging') selected @endif >Packaging</option>
                                <option value="Shipped" @if($order->status=='Shipped') selected @endif >Shipped</option>
                                <option value="Cancelled" @if($order->status=='Cancelled') selected @endif >Cancelled</option>
                                <option value="Completed" @if($order->status=='Completed') selected @endif >Completed</option>
                                <option value="Del. Failed" @if($order->status=='Del. Failed') selected @endif >Del. Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        @if (isset($order->coupon_code))
                            <div class="form-group row mb-2">
                                <label for="fname"
                                    class="col-sm-4 text-right control-label col-form-label">Coupon</label>
                                <div class="col-sm-8">
                                    <span class="form-control" id="coupon"
                                        style="cursor: not-allowed;">{{ $order->coupon_code }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mb-2">
                            <label for="fname" class="col-sm-4 text-right control-label col-form-label">Sub
                                Total</label>
                            <div class="col-sm-8">
                                <span class="form-control" id="subtotal"
                                    style="cursor: not-allowed;">{{ $order->subTotal }}</span>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="fname"
                                class="col-sm-4 text-right control-label col-form-label">Delivery</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" value="{{ $order->deliveryCharge }}"
                                    id="deliveryCharge">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="fname"
                                class="col-sm-4 text-right control-label col-form-label">Discount</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ $order->discountCharge }}" class="form-control"
                                    id="discountCharge">
                            </div>
                        </div>

                        <div class="form-group row paymentAmount mb-2">
                            <label for="fname"
                                class="col-sm-4 text-right control-label col-form-label">Payment</label>
                            <div class="col-sm-8">
                                <input type="text" value="{{ $order->paymentAmount }}" class="form-control"
                                    id="paymentAmount">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fname"
                                class="col-sm-4 text-right control-label col-form-label">Total</label>
                            <div class="col-sm-8">
                                <span class="form-control" id="total" style="cursor: not-allowed;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">

                <button type="button" style="width: 100%;padding: 8px;font-size: 22px;" id="btn-update" value="{{ $order->id }}"
                    class="btn btn-block btn-primary"><i class="fa fa-save"></i> Update Order</button>

            </div>


        </div>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <strong>Order Status</strong>
            </div>
            <div class="card-body">
                <label for="status">Add Note</label>
                <div class="input-group">
                    <input type="text" id="comment" class="form-control" placeholder="Add Notes">
                    <div class="input-group-append">
                        <button class="btn btn-success waves-effect waves-light" id="updateComment"
                            type="button">Update Note</button>
                    </div>
                </div>
                <br>
                <table id="orderCommentTable" style="border-top: 1px solid;" data-id="{{ $order->id }}" style="width: 100% !important;"
                    class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Created At</th>
                            <th>Notes</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <strong>Old Order</strong>
            </div>
            <div class="card-body">
                <table id="oldOrderTable" style="width: 100% !important;border-top: 1px solid;" data-id="{{ $order->id }}"
                    class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice ID</th>
                            <th>Customer Info</th>
                            <th>Products</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


            </div>
        </div>


    </div>
</div>
