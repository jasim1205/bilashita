<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JsonController extends Controller
{


    public function couponCheck()
    {
        //dd($request->all());
        if (Session::has('coupon')) {
            $cuponcode = Session::get('coupon')['cupon_code'];
        } else {
            return false;
        }
        $check = Coupon::where('cupon_code', $cuponcode)->first();
        // print_r($check->discount);
        // print_r(Cart::subtotal());
        $cartsubtotal = str_replace(",", "", Cart::subtotal());


        //if valid coupon found
        if ($check != null) {
            //check coupon validity
            $check_validity = $check->finish_date > Carbon::now()->format('Y-m-d');
            //if coupon date is not expried
            if ($check_validity && $check->discount_type == 0) {
                Session::put('coupon', [
                    'cupon_code' => $check->cupon_code,
                    'discount' => ($cartsubtotal * $check->discount) / 100,
                    'cart_total' => $cartsubtotal,
                    'balance' => $cartsubtotal - ($cartsubtotal * $check->discount) / 100
                ]);
                return true;
            } else if ($check_validity && $check->discount_type == 1) {
                Session::put('coupon', [
                    'cupon_code' => $check->cupon_code,
                    'discount' => $check->discount,
                    'cart_total' => $cartsubtotal,
                    'balance' => $cartsubtotal - $check->discount
                ]);
                return true;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function addToCart(Request $request)
    {
        try {

            $id = $request->product_id;
            $order_qty = $request->order_qty;
            $product = DB::table('db_items')->where('id', $id)->first();
            Cart::add([
                'id' => $product->id,
                'name' => $product->item_name,
                'price' => $product->web_price,
                'weight' => 0,
                'product_stock' => $product->stock,
                'qty' => $order_qty,
                'options' => [
                    'product_image' => $product->item_image
                ]
            ]);

            $this->couponCheck();
            return response([
                'status' => true,
                'message' => "Product Added to Cart"
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function removeFromCart($cart_id)
    {
        Cart::remove($cart_id);
        $this->couponCheck();

        return response([
            'status' => true,
            'message' => "Product has been removed!"
        ], 200);
    }
}