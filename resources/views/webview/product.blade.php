 @forelse ($categoryproducts as $categoryproduct)
    @php
    $firstcatepro = null;
    $relatedIds = json_decode($categoryproduct->RelatedProductIds);

    if (!empty($relatedIds) && isset($relatedIds[0]->productID)) {
        $firstcatepro = App\Models\Product::with([
            'sizes' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')->take(1);
            }
        ])->where('id', $relatedIds[0]->productID)
            ->select('id', 'ProductName')
            ->first();
    }

    $review = $firstcatepro ? App\Models\Review::where('product_id', $firstcatepro->id)->avg('rating') : 0;
    $reviewCount = $firstcatepro ? App\Models\Review::where('product_id', $firstcatepro->id)->count() : 0;
    @endphp
    @if(isset($firstcatepro))
            <div class="mb-2 col-6 col-md-4 col-lg-2">
                <div class="product">
                        <div class="product-micro">
                            <div class="row product-micro-row">
                                <div class="col-12">
                                    <div class="product-image" style="position: relative;">
                                        <div class="text-center image">
                                            <div class="frs_discount">
                                                <span>
                                                    {{ ($firstcatepro->sizes[0]->RegularPrice > 0) ? round((($firstcatepro->sizes[0]->RegularPrice - $firstcatepro->sizes[0]->SalePrice) / $firstcatepro->sizes[0]->RegularPrice) * 100) : 0 }}%<br>
                                                    <span class="pip_pip_1s">ছাড়</span> </span>
                                            </div>
                                            <a href="{{ url('view-product/' . $categoryproduct->ProductSlug) }}">
                                                <img src="{{ asset($categoryproduct->ProductImage) }}">
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /.product-image -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12">
                                    <div class="p-2 infofe p-md-2" style="border-top:none;background: white;">
                                        <div class="product-info">
                                            <h2 class="name text-truncate" id="f_name"><a
                                                    href="{{ url('view-product/' . $categoryproduct->ProductSlug) }}"
                                                    id="f_pro_name">{{ $firstcatepro->ProductName }}</a>
                                            </h2>
                                        </div>

                                        <div class="d-flex" style="justify-content:space-between">
                                            <div class="star" style="padding-top: 5px;">
                                                <span
                                                    style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $firstcatepro->id)->select('id')->get()->count() }})</span>

                                                <span class="fas fa-star" id="checked"></span>
                                                <span class="fas fa-star" id="checked"></span>
                                                <span class="fas fa-star" id="checked"></span>
                                                <span class="fas fa-star" id="checked"></span>
                                                <span class="fas fa-star" id="checked"></span>

                                            </div>

                                        </div>

                                        <div class="price-box">
                                            <del class="old-product-price strong-400" style="color:red">৳
                                                {{ round($firstcatepro->sizes[0]->RegularPrice) }}</del>
                                            <span class="product-price strong-600" style="color:black">৳
                                                {{ round($firstcatepro->sizes[0]->SalePrice) }}</span>
                                        </div>

                                    </div>

                                    <a href="{{ url('view-product/' . $categoryproduct->ProductSlug) }}">
                                        <button class="mb-0 btn btn-danger btn-sm btn-block"
                                            style="width: 100%;border-radius: 0%;" id="purcheseBtn">অর্ডার
                                            করুন</button>
                                    </a>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.product-micro-row -->
                        </div>

                        <!-- /.product-micro -->

                    </div>
            </div>
    @endif
@empty
@endforelse
