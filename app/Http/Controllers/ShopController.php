<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ShopReviews;
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
        $products = Product::where('shop_id', $id)->withCount('reviews')->get();
        $popularProducts = Product::where('shop_id', $id)
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(3)
        ->get();

        $reviews = ShopReviews::with('user')->where('shop_id', $id)->paginate(10);
        // dd($reviews);
        $reviewsCount = ShopReviews::with('user')->where('shop_id', $id)->count();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $favoriteProductsCount = User::where('id', $user->id)->withCount('favoriteProducts')->first();
        $favoriteShopsCount = User::where('id', $user->id)->withCount('favoriteShops')->first();
        $favoritesCount = $favoriteProductsCount->favorite_products_count + $favoriteShopsCount->favorite_shops_count;
        return view('viewShop', compact('reviewsCount','reviews','favoritesCount','notificationsCount', 'cartCount', 'popularProducts'))->with('shop', $shop)->with('categories', $categories)->with('products', $products);
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
        $favoriteProductsCount = User::where('id', $user->id)->withCount('favoriteProducts')->first();
        $favoriteShopsCount = User::where('id', $user->id)->withCount('favoriteShops')->first();
        $favoritesCount = $favoriteProductsCount->favorite_products_count + $favoriteShopsCount->favorite_shops_count;
        $reviews = ShopReviews::with('user')->where('shop_id', $id)->paginate(10);
        $reviewsCount = ShopReviews::with('user')->where('shop_id', $id)->count();

        return view('viewYourShop', compact('reviews','reviewsCount','favoritesCount','notificationsCount','cartCount', 'popularProducts'))->with('shop', $shop)->with('products', $products)->with('categories', $categories);
    }
    public function editShop($id)
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
        $categories = Category::all();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $favoriteProductsCount = User::where('id', $user->id)->withCount('favoriteProducts')->first();
        $favoriteShopsCount = User::where('id', $user->id)->withCount('favoriteShops')->first();
        $favoritesCount = $favoriteProductsCount->favorite_products_count + $favoriteShopsCount->favorite_shops_count;

        return view('editShop', compact('favoritesCount','notificationsCount','cartCount'))->with('shop', $shop)->with('categories', $categories);
    }

    public function editShopPost(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::find($request->input('shopId'));
        if($shop == null)
        {
            return redirect(route('shops'))->with('error', 'Shop does not exist!');
        }
        if($shop->user_id != $user->id)
        {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        $validatedData = $request->validate([
            'shopName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'shopLogo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string|max:2048'
        ]);

        try {
            // Find the category by ID
            $shop = Shop::findOrFail($request->input('shopId'));

            // Update the category details
            $shop->shopName = $validatedData['shopName'];
            $shop->address = $validatedData['address'];
            $shop->description = $validatedData['description'];

            $shopLogoPath = $shop->shopLogo;
            if ($request->hasFile('shopLogo')) {
                $shopLogoPath = $request->file('shopLogo')->store('public/shop-logos');
                // Get the actual file path
                $shopLogoPath = str_replace('public/', 'storage/', $shopLogoPath);
            }
            $shop->shopLogo = $shopLogoPath;
            // Save the updated category details to the database
            $shop->save();

            // Redirect back with a success message
            return redirect(route('viewYourShop', ['id' => $shop->id]))->with('success', 'Shop updated successfully!');

        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Failed to update shop: ' . $e->getMessage());
        }
    }

    public function manageProducts($id)
    {
        $shop = Shop::findOrFail($id);
        $user = Auth::user();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $categories = Category::all();
        $products = Product::where('shop_id', $id)->withCount('orders')->get();
        $favoriteProductsCount = User::where('id', $user->id)->withCount('favoriteProducts')->first();
        $favoriteShopsCount = User::where('id', $user->id)->withCount('favoriteShops')->first();
        $favoritesCount = $favoriteProductsCount->favorite_products_count + $favoriteShopsCount->favorite_shops_count;
        return view('manageProducts', compact('favoritesCount','products','shop','notificationsCount', 'cartCount', 'categories'));
    }

    public function reviewShop(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'star' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
            'email' => 'required|string|email|max:255'
        ]);
        $user = Auth::user();
        $review = ShopReviews::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'comment' => $validatedData['comment'],
            'rating' => $validatedData['star']
        ]);

        return redirect()->back()->with('success', 'Successfully reviewed this shop!');
    }
}
