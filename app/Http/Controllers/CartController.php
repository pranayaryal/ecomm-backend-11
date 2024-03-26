<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function personal(Request $request)
    {
        if (!$request->has('email')) {
            return response()->json(['message' => 'email is required']);
        }
        if (!$request->has('firstName')) {
            return response()->json(['data' => 'firstName is required']);
        }
        if (!$request->has('lastName')) {
            return response()->json(['data' => 'lastName is required']);
        }
        if (!$request->has('phone')) {
            return response()->json(['data' => 'phone is required']);
        }
        $personalDetails = $request->session()->get('personalDetails', []);
        // if (isset($personalDetails['email'])){
        //     return response()->json(['message' => 'email is already set']);
        // }
        // if (isset($personalDetails['first_name'])){
        //     return response()->json(['message' => 'first_name is already set']);
        // }
        // if (isset($personalDetails['last_name'])){
        //     return response()->json(['message' => 'last_name is already set']);
        // }
        $personalDetails['email'] = $request->email;
        $personalDetails['firstName'] = $request->firstName;
        $personalDetails['lastName'] = $request->lastName;
        $personalDetails['phone'] = $request->phone;
        $request->session()->put(['personalDetails' => $personalDetails]);
        return response()->json(['personal_details' => $request->session()->get('personalDetails')]);

    }

    public function getPersonal(Request $request)
    {
        $personalDetails = $request->session()->get('personalDetails', []);
        return response()->json(['personal_details' => $personalDetails]);

    }

    public function saveAddress(Request $request)
    {
        if (!$request->has('street')) {
            return response()->json(['message' => 'street is required']);
        }
        if (!$request->has('addressType')) {
            return response()->json(['message' => 'address-type is required']);
        }
        if (!$request->has('zip')) {
            return response()->json(['data' => 'zip is required']);
        }
        if (!$request->has('state')) {
            return response()->json(['data' => 'state is required']);
        }
        if (!$request->has('city')) {
            return response()->json(['data' => 'city is required']);
        }
        $session_name = $request->addressType == 'shipping' ? 'shipping-address' : 'address';
        $address = $request->session()->get($session_name, []);
        $address['street'] = $request->street;
        $address['city'] = $request->city;
        $address['zip'] = $request->zip;
        $address['state'] = $request->state;
        $request->session()->put([$session_name => $address]);
        return response()->json(['address' => $request->session()->get($session_name)]);

    }


    public function getAddress(Request $request)
    {
        if (!$request->has('addressType')) {
            return response()->json(['message' => 'addressType is required']);
        }
        $session_name = $request->addressType == 'shipping' ? 'shipping-address' : 'address';
        $address = $request->session()->get($session_name, []);
        return response()->json(['address' => $address]);

    }



    // {id: 2, qty: 3}
    public function increaseCart(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json(['message' => 'product id is required']);
        }
        if (!$request->has('quantity')) {
            return response()->json(['data' => 'quantity is required']);
        }
        if ((int)$request->id <= 0) {
            return response()->json(['data' => 'productId cannot be 0 or less']);
        }
        if ((int)$request->quantity <= 0) {
            return response()->json(['data' => 'quantity cannot be 0 or less']);
        }
        // $request->session()->forget('cart');
        $shoppingCart = $request->session()->get('shoppingCart', []);
        if (isset($shoppingCart[$request->id])){
            $shoppingCart[$request->id]['quantity'] += (int)$request->quantity;
            $request->session()->put(['shoppingCart' => $shoppingCart]);
            return response()->json(['products' => $request->session()->get('shoppingCart')]);
        }
        $shoppingCart[$request->id]['id'] = (int)$request->id;
        $shoppingCart[$request->id]['quantity'] = (int)$request->quantity;
        $request->session()->put(['shoppingCart' => $shoppingCart]);
        return response()->json(['products' => $request->session()->get('shoppingCart')]);


    }

    public function setQuantity(Request $request)
    {
        if (!$request->has('id')) {
            return response()->json(['message' => 'product id is required']);
        }
        if (!$request->has('quantity')) {
            return response()->json(['data' => 'quantity is required']);
        }
        if ((int)$request->id <= 0) {
            return response()->json(['data' => 'productId cannot be 0 or less']);
        }
        if ((int)$request->quantity <= 0) {
            return response()->json(['data' => 'quantity cannot be 0 or less']);
        }
        // $request->session()->forget('cart');
        $shoppingCart = $request->session()->get('shoppingCart', []);
        $shoppingCart[$request->id]['id'] = (int)$request->id;
        $shoppingCart[$request->id]['quantity'] = (int)$request->quantity;
        $request->session()->put(['shoppingCart' => $shoppingCart]);
        return response()->json(['products' => $request->session()->get('shoppingCart')]);


    }

    public function decreaseCart(Request $request)
    {

        //if (!$request->has('id')) {
        if (!$request->has('id') || is_null($request->id)) {
            return response()->json(['message' => 'product id is required']);
        }
        if (!$request->has('quantity') || is_null($request->quantity)) {
            return response()->json(['message' => 'quantity is required']);
        }
        // $request->session()->forget('cart');
        if ($request->id == 0) {
            return response()->json(['message' => 'productId cannot be 0']);
        }

        if ($request->quantity == 0) {
            return response()->json(['message' => 'productId cannot be 0']);
        }

        $shoppingCart = $request->session()->get('shoppingCart', []);

        if(!($shoppingCart)){
            return response()->json(['message' => 'Shopping cart is already empty']);
        }

        if ($shoppingCart[$request->id]['quantity'] < (int)$request->quantity ) {
            return response()->json(['message'=> 'quantity supplied is more than that present']);
        }


        if (isset($shoppingCart[$request->id])){
            $shoppingCart[$request->id]['quantity'] -= (int)$request->quantity;
            if ($shoppingCart[$request->id]['quantity'] <= 0){
                unset($shoppingCart[$request->id]);
            }
            $request->session()->put(['shoppingCart' => $shoppingCart]);
            return response()->json(['products' => $request->session()->get('shoppingCart')]);
        }
        return response()->json(['message' => 'That id ' . $request->id . ' is not present']);


    }

    public function getCartItems(Request $request)
    {
        $shoppingCart = $request->session()->get('shoppingCart', []);
        if (!$shoppingCart){
            return response()->json(['message' => 'shopping cart is not set']);

        }
        return response()->json(['products' => $request->session()->get('shoppingCart')]);

    }

    public function forgetCart(Request $request)
    {
        $request->session()->forget('shoppingCart');
        return response()->json(['message' => 'cart has been erased']);

    }

    public function buildResponseProducts()
    {
        $shoppingCart = session()->get('shoppingCart', []);

    }

    public function removeCartItem(Request $request)
    {
        if (!$request->has('id') || is_null($request->id) || ($request->id == 0)) {
            return response()->json(['message' => 'product id is required']);
        }


        $shoppingCart = $request->session()->get('shoppingCart', []);
        if(!($shoppingCart)){
            return response()->json(['message' => 'Shopping cart is already empty']);
        }
        if (isset($shoppingCart[$request->id])) {
          unset($shoppingCart[$request->id]);
          $request->session()->put('shoppingCart', $shoppingCart);
          return response()->json(['products' => $request->session()->get('shoppingCart')]);
        }
        return response()->json(['message' => 'That id ' . $request->id. ' cannot be found']);

    }

}
