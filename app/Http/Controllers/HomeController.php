<?php

namespace App\Http\Controllers;

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

        return view('landing')->with('shops', $shops)->with('categories', $categories)->with('products', $products)->with('recentProducts', $recentProducts)->with('featured', $featuredProducts);
    }
    public function shops(){
        $shops = Shop::all();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        return view('shops')->with('shops', $shops)->with('categories', $categories)->with('featuredProducts', $featuredProducts)->with('recentProducts', $recentProducts);
    }
    public function yourShops(){
        $user = User::findOrFail(Auth::user()->id);
        $shops = $user->shops()->get();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $allShops = Shop::all();

        return view('yourShops', compact('allShops','user', 'shops', 'recentProducts', 'featuredProducts'))->with('categories', $categories);
    }
    public function contact(){
        $categories = Category::withCount('products')->get();
        return view('contact')->with('categories', $categories);
    }
    public function cart(){
        $categories = Category::all();
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id);
        return view('cart')->with('categories', $categories);
    }
    public function checkout(){
        $categories = Category::all();
        return view('checkout')->with('categories', $categories);
    }
    public function setupSeller(){
        $categories = Category::all();
        return view('setupSeller')->with('categories', $categories);
    }

    public function viewCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->get();
        $categories = Category::all();
        $recentProducts = Product::where('category_id', $id)->latest()->take(4)->get();
        $featured = Product::where('category_id', $id)->inRandomOrder()->limit(4)->get();
        $shops = Shop::all();
        return view('viewCategory', compact('shops','category', 'products', 'categories', 'recentProducts', 'featured'));
    }
}
