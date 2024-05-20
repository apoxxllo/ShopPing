<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function setupSeller(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'shopName' => 'required|string|max:255',
            'shopLogo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);
        // Store the shop logo if provided
        $shopLogoPath = null;
        if ($request->hasFile('shopLogo')) {
            $shopLogoPath = $request->file('shopLogo')->store('public/shop-logos');
            // Get the actual file path
            $shopLogoPath = str_replace('public/', 'storage/', $shopLogoPath);
        }

        // Create a new shop record in the database
        $shop = new Shop([
            'shopName' => $validatedData['shopName'],
            'shopLogo' => $shopLogoPath,
            'address' => $validatedData['address'],
            'description' => $validatedData['description']
        ]);

        // Associate the shop with the authenticated user
        $user = Auth::user();
        $user->shops()->save($shop);
        // Redirect the user
        return redirect()->route('yourShops')->with('success', 'Shop setup completed successfully!');
    }
    public function viewShop($id)
    {
        $shop = Shop::find($id);
        $categories = Category::all();
        $products = Product::where('shop_id', $id)->get();
        $popularProducts = Product::where('shop_id', $id)
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(3)
        ->get();

        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return view('viewShop', compact('notificationsCount', 'cartCount', 'popularProducts'))->with('shop', $shop)->with('categories', $categories)->with('products', $products);
    }
    public function viewYourShop($id)
    {
        $user = Auth::user();
        $shop = Shop::find($id);
        if($shop == null)
        {
            return redirect(route('shops'))->with('error', 'Shop does not exist!');
        }
        if($shop->user_id != $user->id)
        {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        // print_r($shop);
        $shop = Shop::find($id);
        $products = Product::where('shop_id', $id)->get();
        $categories = Category::all();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $popularProducts = Product::where('shop_id', $id)
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(3)
        ->get();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();


        return view('viewYourShop', compact('notificationsCount','cartCount', 'popularProducts'))->with('shop', $shop)->with('products', $products)->with('categories', $categories);
    }
    public function manageProducts($id)
    {
        $shop = Shop::findOrFail($id);
        $user = Auth::user();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $categories = Category::all();
        $products = Product::where('shop_id', $id)->withCount('orders')->get();

        return view('manageProducts', compact('products','shop','notificationsCount', 'cartCount', 'categories'));
    }
}
