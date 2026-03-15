<div class="row">
        @php
            $total=0;
            $avtotal=0;
            @endphp
            @foreach(App\Models\Category::all() as $category)
                @php
                    $productid=App\Models\Product::where('category_id',$category->id)->get()->pluck('id');
                    $totalstock=App\Models\Size::whereIn('product_id',$productid)->get()->sum('total_stock');
                    $stock=App\Models\Size::whereIn('product_id',$productid)->get()->sum('available_stock');

                    $total+=$totalstock;
                    $avtotal+=$stock;
                @endphp
                <div class="mb-2 col-sm-6 col-xl-3">
                    <div class="p-4 rounded bg-secondary d-flex align-items-center justify-content-between">
                        <div class="ms-3">
                            <p class="mb-2">{{$category->category_name}}</p>
                            <h6 class="mb-0" id="">{{$stock}}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mb-2 col-sm-6 col-xl-3">
                <div class="p-4 rounded bg-secondary d-flex align-items-center justify-content-between">
                    <div class="ms-3">
                        <p class="mb-2">Total</p>
                        <h6 class="mb-0" id="">{{$avtotal}}</h6>
                    </div>
                </div>
            </div>

    </div>