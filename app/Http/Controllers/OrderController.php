<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItems;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Orders;



class OrderController extends Controller
{
    public function saveOrder(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address1' => 'required|string',
            'address2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'payment_method' => 'required|string',
            'captcha' => 'required|string',
        ]);

        // Calculate total price
        $cartItems = Cart::where('user_id', Auth::id())->get();
        $grandTotal = 0;
        foreach ($cartItems as $item) {
            $totalItemPrice = $item->prod_price * $item->prod_qty;
            $grandTotal += $totalItemPrice;
        }

        // Create the order with calculated total price
        $order = new Orders();
        $order->user_id = Auth::id();
        $order->title = $request->title;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address1 = $request->address1;
        $order->address2 = $request->address2;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->zip = $request->zip;
        $order->country = $request->country;
        $order->payment_method = $request->payment_method;
        $order->captcha = $request->captcha;
        $order->status = 0;
        $order->total = $grandTotal;

        $order->save();

        // Create order items and update product quantities
        foreach ($cartItems as $item) {
            if ($item->prod_id !== null) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'prod_id' => $item->prod_id,
                    'qty' => $item->prod_qty,
                    'price' => optional($item->product)->selling_price ?? 0,
                ]);
            } else {
                // Handle the case when prod_id is null
            }


            $product = Product::where('id', $item->prod_id)->first();
            if ($product) {
                $product->qty -= $item->prod_qty;
                $product->save();
            }
        }

        // Update user details if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            // $user->title = $request->title;
            $user->name = $request->name;
            // $user->phone = $request->phone;
            // $user->address1 = $request->address1;
            // $user->address2 = $request->address2;
            // $user->city = $request->city;
            // $user->state = $request->state;
            // $user->zip = $request->zip;
            // $user->country = $request->country;
            $user->save();
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', "Order Placed Successfully");

        }
}
