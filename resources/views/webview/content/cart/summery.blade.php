<div class="container">
    <div class="row cols-xs-space cols-sm-space cols-md-space">
        <div class="col-md-8" id="smp">
            <div class="px-1 py-3 bg-white form-default" style="padding-top: 10px;margin-bottom: 20px;">
                <div class="">
                    <div class="">
                        <table class="table-cart border-bottom">
                            <thead>
                                <tr>
                                    <th class="product-image d-lg-block ps-2" style="padding-top: 10px;">Product
                                    </th>
                                    <th class="product-name" style="text-align: center;    padding-top: 10px;">Product
                                        Name</th>
                                    <th class="d-none d-lg-table-cell" style="padding-top: 10px;">Price
                                    </th>
                                    <th class="product-quanity d-md-table-cell" style="padding-top: 10px;">Quantity</th>
                                    <th class="product-total" style="padding-top: 10px;">Total</th>
                                    <th class="product-remove" style="padding-top: 10px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cartProducts as $cartProduct)
                                    <tr class="cart-item">
                                        <td class="p-0 product-image d-lg-block">
                                            <a href="#" class="" style="text-align: center;">
                                                <img style="width: 50px;" loading="lazy"
                                                    src="{{ asset($cartProduct->image) }}">
                                            </a>
                                        </td>

                                        <td class="p-0 product-name" id="cartpron">
                                            <span class="pr-2 d-block" id="cartproname">{{ $cartProduct->name }}</span>
                                        </td>

                                        <td class="p-0 product-price d-none d-lg-table-cell">
                                            <span class="pr-3"
                                                id="qtyPro{{ $cartProduct->rowId }}">{{ $cartProduct->qty }}</span>
                                            <span class="pr-3">* ৳{{ $cartProduct->price }}</span>
                                        </td>
                                        <input type="text" name="priceOf" id="priceOf{{ $cartProduct->rowId }}"
                                            value="{{ $cartProduct->price }}" hidden>

                                        <td class="p-0 product-quantity d-md-table-cell">
                                            <div class="product-quantity d-flex align-items-center">
                                                <div class="pr-3 input-group input-group--style-2" id="quantityup">

                                                    <input type="number" name="quantity"
                                                        class="m-0 text-center form-control input-number"
                                                        id="proQuantity{{ $cartProduct->rowId }}" placeholder="1"
                                                        value="{{ $cartProduct->qty }}" min="1" max="10"
                                                        style="padding: 0"
                                                        onchange="updateQuantity('{{ $cartProduct->rowId }}', this)">
                                                </div>

                                            </div>
                                        </td>
                                        <td class="product-total">
                                            <span>৳<span
                                                    id="pricePro{{ $cartProduct->rowId }}">{{ $cartProduct->qty * $cartProduct->price }}</span></span>
                                        </td>

                                        <td class="text-center product-remove">
                                            <a type="button" style="cursor: pointer"
                                                onclick="removeFromCartItemHead('{{ $cartProduct->rowId }}')"
                                                class="pl-3 text-right">
                                                <i class="fa fa-trash" color="red"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="pt-4 row align-items-center">
                    <div class="col-6 col-md-6 col-sm-6" style="margin-top: 15px;">
                        <a href="{{ url('/') }}" class="link link--style-3" style="color:#212129;margin: 12px;">
                            <i class="la la-mail-reply"></i>
                            Return to shop
                        </a>
                    </div>
                    <div class="text-right col-6 col-md-6 col-sm-6" style="   padding-right: 26px;">
                        <a @if (count($cartProducts) > 0) @else disabled @endif href="{{ url('/checkout') }}"
                            class="btn" style="margin-top: 10px;margin-bottom: 10px;background:#212129;color:#fff ">Next
                            Step</a>
                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div>

        <div class="col-md-4 ml-lg-auto" id="smp">
            <div class="card sticky-top">
                <div class="py-3 card-title">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="mb-0 heading heading-3 strong-400">
                                <span style="padding: 10px;font-size: 16px;font-weight: bold;">Summary</span>
                            </h3>
                        </div>

                        {{-- <div class="text-right col-6">
                            <span class="badge badge-md badge-success" style="padding: 6px;    padding-right: 10px;">1
                                Items</span>
                        </div> --}}
                    </div>
                </div>

                <div class="card-body">


                    <table class="table-cart table-cart-review">
                        <thead>
                            <tr>
                                <th class="product-name" style="padding: 6px;">Product</th>
                                <th class="text-right product-total" style="padding: 6px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cartProducts as $cartProduct)
                                <tr class="cart_item">
                                    <td class="product-name" style="padding-left: 6px;font-size: 13px !important;">
                                        {{ $cartProduct->name }}
                                        <strong class="product-quantity">× {{ $cartProduct->qty }}</strong>
                                    </td>
                                    <td class="text-right product-total" style="padding-right: 6px;">
                                        <span class="pl-4"
                                            style="font-size: 13px !important;">৳{{ $cartProduct->qty * $cartProduct->price }}</span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>

                    <table class="table-cart table-cart-review">

                        <tfoot>
                            <tr class="cart-subtotal">
                                <th style="font-weight: normal;padding-bottom: 8px;">Subtotal</th>
                                <td class="text-right">
                                    <span class="strong-600"
                                        style="font-weight: normal;">৳{{ Cart::subtotal() }}</span>
                                </td>
                            </tr>

                            <tr class="cart-shipping">
                                <th style="font-weight: normal;padding-bottom: 8px;">Tax</th>
                                <td class="text-right">
                                    <span class="text-italic" style="font-weight: normal;">৳0</span>
                                </td>
                            </tr>

                            <tr class="cart-shipping">
                                <th style="font-weight: normal;padding-bottom: 8px;">Total Shipping</th>
                                <td class="text-right">
                                    <span class="text-italic shiop" style="font-weight: normal;">৳0</span>
                                </td>
                            </tr>



                            <tr class="cart-total">
                                <th><span class="strong-600">Total</span></th>
                                <td class="text-right">
                                    <strong>৳ <span class="g_total">{{ Cart::subtotal() }}</span></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
