<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'productId' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($request) {
                $product = Product::find($request->product_id);
                if ($product && $value > $product->stock) {
                    $fail('The quantity must be less than or equal to the product\'s stock.');
                }
            }],
        ]);
        $product = Product::findOrFail($request->input('productId'));
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->where('product_id',$request->productId)->get();

        if($cart->isEmpty())
        {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }
        else
        {
            foreach ($cart as $item) {
                $item->quantity = $request->quantity;
                $item->save();
            }
        }
        return redirect(route('cart'))->with('success', 'Added to your cart successfully!');
    }

    public function addOne(Request $request)
    {
        $request->validate([
            'productId' => ['required', 'exists:products,id']
        ]);
        $product = Product::findOrFail($request->input('productId'));

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('product_id',$request->productId)->get();
        foreach ($cart as $item) {
            $item->quantity += 1;
            if($item->quantity > $product->stock)
            {
                return redirect()->back()->with('error', 'Insufficent stock for ' . $product->name . ' to add one more.');
            }
            $item->save();
        }

        return redirect()->back()->with('success', 'Successfully added one to item ' . $product->name);
    }

    public function deductOne(Request $request)
    {
        $request->validate([
            'productId' => ['required', 'exists:products,id']
        ]);
        $product = Product::findOrFail($request->input('productId'));

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('product_id',$request->productId)->get();
        foreach ($cart as $item) {
            $item->quantity -= 1;
            if($item->quantity == 0)
            {
                $item->delete();
                return redirect()->back()->with('success', 'Successfully deducted one to item ' . $product->name);
            }
            $item->save();
        }

        return redirect()->back()->with('success', 'Successfully deducted one to item ' . $product->name);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'productId' => ['required', 'exists:products,id']
        ]);
        $product = Product::findOrFail($request->input('productId'));

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('product_id',$request->productId)->get();
        foreach ($cart as $item) {
            $item->delete();
        }

        return redirect()->back()->with('success', 'Successfully remvoved item ' . $product->name . ' from your cart');

    }
}
