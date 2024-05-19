<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function landing(){
        $categories = Category::withCount('products')->get();
        $products = Product::all();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $shops = Shop::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('landing')->with('cartCount', $cartCount)->with('shops', $shops)->with('categories', $categories)->with('products', $products)->with('recentProducts', $recentProducts)->with('featured', $featuredProducts);
    }
    public function shops(){
        $shops = Shop::all();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('shops')->with('cartCount', $cartCount)->with('shops', $shops)->with('categories', $categories)->with('featuredProducts', $featuredProducts)->with('recentProducts', $recentProducts);
    }
    public function yourShops(){
        $user = User::findOrFail(Auth::user()->id);
        $shops = $user->shops()->get();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $allShops = Shop::all();
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('yourShops', compact('cartCount','allShops','user', 'shops', 'recentProducts', 'featuredProducts'))->with('categories', $categories);
    }
    public function contact(){
        $categories = Category::withCount('products')->get();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('contact', compact('cartCount'))->with('categories', $categories);
    }
    public function cart(){
        $categories = Category::all();
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $cartCount = Cart::where('user_id', $user->id)->count();

        $total = 0;
        foreach ($cart as $item) {
            $total = $total + ($item->product->price * $item->quantity);
        }
        return view('cart', compact('cartCount'))->with('cart', $cart)->with('categories', $categories)->with('total', $total);
    }
    public function checkout(){
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('checkout', compact('cartCount'))->with('categories', $categories);
    }
    public function setupSeller(){
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        if(!Auth::user()->shops->isEmpty())
        {
            return redirect()->back()->with('error', 'You already have a shop! Subscribe to premium to have more than one shop!');
        }
        return view('setupSeller', compact('cartCount'))->with('categories', $categories);
    }

    public function viewCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->get();
        $categories = Category::all();
        $recentProducts = Product::where('category_id', $id)->latest()->take(4)->get();
        $featured = Product::where('category_id', $id)->inRandomOrder()->limit(4)->get();
        $shops = Shop::all();

        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();

        return view('viewCategory', compact('cartCount','shops','category', 'products', 'categories', 'recentProducts', 'featured'));
    }
}
