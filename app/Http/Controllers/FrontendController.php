<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Frontend;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\HeaderSlider;
use App\Models\FooterSlider;
use App\Models\FrontSettings;
use App\Models\OurOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq=Faq::all();
        $slide=HeaderSlider::all();
        $footslider=FooterSlider::all();
        $frontsettt=FrontSettings::first();
        $popular_products = DB::table('db_items')->where('is_feature', '1')->select('id', 'item_name', 'sales_price', 'web_price', 'item_image', 'is_feature')->limit(16)->inRandomOrder()->orderBy('item_name')->where('web_price','>',0)->get();
        $top_products  = DB::table('db_items')->where('is_latest', '1')->select('id', 'item_name', 'sales_price', 'web_price', 'item_image', 'is_top')->limit(16)->inRandomOrder()->orderBy('item_name')->where('web_price','>',0)->get();
        $offer_products = DB::table('db_items')->where('is_top', '1')->select('id', 'item_name', 'sales_price', 'web_price', 'item_image', 'is_top')->limit(16)->inRandomOrder()->orderBy('item_name')->where('web_price','>',0)->get();

        $heroone = DB::table('header_sliders')->where('order_by', '1')->select('id', 'title', 'short_description', 'slider_image', 'link', 'order_by')->first();
        $herotwo = DB::table('header_sliders')->where('order_by', '2')->select('id', 'title', 'short_description', 'slider_image', 'link', 'order_by')->first();
        $herothree = DB::table('header_sliders')->where('order_by', '3')->select('id', 'title', 'short_description', 'slider_image', 'link', 'order_by')->first();
        $herofour = DB::table('header_sliders')->where('order_by', '4')->select('id', 'title', 'short_description', 'slider_image', 'link', 'order_by')->first();
        $herofive = DB::table('header_sliders')->where('order_by', '5')->select('id', 'title', 'short_description', 'slider_image', 'link', 'order_by')->first();

        return view('home', compact('faq', 'slide', 'footslider', 'popular_products', 'offer_products', 'top_products', 'frontsettt','heroone','herotwo','herothree','herofour','herofive'));
    }
    public function Search(Request $request)
    {
        if($request->item_name)
        $product=DB::table('db_items')->where('item_name','like','%'.$request->item_name.'%')->get();
        return view('product.search',compact('product'));
    }

    public function CustomerDasboard()
    {
        $order=Order::where('user_id',Session::get('userId'))->with(['billing','orderdetails'])->limit(3)->get();
        // return $order;
        return view('product.customer_dashboard',['order_details' =>$order]);
    }

    public function subCategory($category_id)
    {
        $show_subcategory = SubCategory::where('category_id',$category_id)->get();
        $cat=Category::find($category_id);
        // return $show_subcategory;
        $subcategorys = DB::table('db_subcategory')->where('category_id',$category_id)->orderBy('id', 'desc')->where('is_advertise', '1')->select('is_advertise','advertise_image')->limit(6)->get();
        // return $show_subcategory;
        return view('product.subcategory',compact('show_subcategory','subcategorys','cat'));

    }

    public function childCategory($category_id,$subcategory_id)
    {
        $show_childcategory = ChildCategory::where('subcategory_id',$subcategory_id)->get();
        $sub_cat=SubCategory::find($subcategory_id);
        $cat=Category::find($category_id);
        $child_advertise = DB::table('db_childcategory')->where('subcategory_id',$subcategory_id)->orderBy('id', 'desc')->where('is_advertise', '1')->select('is_advertise','advertise_image')->limit(6)->get();
        return view('product.childcategory',compact('show_childcategory','child_advertise','cat','sub_cat'));

    }

    public function childCategoryProductList($category_id,$subcategory_id=false,$childcategory_id=false)
    {
        $child_cat=$sub_cat=false;
        $product =Product::select('id','item_name','sales_price','web_price','item_image','is_feature');
        if($childcategory_id){
            $product =$product->where('childcategory_id', $childcategory_id);
            $child_cat=ChildCategory::find($childcategory_id);
            $sub_cat=SubCategory::find($subcategory_id);
        }else if($subcategory_id){
            $product =$product->where('subcategory_id', $subcategory_id);
            $sub_cat=SubCategory::find($subcategory_id);
        }else{
            $product =$product->where('category_id', $category_id);
        }

        $cat=Category::find($category_id);
        $product =$product->paginate(12);

        // return $product;
        return view('product.childcatproduct',compact('product','cat','sub_cat','child_cat'));
    }
}