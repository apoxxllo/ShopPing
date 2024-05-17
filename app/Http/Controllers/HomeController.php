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
        $categories = Category::all();
        $products = Product::all();
        $recentProducts = Product::latest()->take(5)->get();

        return view('landing')->with('categories', $categories)->with('products', $products)->with('recentProducts', $recentProducts);
    }
    public function shops(){
        $shops = Shop::all();
        $categories = Category::all();
        return view('shops')->with('shops', $shops)->with('categories', $categories);
    }
    public function yourShops(){
        $user = User::findOrFail(Auth::user()->id);
        $shops = $user->shops()->get();
        $categories = Category::all();
        return view('yourShops', compact('user', 'shops'))->with('categories', $categories);
    }
    public function contact(){
        $categories = Category::all();
        return view('contact')->with('categories', $categories);
    }
    public function cart(){
        $categories = Category::all();
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

}
