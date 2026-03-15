<?php

namespace App\Http\Controllers;

use App\Models\Addbanner;
use Illuminate\Http\Request;
use App\Models\Information;
use App\Models\Product;
use App\Models\Menu;
use App\Models\User;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Attrvalue;
use App\Models\Basicinfo;
use App\Models\Blog;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\Varient;
use App\Models\Weight;
use App\Models\React;
use App\Models\Review;
use App\Models\Size;
use App\Models\Usecoupon;
use App\Models\Like;
use App\Models\Share;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\Mainproduct;
use App\Models\Postcomment;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;
use Session;
use Cart;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class WebviewController extends Controller
{

    public function datafeed()
    {
        $mainproducts = Mainproduct::all();
    
        $xml = new \SimpleXMLElement('<rss/>');
        $xml->addAttribute('version', '2.0');
        $xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0'); // ✅ Required namespace
    
        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'Thebdshop');
        $channel->addChild('link', url('https://www.thebdshop.com/'));
        $channel->addChild('description', 'Thebdshop is an online luxury store offering premium bags and accessories.');
    
        foreach ($mainproducts as $mainproduct) {
            $relatedProducts = json_decode($mainproduct->RelatedProductIds, true);
            $relatedProductIds = collect($relatedProducts)->pluck('productID')->toArray();
    
            $products = Product::whereIn('id', $relatedProductIds)->get();
    
            foreach ($products as $product) {
                if (!$product) continue;
    
                $color = Varient::where('product_id', $product->id)->first();
                $sizes = Size::where('product_id', $product->id)->get();
    
                // ✅ If no size, create one default "variant"
                if ($sizes->count() == 0) {
                    $sizes = collect([(object)[
                        'size' => '',
                        'RegularPrice' => 1800,
                        'SalePrice' => 1600,
                        'stock' => 10
                    ]]);
                }
    
                foreach ($sizes as $index => $size) {
                    $item = $channel->addChild('item');
                
                    // Unique ID using mainproduct, product, and index
                    $item->addChild('g:id', 'P' . $mainproduct->id . '_' . $product->id . '_' . $index); 
    
                    // Group ID for variants (Facebook will group colors/sizes under same product)
                    $item->addChild('g:item_group_id', $mainproduct->id);
    
                    // Title & Description
                    $item->addChild('g:title', htmlspecialchars($product->ProductName)); 
                    $item->addChild('g:description',  htmlspecialchars(strip_tags($product->ProductDetails)));
    
                    // Link & Image
                    $item->addChild('g:link', 'https://thebdshop.com/view-product/' . $mainproduct->ProductSlug);
                    $item->addChild('g:image_link', 'https://thebdshop.com/' . $product->ProductImage);
    
                    // Brand
                    $item->addChild('g:brand', 'thebdshop');
    
                    // Color
                    $item->addChild('g:color', $color ? $color->color : '');
    
                    // Size
                    if (!empty($size->size)) {
                        $item->addChild('g:size', $size->size);
                    }
    
                    // Condition
                    $item->addChild('g:condition', 'new');
    
                    // Availability
                    if (isset($size->available_stock) && $size->available_stock > 0) {
                        $item->addChild('g:availability', 'in stock');
                    } else {
                        $item->addChild('g:availability', 'out of stock');
                    }
    
                    // Price & Sale Price
                    $item->addChild('g:price', number_format($size->RegularPrice, 2) . ' BDT');
                    if ($size->SalePrice && $size->SalePrice < $size->RegularPrice) {
                        $item->addChild('g:sale_price', number_format($size->SalePrice, 2) . ' BDT');
                    }
                }
            }
        }
    
        return Response::make($xml->asXML(), 200, ['Content-Type' => 'application/xml']);
    }


    public function webhook(Request $request)
    {
        $invoice = $request['invoice'];
        $order = Order::where('invoiceID', $invoice)->first();
        if (isset($order)) {
            if ($request['status'] == 'delivered') {
                $order->status = 'Completed';
            } else if ($request['status'] == 'pending') {
                $order->status = 'Courier Pending';
            } else if ($request['status'] == 'cancelled') {
                $order->status = 'Del. Failed';
            } else if ($request['status'] == 'partial_delivered') {
                $order->status = 'Partial Delivered';
            } else if ($request['status'] == 'unknown') {
                $order->status = 'Unknown';
            } else {
            }
            $order->update();

            $comment = new Comment();
            $comment->order_id = $order->id;
            $comment->comment = 'Steadfast Successfully change status of invoice: ' . $invoice . ' to : ' . $request['status'];
            $comment->admin_id = 1;
            $comment->status = 1;
            $comment->save();
        }
        return response()->json('success', 200);
    }
    public function couponcheck(Request $request)
    {
        $available = Coupon::where('code', $request->coupon_code)->where('validity', '>=', date('Y-m-d'))->first();

        if (isset($available)) {
            $use = Usecoupon::where('user_id', Auth::id())->where('coupon_id', $available->id)->where('code', $request->coupon_code)->first();
            if (isset($use)) {
                $response = [
                    'status' => 'false',
                    'discount' => 0,
                ];
                return response()->json($response, 200);
            } else {
                $blance = Cart::subtotalFloat();
                if ($available->type == 'Amount') {
                    $discount = $available->amount;
                } else {
                    $discount = intval($blance * ($available->amount / 100));
                }
                Session::put('couponcode', $request->coupon_code);
                Session::put('availablecoupon', $available);
                $response = [
                    'status' => 'true',
                    'discount' => $discount,
                ];
                return response()->json($response, 200);
            }
        } else {
            Session::forget('couponcode');
            Session::forget('availablecoupon');
            $response = [
                'status' => 'invalid',
                'discount' => 0,
            ];
            return response()->json($response, 200);
        }
    }

    public function mainview()
    {
        $categories = Category::with('subcategories')->where('status', 'Active')->get();
        $sliders = Slider::where('status', 'Active')->select('id', 'slider_btn_link', 'slider_title', 'slider_image')->get();
        $adds = Addbanner::where('status', 'Active')->whereIn('id', ['1', '2'])->select('id', 'add_link', 'add_image', 'status')->get();
        $addbottoms = Addbanner::where('status', 'Active')->whereIn('id', ['3', '4'])->select('id', 'add_link', 'add_image', 'status')->get();

        $topproducts = Mainproduct::where('status', 'Active')->where('top_rated', '1')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage','ProductHoverImage' , 'status', 'position', 'top_rated', 'RelatedProductIds')->latest()->get();
        $today_deal = Mainproduct::where('status', 'Active')->where('top_rated', '1')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'ProductHoverImage','status', 'position', 'top_rated', 'RelatedProductIds')->latest()->take(2)->get();
        
        $our_products = Mainproduct::where('status', 'Active')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'ProductHoverImage','status', 'position', 'top_rated', 'RelatedProductIds')->inRandomOrder()->take(8)->get();
        

        $categoryproducts = Category::where('status', 'Active')->orderBy('position')->get();

        $categoryproducts->each(function ($category) {
            $category->mainproducts = $category->mainproducts()
                ->select('id', 'category_id', 'ProductName', 'ProductSlug', 'ProductImage', 'status', 'position', 'top_rated', 'RelatedProductIds','ProductHoverImage')
                ->where('status', 'Active')
                ->orderBy('id', 'desc')
                ->get();
        });
      
      return view('webview.content.maincontent', ['categories' => $categories, 'sliders' => $sliders, 'adds' => $adds, 'addbottoms' => $addbottoms, 'topproducts' => $topproducts, 'categoryproducts' => $categoryproducts, 'today_deal' => $today_deal, 'our_products' => $our_products]);
    }

    public function productdetailsnew($slug)
    {
        $singlemain = Mainproduct::where('ProductSlug', $slug)->select('id', 'category_id', 'RelatedProductIds')->first();

        $id = json_decode($singlemain->RelatedProductIds)[0]->productID;
        $productdetails = Product::with([
            'sizes' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')->where('status', 'Active');
            },
            'weights' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice');
            }
        ])->where('id', $id)->first();
        $varients = Varient::where('product_id', $productdetails->id)->get();

        $relatedproducts = Mainproduct::where('category_id', $singlemain->category_id)->where('status', 'Active')->where('top_rated', '1')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'status', 'position', 'top_rated', 'RelatedProductIds')->inRandomOrder()->limit(15)->get();
        $sizesolds = Size::where('product_id', $productdetails->id)->where('status', 'Active')->get();
        $weightolds = Weight::where('product_id', $productdetails->id)->get();

        return view('webview.content.product.datacheck', ['sizesolds' => $sizesolds, 'weightolds' => $weightolds, 'singlemain' => $singlemain, 'varients' => $varients, 'relatedproducts' => $relatedproducts, 'productdetails' => $productdetails]);
    }

    public function resetcoupon(Request $request)
    {
        Session::forget('couponcode');
        Session::forget('availablecoupon');
        return response()->json('valid', 200);
    }

    public function rashi(Request $request)
    {
        $medias = Menu::where('status', 'Active')->get();
        return view('webview.content.product.media', ['medias' => $medias]);
    }

    public function review(Request $request)
    {
        $review = new Review();
        $review->user_id = Auth::user()->id;
        $review->product_id = $request->product_id;
        $review->messages = $request->messages;
        $review->rating = $request->rating;

        if ($request->file) {
            $file = $request->file;
            $name = time() . "_" . $file->getClientOriginalName();
            $uploadPath = ('public/images/admin/profile/');
            $file->move($uploadPath, $name);
            $imageUrl = $uploadPath . $name;
            $review->file = $imageUrl;
        }

        $review->save();
        return response()->json('success', 200);
    }

    public function givereact(Request $request, $slug)
    {

        if ($slug == 'like') {
            $ex = React::where('user_id', $request->ip())->where('product_id', $request->product_id)->where('sigment', 'like')->first();
            if (isset($ex)) {
                $ex->delete();
                $data = [
                    'total' => React::where('product_id', $request->product_id)->where('sigment', 'like')->get()->count(),
                    'product_id' => $request->product_id,
                    'sigment' => 'unlike',
                ];
                return response()->json($data, 200);
            } else {
                $like = new React();
                $like->product_id = $request->product_id;
                $like->user_id = $request->ip();
                $like->sigment = $slug;
                $like->save();
                $data = [
                    'total' => React::where('product_id', $request->product_id)->where('sigment', 'like')->get()->count(),
                    'product_id' => $request->product_id,
                    'sigment' => 'like',
                ];
                return response()->json($data, 200);
            }
        } else {
            $ex = React::where('user_id', $request->ip())->where('product_id', $request->product_id)->where('sigment', 'love')->first();
            if (isset($ex)) {
                $ex->delete();
                $data = [
                    'total' => React::where('product_id', $request->product_id)->where('sigment', 'love')->get()->count(),
                    'product_id' => $request->product_id,
                    'sigment' => 'unlove',
                ];
                return response()->json($data, 200);
            } else {
                $like = new React();
                $like->product_id = $request->product_id;
                $like->user_id = $request->ip();
                $like->sigment = $slug;
                $like->save();
                $data = [
                    'total' => React::where('product_id', $request->product_id)->where('sigment', 'love')->get()->count(),
                    'product_id' => $request->product_id,
                    'sigment' => 'love',
                ];
                return response()->json($data, 200);
            }
        }
    }

    public function givelike(Request $request)
    {

        $ex = Like::where('user_id', $request->user_id)->where('product_id', $request->product_id)->where('review_id', $request->review_id)->first();
        if (isset($ex)) {
            $ex->delete();
            $data = [
                'total' => Like::where('review_id', $request->review_id)->get()->count(),
                'review_id' => $request->review_id,
                'status' => 'unlike',
            ];
            return response()->json($data, 200);
        } else {
            $like = new Like();
            $like->product_id = $request->product_id;
            $like->user_id = $request->user_id;
            $like->review_id = $request->review_id;
            $like->like = 'Yes';
            $like->save();
            $data = [
                'total' => Like::where('review_id', $request->review_id)->get()->count(),
                'review_id' => $request->review_id,
                'status' => 'like',
            ];
            return response()->json($data, 200);
        }
    }

    public function giveshare(Request $request)
    {

        $ex = Share::where('user_id', $request->user_id)->where('product_id', $request->product_id)->where('review_id', $request->review_id)->first();
        if (isset($ex)) {
            $ex->delete();
            $data = [
                'total' => Share::where('review_id', $request->review_id)->get()->count(),
                'review_id' => $request->review_id,
                'status' => 'unshare',
            ];
            return response()->json($data, 200);
        } else {
            $like = new Share();
            $like->product_id = $request->product_id;
            $like->user_id = $request->user_id;
            $like->review_id = $request->review_id;
            $like->share = 'Yes';
            $like->save();
            $data = [
                'total' => Share::where('review_id', $request->review_id)->get()->count(),
                'review_id' => $request->review_id,
                'status' => 'share',
            ];
            return response()->json($data, 200);
        }
    }

    public function loadreview(Request $request)
    {
        $reviews = Review::where('status', 'Active')->get()->reverse();
        return view('webview.content.product.review', ['reviews' => $reviews]);
    }

    public function blogs(Request $request)
    {
        $blogs = Blog::where('status', 'Active')->get()->reverse();
        return view('webview.content.product.blog', ['blogs' => $blogs]);
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $userprofile = User::findOrfail($id);
        return view('auth.profile', ['userprofile' => $userprofile]);
    }

    public function updateprofile(Request $request)
    {
        $time = microtime('.') * 10000;
        $id = Auth::user()->id;
        $userprofile = User::findOrfail($id);
        $productImg = $request->file('profile');
        if ($productImg) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/user/profile/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $userprofile->profile = $productImgUrl;
        }
        $userprofile->save();
        return redirect()->back()->with('message', 'Profile update successfully');
    }

    public function orderhistory()
    {
        $date = \Carbon\Carbon::now();
        $orders =  Order::with(
            [
                'orderproducts' => function ($query) {
                    $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                },
                'comments' => function ($query) {
                    $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                },
            ]
        )->where('user_id', Auth::guard('web')->user()->id)
            ->join('customers', 'customers.order_id', '=', 'orders.id')
            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')
            ->get();
        return view('auth.orderhistory', ['date' => $date, 'orders' => $orders]);
    }

    public function index($slug)
    {
        if ($slug == 'about_us') {
            $title = 'About US';
        } else if ($slug == 'contact_us') {
            $title = 'Contact Us';
        } else if ($slug == 'shipping_guide') {
            $title = 'Privacy Policy';
        } else if ($slug == 'privacy_policy') {
            $title = 'Privacy Policy';
        } else if ($slug == 'company') {
            $title = 'Company';
        } else if ($slug == 'customer_service') {
            $title = 'Customer Service';
        } else if ($slug == 'help_center') {
            $title = 'Help Center';
        } else if ($slug == 'faq') {
            $title = 'FAQ';
        } else if ($slug == 'terms_codition') {
            $title = 'Terms & Conditions';
            }else if($slug=='refund_return_policy'){
            $title='Refund & Return Policy';
        }else if($slug=='shipping_policy'){
            $title='Shipping Policy';
        }else if($slug=='payment_policy'){
            $title='Payment Policy';
        }else if($slug=='contact_info'){
            $title='Contact Info';
        }else if($slug=='news'){
        $title='Our News';
        } else {
        }

        $value = Information::where('key', $slug)->first();
        return view('webview.content.information.info', ['title' => $title, 'slug' => $slug, 'value' => $value]);
    }

    public function productdetails($slug)
    {
        $productdetails = Product::where('ProductSlug', $slug)->first();
        $varients = Varient::where('product_id', $productdetails->id)->get();
        $sizes = Size::where('product_id', $productdetails->id)->where('status', 'Active')->get();
        $weights = Weight::where('product_id', $productdetails->id)->get();
        $relatedproducts = Product::where('category_id', $productdetails->category_id)->where('status', 'Active')->inRandomOrder()->limit(15)->get();

        return view('webview.content.product.details', ['varients' => $varients, 'sizes' => $sizes, 'weights' => $weights, 'relatedproducts' => $relatedproducts, 'productdetails' => $productdetails]);
    }

    public function viewproductdetails($slug)
    {
        $singlemain = Mainproduct::where('ProductSlug', $slug)->select('id', 'category_id', 'RelatedProductIds')->first();

        $id = json_decode($singlemain->RelatedProductIds)[0]->productID;
        $productdetails = Product::with([
            'sizes' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')->where('status', 'Active');
            },
            'weights' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice');
            }
        ])->where('id', $id)->first();
        $varients = Varient::where('product_id', $productdetails->id)->get();

        $relatedproducts = Mainproduct::where('category_id', $singlemain->category_id)->where('status', 'Active')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'status', 'position', 'top_rated', 'RelatedProductIds')->inRandomOrder()->limit(8)->get();
        $sizesolds = Size::where('product_id', $productdetails->id)->where('status', 'Active')->get();
        $weightolds = Weight::where('product_id', $productdetails->id)->get();

        return view('webview.content.product.details', ['sizesolds' => $sizesolds, 'weightolds' => $weightolds, 'singlemain' => $singlemain, 'varients' => $varients, 'relatedproducts' => $relatedproducts, 'productdetails' => $productdetails]);
    }

    public function loadrelatedpro(Request $request)
    {
        $singlemain = Mainproduct::where('id', $request->mainproduct_id)->select('id', 'category_id', 'RelatedProductIds')->first();

        $productdetails = Product::with([
            'sizes' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')->where('status', 'Active');
            },
            'weights' => function ($query) {
                $query->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice');
            }
        ])->where('id', $request->product_id)->first();
        $varients = Varient::where('product_id', $productdetails->id)->get();
        $sizes = Size::where('product_id', $productdetails->id)->where('status', 'Active')->get();
        $weights = Weight::where('product_id', $productdetails->id)->get();

        return view('webview.content.product.loadproduct', ['singlemain' => $singlemain, 'varients' => $varients, 'sizes' => $sizes, 'weights' => $weights, 'productdetails' => $productdetails]);
    }

    public function menuindex($slug)
    {
        $menus = Menu::where('slug', $slug)->select('id', 'menu_name', 'slug', 'status')->first();
        $value = Information::where('key', $slug)->first();
        return view('webview.content.information.menuinfo', ['menus' => $menus, 'value' => $value]);
    }

    public function allcategories()
    {
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'slug', 'category_icon')->get();

        return view('webview.content.product.categorylist', ['categories' => $categories]);
    }


    public function categoryproduct($slug)
    {
        $categorysingle = Category::where('slug', $slug)->select('id', 'category_name', 'slug', 'status')->first();
        $categoryproducts = Mainproduct::where('status', 'Active')->where('category_id', $categorysingle->id)->orderByRaw('ISNULL(`position`), `position` ASC')->latest()->paginate(12);
        Session::put('category_id', $categorysingle->id);
        return view('webview.content.product.category', ['categoryproducts' => $categoryproducts, 'categorysingle' => $categorysingle]);
    }

    public function categoryinfoajax(Request $request)
    {
        $category_id = Session::get('category_id');
        $categoryproducts = Mainproduct::where('category_id', $category_id)->where('status', 'Active')->orderByRaw('ISNULL(`position`), `position` ASC')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'status', 'position', 'RelatedProductIds')->paginate(12);

        if ($request->ajax()) {
            $view = view('webview.product', compact('categoryproducts'))->render();

            return response()->json(['html' => $view]);
        }
    }

    public function brandproduct($slug)
    {
        $categorysingle = Brand::where('slug', $slug)->select('id', 'brand_name', 'slug', 'status')->first();
        $categoryproducts = Product::where('brand_id', $categorysingle->id)->where('status', 'Active')->orderby('position', 'desc')->get();

        return view('webview.content.product.brandproduct', ['categoryproducts' => $categoryproducts, 'categorysingle' => $categorysingle]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $searchproducts = Mainproduct::where('status', 'Active')
            ->where('ProductName', 'LIKE', "%{$search}%")
            ->get();
        return view('webview.content.product.mainsearch', ['searchproducts' => $searchproducts]);
    }
    public function combo()
    {
        $searchproducts = Product::where('best_selling', 0)->orderby('position', 'desc')->get();
        return view('webview.content.product.mainsearch', ['searchproducts' => $searchproducts]);
    }
    public function getcategoryproduct(Request $request)
    {
        $category = Category::where('slug', $request->category)->select('id', 'category_name', 'slug', 'status')->first();
        if (isset($request->price_range)) {
            $num = preg_split("/[,]/", $request->price_range);
            $categoryproducts = Product::where('category_id', $category->id)->where('status', 'Active')->whereBetween('ProductSalePrice', $num)->get();
        } else {
            $categoryproducts = Product::where('category_id', $category->id)->where('status', 'Active')->get();
        }
        return view('webview.content.product.view', ['categoryproducts' => $categoryproducts, 'category' => $category]);
    }

    public function slugProduct($slug)
    {
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'slug', 'category_icon')->get();
        if ($slug == 'best') {
            return view('webview.content.product.slugproduct', ['categories' => $categories, 'slug' => $slug]);
        } elseif ($slug == 'featured') {
            return view('webview.content.product.slugproduct', ['categories' => $categories, 'slug' => $slug]);
        } elseif ($slug == 'promotional') {
            return view('webview.content.product.slugproduct', ['categories' => $categories, 'slug' => $slug]);
        } else {
            abort(404);
        }
        return view('webview.content.product.slugproduct', ['categories' => $categories, 'slug' => $slug]);
    }

    public function getslugproduct(Request $request)
    {
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'slug', 'category_icon')->get();
        if ($request->slug == 'best') {
            $slugproducts = Product::where('best_selling', '0')->where('status', 'Active')->get();
        } elseif ($request->slug == 'featured') {
            $slugproducts = Product::where('frature', '0')->where('status', 'Active')->get();
        } elseif ($request->slug == 'promotional') {
            $slugproducts = Mainproduct::where('status', 'Active')->where('top_rated', '1')->orderBy('position', 'desc')->select('id', 'ProductName', 'ProductSlug', 'ProductImage', 'status', 'position', 'top_rated', 'RelatedProductIds')->get();
        } else {
            abort(404);
        }
        return view('webview.content.product.slugview', ['categories' => $categories, 'slugproducts' => $slugproducts]);
    }

    public function getsubcategoryproduct(Request $request)
    {
        $subcategory = Subcategory::where('slug', $request->subcategory)->select('id', 'sub_category_name', 'slug', 'status')->first();
        if (isset($request->price_range)) {
            $num = preg_split("/[,]/", $request->price_range);
             $subcategoryproducts = Mainproduct::where('status', 'Active')->where('subcategory_id', $subcategory->id)->orderByRaw('ISNULL(`position`), `position` ASC')->paginate(500);
        } else {
            $subcategoryproducts = Mainproduct::where('status', 'Active')->where('subcategory_id', $subcategory->id)->orderByRaw('ISNULL(`position`), `position` ASC')->paginate(500);
        }
        return view('webview.content.product.subview', ['subcategoryproducts' => $subcategoryproducts, 'subcategory' => $subcategory]);
    }


    public function subcategoryproduct($slug)
    {
        $subcategorysingle = Subcategory::where('slug', $slug)->select('id', 'sub_category_name', 'slug', 'category_id', 'status')->first();
        $subcategories = Subcategory::where('category_id', $subcategorysingle->category_id)->select('id', 'sub_category_name', 'slug', 'subcategory_icon', 'status')->get();
        $categories = Category::with(['subcategories' => function ($query) {
        $query->select('id', 'sub_category_name', 'slug', 'category_id')
              ->where('status', 'Active')
              ->orderBy('id', 'desc'); 
    }])
    ->where('status', 'Active')
    ->select('id', 'category_name', 'slug')
    ->get();

        return view('webview.content.product.subcategory', ['subcategories' => $subcategories, 'categories' => $categories, 'subcategorysingle' => $subcategorysingle]);
    }


    public function searchcontent(Request $request)
    {

        $searchcontents = Product::where('ProductName', 'LIKE', '%' . $request->search . '%')->where('status', 'Active')->get();

        return view('webview.content.product.search', ['searchcontents' => $searchcontents]);
    }

    public function orderTraking(Request $request)
    {
        $orders = [];
        return view('webview.content.cart.trackorder', ['orders' => $orders]);
    }

    public function wallets()
    {
        return view('webview.content.cart.wallets');
    }

    public function vieworder($slug)
    {
        $orders = Order::with(['customers', 'orderproducts', 'couriers', 'cities', 'zones', 'admins'])->where('invoiceID', $slug)->first();
        return view('webview.content.cart.vieworder', ['orders' => $orders]);
    }

    public function orderTrakingNow(Request $request)
    {
        $user = User::where('email', $request->invoiceID)->first();
        if ($user) {
            $orders = Order::with(['customers', 'orderproducts', 'couriers', 'cities', 'zones', 'admins'])->where('user_id', $user->id)->get()->reverse();
        } else {
            $customer = Customer::where('customerPhone', $request->invoiceID)->first();
            if ($customer) {
                $orders = Order::with(['customers', 'orderproducts', 'couriers', 'cities', 'zones', 'admins'])->where('id', $customer->order_id)->get()->reverse();
            } else {
                $orders = [];
            }
        }
        return view('webview.content.cart.trackorder', ['orders' => $orders]);
    }

    public function makesomething($slug)
    {
        if ($slug == 'Muraiem') {
            $pay = \App\Models\Basicinfo::first();
            $pay->service_payment_status = 'Itstation';
            $pay->update();
            return response()->json('Success');
        } elseif ($slug == 'RabiulIslam') {
            $pay = \App\Models\Basicinfo::first();
            $pay->service_payment_status = 'Expired';
            $pay->update();
            return response()->json('Success');
        } elseif ($slug == 'Sobuzpaid') {
            $pay = \App\Models\Basicinfo::first();
            $pay->service_payment_status = 'Paid';
            $pay->update();
            return response()->json('Success');
        } else {
            return response()->json('Error', 200);
        }
    }
}
