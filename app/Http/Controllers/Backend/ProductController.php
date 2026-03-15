<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attrvalue;
use App\Models\Attribute;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Purchase;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\App;
use App\Models\Varient;
use App\Models\Size;
use App\Models\Weight;
use App\Models\Purcheseproduct;
use App\Models\Orderproduct;
use App\Models\Mainproduct;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class ProductController extends Controller
{
    public function stockby()
    {
        return view('backend.content.product.stockby');
    }
    public function stockbydata()
    {

        $products = Product::all();

        foreach ($products as $item) {

            if (App::environment('local')) {
                $item->ProductImage = url($item->ProductImage);
            } else {
                $item->ProductImage = url($item->ProductImage);
            }
            $product[] = array(
                "id" => $item->id,
                "text" => $item->ProductName,
                "total" => Size::where('product_id', $item->id)->get()->sum('total_stock'),
                "sold" => Size::where('product_id', $item->id)->get()->sum('sold'),
                "available" => Size::where('product_id', $item->id)->get()->sum('available_stock'),
                "image" => $item->ProductImage,
                "productCode" => $item->ProductSku
            );
        }

        return Datatables::of($product)
            ->addColumn('serial', function ($product) {
                if ($product['available'] <= 9) {
                    return '<span style="color:red;font-weight:bold">ID: ' . $product['id'] . '<br> SKU: ' . $product['productCode'] . '</span';
                } else {
                    return 'ID: ' . $product['id'] . '<br> SKU: ' . $product['productCode'];
                }
            })
            ->editColumn('image', function ($product) {
                return '<img src="../../' . Product::where('id', $product['id'])->first()->ProductImage . '" style="width:100px">';
            })
            ->editColumn('products', function ($product) {
                return $product['text'];
            })
            ->escapeColumns([])->make();
    }
    public function stockoverview($slug)
    {
        return view('backend.content.product.stock', ['slug' => $slug]);
    }

    public function stockdata($stage)
    {

        $products = Product::with('sizes')->select('id', 'ProductName', 'ProductSku', 'ProductImage', 'status')->get();


        return Datatables::of($products)
            ->addColumn('serial', function ($products) {
                return 'ID: ' . $products->id . '<br> SKU: ' . $products->ProductSku;
            })
            ->editColumn('image', function ($products) {
                return '<img src="../../' . Product::where('id', $products->id)->first()->ProductImage . '" style="width:100px">';
            })
            ->editColumn('stocks', function ($products) {
                $stocks = '';
                foreach ($products->sizes as $size) {

                    $stocks = $stocks . '<div class="d-flex"><p style="width:80px;float:left;">Size: ' . $size->size . '</p><p style="width:80px;float:left;"> Total: ' . $size->total_stock . '</p><p style="width:80px;float:left;"> Sold: ' . $size->sold . '</p><p style="width:120px;float:left;"> Available: ' . $size->available_stock . '</div><br>';
                }
                return rtrim($stocks, '<br>');
            })
            ->editColumn('products', function ($products) {
                return $products->ProductName;
            })
            ->escapeColumns([])->make();
    }

    public function stockdatas($stage)
    {
        if ($stage == 'overview') {
            $type0 = DB::table('sizes')->orderBy('product_id')
                ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                ->join('products', 'products.id', '=', 'sizes.product_id')->get();
        } else {
            if ($stage == 'low') {
                $type0 = DB::table('sizes')->where('available_stock', '<', 9)->orderBy('product_id')
                    ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                    ->join('products', 'products.id', '=', 'sizes.product_id')->get();
            } else {
                $type0 = DB::table('sizes')->where('available_stock', 0)->orderBy('product_id')
                    ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                    ->join('products', 'products.id', '=', 'sizes.product_id')->get();
            }
        }

        $products = $type0;

        foreach ($products as $item) {

            if (App::environment('local')) {
                $item->ProductImage = url($item->ProductImage);
            } else {
                $item->ProductImage = url($item->ProductImage);
            }
            $product[] = array(
                "id" => $item->product_id,
                "size_id" => $item->id,
                "text" => $item->ProductName,
                "size" => $item->size,
                "total" => $item->total_stock,
                "sold" => $item->sold,
                "available" => $item->available_stock,
                "image" => $item->ProductImage,
                "productCode" => $item->ProductSku,
                "productPrice" => intval($item->Wholesale),
                "salePrice" => intval($item->SalePrice),
                "twholesale" => 0,
                "tsale" => intval($item->SalePrice) * $item->available_stock,
                "status" => $item->status
            );
        }

        return Datatables::of($product)
            ->addColumn('serial', function ($product) {
                if ($product['available'] <= 9) {
                    return '<span style="color:red;font-weight:bold">ID: ' . $product['id'] . '<br> SKU: ' . $product['productCode'] . '</span';
                } else {
                    return 'ID: ' . $product['id'] . '<br> SKU: ' . $product['productCode'];
                }
            })
            ->editColumn('image', function ($product) {
                return '<img src="../../' . Product::where('id', $product['id'])->first()->ProductImage . '" style="width:100px">';
            })
            ->editColumn('products', function ($product) {
                return $product['text'];
            })
            ->escapeColumns([])->make();
    }

    public function index()
    {
        $sizes = Attrvalue::where('attribute_id', 2)->where('status', 'Active')->get();
        $colors = Attrvalue::where('attribute_id', 3)->where('status', 'Active')->get();
        $weights = Attrvalue::where('attribute_id', 1)->where('status', 'Active')->get();
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'status')->get();
        $brands = Brand::where('status', 'Active')->select('id', 'brand_name', 'status')->get();
        $subcategories = Subcategory::where('status', 'Active')->select('id', 'sub_category_name')->get();
        return view('backend.content.product.index', ['brands' => $brands, 'weights' => $weights, 'colors' => $colors, 'sizes' => $sizes, 'categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function create()
    {
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'status')->get();
        $brands = Brand::where('status', 'Active')->select('id', 'brand_name', 'status')->get();
        return view('backend.content.product.create', ['brands' => $brands, 'categories' => $categories]);
    }

    public function variant(Request $request)
    {
        if (isset($request['q'])) {
            $variants = Attrvalue::query()->where('value', 'like', '%' . $request['q'] . '%')->where('attribute_id', 3)->where('status', 'Active')->get();
        } else {
            $variants = Attrvalue::where('attribute_id', 3)->where('status', 'Active')->get();
        }
        $variant = array();
        foreach ($variants as $item) {
            $variant[] = array(
                "id" => $item['id'],
                "text" => $item['value']
            );
        }
        $data['data'] = $variant;
        return json_encode($data);
        die();
    }

    public function size(Request $request)
    {
        if (isset($request['q'])) {
            $variants = Attrvalue::query()->where('value', 'like', '%' . $request['q'] . '%')->where('attribute_id', 2)->where('status', 'Active')->get();
        } else {
            $variants = Attrvalue::where('attribute_id', 2)->where('status', 'Active')->get();
        }
        $variant = array();
        foreach ($variants as $item) {
            $variant[] = array(
                "id" => $item['id'],
                "text" => $item['value']
            );
        }
        $data['data'] = $variant;
        return json_encode($data);
        die();
    }

    public function weight(Request $request)
    {
        if (isset($request['q'])) {
            $variants = Attrvalue::query()->where('value', 'like', '%' . $request['q'] . '%')->where('attribute_id', 1)->where('status', 'Active')->get();
        } else {
            $variants = Attrvalue::where('attribute_id', 1)->where('status', 'Active')->get();
        }
        $variant = array();
        foreach ($variants as $item) {
            $variant[] = array(
                "id" => $item['id'],
                "text" => $item['value']
            );
        }
        $data['data'] = $variant;
        return json_encode($data);
        die();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statusupdate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $product->status = $request->status;
        $product->update();
        return response()->json($product, 200);
    }

    public function featurestatusupdate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $product->frature = $request->frature;
        $product->update();
        return response()->json($product, 200);
    }

    public function bestsellstatusupdate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $product->best_selling = $request->best;
        $product->update();
        return response()->json($product, 200);
    }

    public function ratedstatusupdate(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $product->top_rated = $request->top_rated;
        $product->update();
        return response()->json($product, 200);
    }

    public function removevarient($id)
    {
        $variant = Varient::where('id', $id)->first();
        $variant->delete();
        $response['status'] = 'success';
        $response['message'] = 'Varient Remove Sucessfully';
        return json_encode($response);
        die();
    }
    public function removesize(Request $request, $id)
    {
        if($request->status=='Trash'){
            $size = Size::where('id', $id)->first();
            $purcheseproducts = Purcheseproduct::where('size_id', $size->id)->get(); 
            foreach ($purcheseproducts as $purcheseproduct) {
                $stocks = Stock::where('purchese_product_id', $purcheseproduct->id)->delete();
                $orderproducts = Purcheseproduct::where('purchese_id', $purcheseproduct->id)->delete();
                $purcheseproduct->delete();
            }
            $size->delete();
            $response['status'] = 'success';
            $response['message'] = 'Size Delete Sucessfully';
            return json_encode($response);
            die();
        }else{             
            $size = Size::where('id', $id)->first();
            $size->status = $request->status;
            $size->update();
            $response['status'] = 'success';
            $response['message'] = 'Size Send To Trash Sucessfully';
            return json_encode($response);
            die();
        }
    }
    public function removeweight($id)
    {
        $weight = Weight::where('id', $id)->first();
        $weight->delete();
        $response['status'] = 'success';
        $response['message'] = 'Weight Remove Sucessfully';
        return json_encode($response);
        die();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $last_id = Product::max('id') + 1;
        $product = new Product();
        $product->ProductName = $request->ProductName;
        $product->bonus_coin = $request->bonus_coin;
        $product->ProductBreaf = $request->ProductBreaf;
        $product->ProductDetails = $request->ProductDetails;
        $product->position = $request->position;
        $product->ProductSku = 'P' . str_pad($last_id, 4, '0', STR_PAD_LEFT);
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        if (isset($request->subcategory_id)) {
            $product->subcategory_id = $request->subcategory_id;
        }
        if ($request->hasFile('PostImage')) {
            foreach ($request->file('PostImage') as $imgfiles) {
                $name = time() . "_" . $imgfiles->getClientOriginalName();
                $imgfiles->move(public_path() . '/images/product/slider/', $name);
                $imageData[] = $name;
            }
            $product->PostImage = json_encode($imageData);
        };

        $product->youtube_embade = $request->youtube_embade;
        $product->MetaTitle = $request->MetaTitle;
        $product->MetaKey = $request->MetaKey;
        $product->MetaDescription = $request->MetaDescription;

        if ($request->variant) {
            $variants = $request->variant;
        }
        if ($request->size) {
            $sizes = $request->size;
        }
        if ($request->weight) {
            $weights = $request->weight;
        }
        $time = microtime('.') * 10000;


        $productImg = $request->ProductImage;
        if ($productImg) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/product/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $product->ProductImage = $productImgUrl;
            $webp = $productImgUrl;
            $im = imagecreatefromstring(file_get_contents($webp));
            $new_webp = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $webp);
            imagewebp($im, $new_webp, 50);
            $product->ViewProductImage = $new_webp;
        }

        $result = $product->save();

        if ($result) {
            if (!empty($variants)) {
                foreach ($variants as $vr) {
                    $variant = new Varient();
                    $variant->product_id = $product->id;
                    $variant->color_id = $vr['mediaID'];
                    $variant->color = $vr['color'];
                    $variantImg = $vr['image'];
                    if ($variantImg) {
                        $imgnamev = $time . $variantImg->getClientOriginalName();
                        $imguploadPathv = ('public/images/variant/');
                        $variantImg->move($imguploadPathv, $imgnamev);
                        $variantImgUrl = $imguploadPathv . $imgnamev;
                        $variant->Image = $variantImgUrl;
                    }
                    $variant->save();
                }
            }
            if (!empty($sizes)) {
                foreach ($sizes as $si) {
                    $size = new Size();
                    $size->product_id = $product->id;
                    $size->size_id = $si['sizeID'];
                    $size->size = $si['size'];
                    $size->RegularPrice = $si['RegularPrice'];
                    $size->Discount = $si['RegularPrice'] - $si['Discount'];
                    $size->SalePrice = $si['Discount'];
                    $size->save();
                }
            }
            if (!empty($weights)) {
                foreach ($weights as $we) {
                    $weight = new Weight();
                    $weight->product_id = $product->id;
                    $weight->weight_id = $we['weightID'];
                    $weight->weight = $we['weight'];
                    $weight->RegularPrice = $we['RegularPrice'];
                    $weight->Discount = $we['RegularPrice'] - $we['Discount'];
                    $weight->SalePrice = $we['Discount'];
                    $weight->save();
                }
            }
        }

        $response['status'] = 'success';
        $response['message'] = 'Product Create Sucessfully';
        return json_encode($response);
        die();
    }

    public function sku()
    {
        $lastProduct = Product::latest('id')->first();
        if ($lastProduct) {
            $ProductID = $lastProduct->id + 1;
        } else {
            $ProductID = 1;
        }

        return 'RPQ' . $ProductID;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function productdata(Request $request)
    {
        Session::put('category_id',$request->category_id);
        if($request->category_id=='all'){
            if(isset($request->code)){
                $products = Product::where('ProductName', 'LIKE', '%' . $request->code . '%');
            }else{
                $products = Product::all(); 
            }
            
        }else{
            if(isset($request->code)){
                $products = Product::where('category_id',$request->category_id)->where('ProductName', 'LIKE', '%' . $request->code . '%')->orderByRaw('ISNULL(`position`), `position` ASC');
            }else{
                $products = Product::where('category_id',$request->category_id);
            }
            
        }
        
        
        return Datatables::of($products)
            ->addColumn('action', function ($products) {
                if(Auth::id()==1) {
                    return '<a href="products/' . $products->id . '/edit" class="btn btn-primary btn-sm" style="margin-bottom:2px;"><i class="bi bi-pencil-square"></i></a>
                    <a href="#" type="button" style="margin-bottom:2px;" id="deleteProductBtn" data-id="' . $products->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
                }else{
                    return '<a href="products/' . $products->id . '/edit" class="btn btn-primary btn-sm" style="margin-bottom:2px;"><i class="bi bi-pencil-square"></i></a>';
                }
                
            })
            ->addColumn('category', function ($products) {
                 
                    return Category::where('id',$products->category_id)->first()->category_name;
                 
            })
            ->addColumn('positioninfo', function ($products) {
                if(isset($products->position)){
                    return '<button type="button" class="btn btn-info btn-sm btn-status">'.$products->position.'</button>';
                }else{
                    return '';
                }
            })
            ->addColumn('landing', function ($products) {
                if($products->landingpage_status=='No'){
                    return '<a href="#" type="button"  class="btn btn-primary btn-sm" style="margin-bottom:2px;">No View</a>';
                }else{
                    return '<a href="../lnpage/'. $products->ProductSlug .'" target="_blank"  class="btn btn-primary btn-sm" style="margin-bottom:2px;">landingpage View</a>';
                }
            })

             ->escapeColumns([])->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        $varients = Varient::where('product_id', $id)->get();
        $sizes = Size::where('product_id', $id)->get();
        $weights = Weight::where('product_id', $id)->get();
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'status')->get();
        $brands = Brand::where('status', 'Active')->select('id', 'brand_name', 'status')->get();
        return view('backend.content.product.edit', ['weights' => $weights, 'sizes' => $sizes, 'varients' => $varients, 'product' => $product, 'brands' => $brands, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
        $product->ProductName = $request->ProductName;
        $product->bonus_coin = $request->bonus_coin;
        $product->ProductBreaf = $request->ProductBreaf;
        $product->ProductDetails = $request->ProductDetails;
        $product->position = $request->position;
        // $product->ProductSku = $request->ProductSku;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        if (isset($request->subcategory_id)) {
            $product->subcategory_id = $request->subcategory_id;
        }
        $product->youtube_embade = $request->youtube_embade;
        $product->MetaTitle = $request->MetaTitle;
        $product->MetaKey = $request->MetaKey;
        $product->MetaDescription = $request->MetaDescription;

        if ($request->variant) {
            $variants = $request->variant;
        }
        if ($request->size) {
            $sizes = $request->size;
        }
        if ($request->weight) {
            $weights = $request->weight;
        }
        $time = microtime('.') * 10000;


        $productImg = $request->ProductImage;
        if (isset($productImg)) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/product/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $product->ProductImage = $productImgUrl;
            $webp = $productImgUrl;
            $im = imagecreatefromstring(file_get_contents($webp));
            $new_webp = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $webp);
            imagewebp($im, $new_webp, 50);
            $product->ViewProductImage = $new_webp;
        }

        if ($request->hasFile('PostImage')) {
            foreach ($request->file('PostImage') as $imgfiles) {
                $name = time() . "_" . $imgfiles->getClientOriginalName();
                $imgfiles->move(public_path() . '/images/product/slider/', $name);
                $imageData[] = $name;
            }
            $product->PostImage = json_encode($imageData);
        };

        $result = $product->update();

        if ($result) {
            if (!empty($variants)) {
                foreach ($variants as $vr) {
                    if (isset($vr['mID'])) {
                        $variant = Varient::where('id', $vr['mID'])->first();
                        $variant->product_id = $product->id;
                        $variant->color_id = $vr['mediaID'];
                        $variant->color = $vr['color'];
                        $variantImg = $vr['image'];
                        if ($variantImg) {
                            $imgnamev = $time . $variantImg->getClientOriginalName();
                            $imguploadPathv = ('public/images/variant/');
                            $variantImg->move($imguploadPathv, $imgnamev);
                            $variantImgUrl = $imguploadPathv . $imgnamev;
                            $variant->Image = $variantImgUrl;
                        }
                        $variant->update();
                    } else {
                        $variant = new Varient();
                        $variant->product_id = $product->id;
                        $variant->color_id = $vr['mediaID'];
                        $variant->color = $vr['color'];
                        $variantImg = $vr['image'];
                        if ($variantImg) {
                            $imgnamev = $time . $variantImg->getClientOriginalName();
                            $imguploadPathv = ('public/images/variant/');
                            $variantImg->move($imguploadPathv, $imgnamev);
                            $variantImgUrl = $imguploadPathv . $imgnamev;
                            $variant->Image = $variantImgUrl;
                        }
                        $variant->save();
                    }
                }
            }
            if (!empty($sizes)) {
                foreach ($sizes as $si) {
                    if (isset($si['sID'])) {
                        $size = Size::where('id', $si['sID'])->first();
                        $size->product_id = $product->id;
                        $size->size_id = $si['sizeID'];
                        $size->size = $si['size'];
                        $size->RegularPrice = $si['RegularPrice'];
                        $size->Discount = $si['RegularPrice'] - $si['Discount'];
                        $size->SalePrice = $si['Discount'];
                        $size->update();
                    } else {
                        $size = new Size();
                        $size->product_id = $product->id;
                        $size->size_id = $si['sizeID'];
                        $size->size = $si['size'];
                        $size->RegularPrice = $si['RegularPrice'];
                        $size->Discount = $si['RegularPrice'] - $si['Discount']; 
                        $size->SalePrice = $si['Discount'];
                        $size->save();
                    }
                }
            }
            if (!empty($weights)) {
                foreach ($weights as $we) {
                    if (isset($we['wID'])) {
                        $weight = Weight::where('id', $we['wID'])->first();
                        $weight->product_id = $product->id;
                        $weight->weight_id = $we['weightID'];
                        $weight->weight = $we['weight'];
                        $weight->RegularPrice = $we['RegularPrice'];
                        $weight->Discount = $we['RegularPrice'] - $we['Discount'];
                        $weight->SalePrice = $we['Discount'];
                        $weight->update();
                    } else {
                        $weight = new Weight();
                        $weight->product_id = $product->id;
                        $weight->weight_id = $we['weightID'];
                        $weight->weight = $we['weight'];
                        $weight->RegularPrice = $we['RegularPrice'];
                        $weight->Discount = $we['RegularPrice'] - $we['Discount'];
                        $weight->SalePrice = $we['Discount'];
                        $weight->save();
                    }
                }
            }
        }

        $response['status'] = 'success';
        $response['message'] = 'Product Update Sucessfully';
        return json_encode($response);
        die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = Product::where('id', $product->id)->first();

        $variant = Varient::where('product_id', $product->id)->delete();;
        $size = Size::where('product_id', $product->id)->delete();
        $weight = Weight::where('product_id', $product->id)->delete();
        $orderproducts = Orderproduct::where('product_id', $product->id)->get();

        foreach ($orderproducts as $orderproduct) {
            $orderpros = Orderproduct::where('order_id', $orderproduct->order_id)->get();
            if (count($orderpros) > 1) {
                $orderproduct->delete();
            } else {
                $order = Order::where('id', $orderproduct->order_id)->delete();
                $customer = Customer::where('order_id', $orderproduct->order_id)->delete();
                $orderproduct->delete();
            }
        }

        $purcheseproducts = Purcheseproduct::where('product_id', $product->id)->get();

        foreach ($purcheseproducts as $purcheseproduct) {
            $stocks = Stock::where('purchese_product_id', $purcheseproduct->id)->delete();
            $orderproducts = Purcheseproduct::where('purchese_id', $purcheseproduct->id)->delete();
            $purcheseproduct->delete();
        }

        $mainproducts = Mainproduct::where('category_id', $product->category_id)->get();

        foreach ($mainproducts as $mainproduct) {
            $dataArray = json_decode($mainproduct->RelatedProductIds, true);
            if (is_array($dataArray)) {
                // Filter out the productID
                $deleteID = $product->id;
                $filteredArray = array_filter($dataArray, function ($item) use ($deleteID) {
                    return $item['productID'] != $deleteID;
                });

                // Re-index the array
                $filteredArray = array_values($filteredArray);

                // Convert back to JSON
                $newJsonData = json_encode($filteredArray, JSON_PRETTY_PRINT);
                $newJsonData;
                $mainproduct->RelatedProductIds = $newJsonData;
                $mainproduct->update();
                $dataArraycheck = json_decode($mainproduct->RelatedProductIds, true);
                if (empty($dataArraycheck)) {
                    $mainproduct->delete();
                } else {
                }
            } else {
                $mainproduct->delete();
            }
        }

        $product->delete();
        return response()->json('success', 200);
    }
}