<style>
    #buttonplus {
        font-size: 25px;
        padding: 3px 14px;
        border-radius: 0px;
        height: 30px;
        margin: 0;
        line-height: 4px;
        color: #000;
        border: none;
    }

    #buttonminus {
        font-size: 40px;
        padding: 3px 14px;
        border-radius: 0px;
        height: 25px;
        margin: 0;
        line-height: 4px;
        color: #000;
        border: none;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }
</style>
<div class="product">

    {{-- PRODUCT INFO --}}
    <div class="mb-3 d-flex" id="productInfo">
        <img id="productImg" src="{{ asset($productdetails->ProductImage) }}"
            style="width:26%;border-radius:4px;margin-right:10px">
        <div>
            <p id="productName" style="font-size:18px">{{ $productdetails->ProductName }}</p>
            <div style="font-weight:bold">
                <span id="salePrice">{{ $sizesolds->first()->SalePrice ?? 0 }}</span> TK
            </div>
        </div>
    </div>

    {{-- COLOR SECTION --}}
    @if(!empty(json_decode($singlemain->RelatedProductIds)))
        <div class="mb-2">
            <b>Color:</b>
            <div class="mt-1 d-flex">
                @foreach(json_decode($singlemain->RelatedProductIds) as $key => $ids)
                    @php
                        $prod = App\Models\Product::where('id', $ids->productID)->first();

                        $firstSize = App\Models\Size::where('product_id', $ids->productID)
                            ->where('status', 'Active')->first();

                        $pcolor = App\Models\Varient::where('product_id', $ids->productID)->first();
                    @endphp

                    @if($prod)
                        <label class="color-option" data-product="{{ $prod->id }}" data-name="{{ $prod->ProductName }}"
                            data-img="{{ asset($prod->ProductImage) }}" data-price="{{ $firstSize->SalePrice ?? 0 }}"
                            data-color="{{ $pcolor->color ?? '' }}" onclick="selectColor(this)"
                            style="border:1px solid #ccc;margin-right:5px;cursor:pointer;padding:2px">

                            <img src="{{ asset($prod->ProductImage) }}" style="width:50px">
                        </label>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- SIZE SECTION --}}
    <div class="mb-2">
        <b>Size:</b>
        @foreach(json_decode($singlemain->RelatedProductIds) as $ids)
            @php
                $sizes = App\Models\Size::where('product_id', $ids->productID)
                    ->where('status', 'Active')->get();
            @endphp
            <div class="sizeblock" id="sizeblock{{ $ids->productID }}" style="display:none;margin-top:5px">
                @foreach($sizes as $size)
                    <label class="sizetext size-product-{{ $ids->productID }}" data-size="{{ $size->size }}"
                        data-product="{{ $ids->productID }}" data-price="{{ $size->SalePrice }}" onclick="selectSize(this)"
                        style="border:1px solid #e4e4e4;padding:4px 10px;margin-right:4px;margin-bottom:4px;cursor:pointer;display:inline-block">
                        {{ $size->size }}
                    </label>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- QUANTITY --}}
    <div class="mb-3 quantity-wrapper d-flex" style="align-items:center;">
        <button type="button" onclick="minus()" class="qty-btn" style="margin-top: 20px">-</button>
        <input type="text" id="qtyval" value="1"
            style="width:60px;text-align:center;margin:0 5px; border:1px solid #041E3A; border-radius:4px;">
        <button type="button" onclick="plus()" class="qty-btn" style="margin-top: 20px">+</button>
    </div>

    {{-- ADD TO CART --}}
    <form action="{{ url('add-to-cart-new') }}" method="POST" class="mb-2">
        @csrf
        <input type="hidden" name="color" id="product_colorordernew">
        <input type="hidden" name="color" id="product_colorordernew2">
        <input type="hidden" name="product_id" id="product_idorder" value="{{ $productdetails->id }}">
        <input type="hidden" name="size" id="product_sizeorder" value="">
        <input type="hidden" name="price" id="product_priceorder" value="">
        <input type="hidden" name="qty" id="qtyoror" value="1">
        <button type="submit" style="width:100%;border:1px solid #041E3A;background:transparent;color:#041E3A">Add To
            Cart</button>
    </form>

    <form action="{{ url('add-to-cart') }}" method="POST">
        @csrf
        <input type="hidden" name="color" id="product_colororder">
        <input type="hidden" name="color" id="product_colororder2">
        <input type="hidden" name="product_id" id="product_idorder2" value="{{ $productdetails->id }}">
        <input type="hidden" name="size" id="product_sizeorder2" value="">
        <input type="hidden" name="price" id="product_priceorder2" value="">
        <input type="hidden" name="qty" id="qtyoror2" value="1">
        <button type="submit" style="width:100%;background:#041E3A;color:white">Order Now</button>
    </form>

</div>

<script>
    $(document).ready(function () {
        var firstColor = $('.color-option').first();
        if (firstColor.length) {
            selectColor(firstColor);
        }
    });

    // Color select
    function selectColor(el) {

        var product_id = $(el).data('product');
        var name = $(el).data('name');
        var img = $(el).data('img');
        var defaultPrice = $(el).data('price');
        var color = $(el).data('color');

        $('#productImg').attr('src', img);
        $('#productName').text(name);
        $('#salePrice').text(defaultPrice);

        $('.color-option').css('border', '1px solid #ccc');
        $(el).css('border', '2px solid #041E3A');

        $('.sizeblock').hide();
        $('#sizeblock' + product_id).show();

        $('#product_idorder, #product_idorder2').val(product_id);

        //  selected color
        $('#product_colororder, #product_colororder2').val(color);
        $('#product_colorordernew, #product_colorordernew2').val(color);

        var firstSize = $('#sizeblock' + product_id + ' .sizetext').first();
        if (firstSize.length) {
            selectSize(firstSize);
        }
    }

    // Size select
    function selectSize(el) {
        var size = $(el).data('size');
        var product = $(el).data('product');
        var price = $(el).data('price');

        // Show price somewhere if needed
        $('#salePrice').html(price);

        // Update hidden inputs for Add to Cart
        $('#product_sizeorder, #product_sizeorder2').val(size);
        $('#product_priceorder, #product_priceorder2').val(price);
        $('#product_idorder, #product_idorder2').val(product);

        // Reset other sizes
        $('.size-product-' + product).css({ 'background': '#fff', 'color': '#000' });

        // Active size
        $(el).css({ 'background': '#041E3A', 'color': '#fff' });
    }

    // Quantity functions
    function minus() {
        var qty = parseInt($('#qtyval').val());
        if (qty > 1) qty--;
        $('#qtyval').val(qty);
        $('#qtyoror, #qtyoror2').val(qty);
    }
    function plus() {
        var qty = parseInt($('#qtyval').val());
        qty++;
        $('#qtyval').val(qty);
        $('#qtyoror, #qtyoror2').val(qty);
    }
</script>

<style>
    .qty-btn {
        background-color: #041E3A;
        color: #fff;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
