<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Billing;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\StockEntry;
use App\Models\Shippingcharge;
use App\Models\CustomerAuth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Support\Facades\Crypt;
use Exception;

class CheckoutController extends Controller
{
    public function checkoutPage()
    {
        $carts=Cart::content();
        $total_price=Cart::subtotal();
        $districts=District::select('id','name','bn_name')->get();
        return view('product.checkout',compact('carts','total_price','districts'));
    }

    public function loadUpazillaAjax($district_id)
    {
        $upazilas=Upazila::where('district_id',$district_id)->select('id','name')->get();
        return response()->json($upazilas,200);
    }
    public function ShippingAjax($district_id)
    {
          $total_price=str_replace(",", "", Cart::subtotal());
        // $shipcheck=Shippingcharge::where('district_id',$district_id);
        $shippingcharge=Shippingcharge::where('district_id',$district_id);
        if($shippingcharge->count() >0 && $total_price<=10000)
            $shippingcharge= $shippingcharge->pluck('shipping_charge')[0];
        else
            $shippingcharge=0;


        if (Session::has('coupon')){
            $return="
            <tr>
                <td>Subtotal</td>
                <td> ".Cart::subtotal()." BDT</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td> (-) ". Session::get('coupon')['discount'] ." BDT</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td id='shipping_charge'>"."(+) ".$shippingcharge." BDT</td>
            </tr>
            <tr>
                <td>Total</td>
                <td> ". (Session::get('coupon')['balance'])+$shippingcharge ." TK<del class='text-danger'> ৳". (Session::get('coupon')['cart_total']+$shippingcharge) ." TK</del></td>
            </tr>
            <tr>
            <td>
                <input id='delivery' name='payment_method' value='1' type='checkbox'>
                <label for='delivery'>Cash on Delivery</label>
            </td>
        </tr>";
        }else{
            $return="<tr>
                        <td>Subtotal</td>
                        <td> ".Cart::subtotal()." BDT</td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td id='shipping_charge'>"."(+) ".$shippingcharge." BDT</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td> ".(str_replace(",", "", Cart::subtotal())+$shippingcharge)." BDT</td>
                    </tr>
                    <tr>
                    <td>
                        <input id='delivery' name='payment_method' value='COD' type='checkbox'>
                        <label for='delivery'>Cash on Delivery</label>
                    </td>
                </tr>";
        }

        session_start();
        unset($_SESSION['s_charge']);
        $_SESSION['s_charge']=$shippingcharge;
        return response()->json($return,200);
    }


    public function placeOrder(PlaceOrderRequest $request)
    {
        session_start();
        $shippingcharge= isset($_SESSION['s_charge'])?$_SESSION['s_charge']:0;
        if(Session::get('coupon'))
            $cuponbalance=Session::get('coupon')['balance'];
        else
            $cuponbalance=str_replace(",", "", Cart::subtotal());

        // die();
        // dd($request->all());
        DB::beginTransaction();
        try {
            $billing = new Billing;
            $billing->name=$request->full_name;
            $billing->email=$request->email;
            $billing->phone_number=$request->contact;
            $billing->district_id=$request->district_id;
            $billing->upazila_id=$request->upazila_id;
            $billing->address=$request->shipping_address;
            $billing->order_notes=$request->order_note;
            $billing->payment_method=$request->payment_method;
            $billing->save();
            if($billing->save()){
                $customer = new CustomerAuth;
                $customer->customer_name=$request->full_name;
                $customer->mobile=$request->contact;
                // $customer->address=$request->address;
                // $customer->email=$request->email;
                $customer->image='avater.jpg';
                $customer->password=Crypt::encryptString($request->contact);
                //dd($customer);
                if($customer->save()){
                    $order=new Order;
                    $order->user_id=$customer->id;
                    $order->billing_id=$billing->id;
                    $order->invoice_no="AB_".str_pad($billing->id,8,"0",STR_PAD_LEFT);
                    $order->sub_total=Session::get('coupon')['cart_total']??str_replace(",", "", Cart::subtotal());
                    $order->discount_amount=Session::get('coupon')['discount']?? 0;
                    $order->cupon_code=Session::get('coupon')['cupon_code']?? '';
                    $order->shipping_charge=$shippingcharge?? 0;
                    $order->total=$cuponbalance+$shippingcharge??str_replace(",", "", Cart::subtotal())+$shippingcharge;
                    $order->status=0;
                    //dd($order);
                    if($order->save()){
                        //Order details table data insert using cart_items helpers
                        foreach(Cart::content() as $cart_item) {
                            $orderdetails= new OrderDetails;
                            $orderdetails->order_id=$order->id;
                            // $orderdetails->user_id=$request->session()->get('userId');
                            $orderdetails->user_id=$customer->id;
                            $orderdetails->product_id=$cart_item->id;
                            $orderdetails->product_qty=$cart_item->qty;
                            $orderdetails->product_price=$cart_item->price;
                            // StockEntry::findOrFail($cart_item->id)->decrement('qty', $cart_item->qty);
                            // DB::table('db_stockentry')->findOrFail($cart_item->id)->decrement('qty', $cart_item->qty);
                            if($orderdetails->save()){
                                Cart::destroy();
                                Session::forget('coupon');
                                Session::forget('shipping');
    
                                
                            }else{
                                return back();
                            }
                        }
                         DB::commit();
                       // Toastr::success('Your Order placed successfully!!!!','Success');
                                return view('product.order-complete-message');
                        
                        //return redirect()->route('home');
                        
                    }else{
                        return back();
                    }
                    return back();
                }else{
                    return redirect()->back()->with('please try again');
                }
            }
           
        }catch(Exception $e){
            DB::rollback();
            dd($e);
        }
    }
}
