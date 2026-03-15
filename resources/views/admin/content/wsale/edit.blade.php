 <div class="row">
    <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Customer Info</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group" id="wcustomer">
                                <label for="wcustomerID">Wcustomer Name</label>
                                <select id="wcustomerID"  class="form-control">
                                    <option value="{{$wsale->wcustomer_id}}">{{ $wsale->wcustomerName }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="invoiceID">Invoice Number</label>
                                <input type="text" readonly class="form-control" style="cursor: not-allowed;" id="invoiceID" value="{{ $wsale->invoiceID }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group row mb-2">
                                <label for="fname" class="col-sm-4 text-right control-label col-form-label">Sub Total</label>
                                <div class="col-sm-8">
                                    <span class="form-control" id="subtotal" style="cursor: not-allowed;">{{$wsale->totalAmount-$wsale->paid}}</span>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fname" class="col-sm-4 text-right control-label col-form-label">Transport</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="{{$wsale->deliveryCharge}}" id="deliveryCharge">
                                </div>
                            </div>

                            <div class="form-group row paymentAmount mb-2">
                                <label for="fname" class="col-sm-4 text-right control-label col-form-label">Payment Amount</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{$wsale->paid}}" class="form-control" id="paymentAmount">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="fname" class="col-sm-4 text-right control-label col-form-label">Total</label>
                                <div class="col-sm-8">
                                    <span class="form-control" id="total" style="cursor: not-allowed;">{{$wsale->totalAmount}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="orderDate">Order Date</label>
                                <input type="text" class="form-control datepicker" value="{{$wsale->date}}" id="orderDate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <strong>Product Info</strong>
                </div>
                <div class="card-body">
                    <table id="productTable" style="width: 100% !important;" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Size</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($wsale->products as $product)
                                <tr>
                                <td  style="display: none"><input type="text" class="productID" style="width:80px;" value="{{$product->product_id}}"><input type="text" class="sizeID" style="width:80px;" value="{{$product->size_id}}" readonly></td>
                                <td><input type="text" name="size" id="ProductSize" readonly value="{{$product->size}}" style="    max-width: 40px;" readonly></td>'
                                <td><span class="productCode">{{$product->product_code}}</span></td>
                                <td><span class="productName">{{$product->product_name}}</span></td>
                                <td><input type="number" class="productQuantity form-control" style="width:80px;" value="{{$product->quantity}}" readonly></td>
                                <td><input type="text" name="productPrice" id="productPrice" value="{{$product->product_price}}" style="    max-width: 80px;" readonly></td>
                                <td><button class="btn btn-sm btn-danger checkdelete-btn"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
</div>


