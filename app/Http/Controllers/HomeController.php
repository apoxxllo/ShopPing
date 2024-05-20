<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\FavoriteShop;
use App\Models\Notification;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use App\Models\FavoriteProduct;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function landing(){
        $categories = Category::withCount('products')->get();
        $products = Product::all();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $shops = Shop::all();
        $cartCount = 0;
        $notificationsCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
            $cartCount = Cart::where('user_id', $user->id)->count();
        }
        return view('landing', compact('notificationsCount'))->with('cartCount', $cartCount)->with('shops', $shops)->with('categories', $categories)->with('products', $products)->with('recentProducts', $recentProducts)->with('featured', $featuredProducts);
    }
    public function shops(){
        $shops = Shop::all();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $cartCount = 0;
        $notificationsCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
            $cartCount = Cart::where('user_id', $user->id)->count();
        }
        return view('shops', compact('notificationsCount'))->with('cartCount', $cartCount)->with('shops', $shops)->with('categories', $categories)->with('featuredProducts', $featuredProducts)->with('recentProducts', $recentProducts);
    }
    public function yourShops(){
        $user = User::findOrFail(Auth::user()->id);
        $shops = $user->shops()->get();
        $categories = Category::withCount('products')->get();
        $recentProducts = Product::latest()->take(4)->get();
        $featuredProducts = Product::inRandomOrder()->limit(4)->get();
        $allShops = Shop::all();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return view('yourShops', compact('notificationsCount','cartCount','allShops','user', 'shops', 'recentProducts', 'featuredProducts'))->with('categories', $categories);
    }
    public function contact(){
        $categories = Category::withCount('products')->get();
        $cartCount = 0;
        $notificationsCount = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $cartCount = Cart::where('user_id', $user->id)->count();
            $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        }
        return view('contact', compact('cartCount', 'notificationsCount'))->with('categories', $categories);
    }
    public function cart(){
        $categories = Category::all();
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        $total = 0;
        foreach ($cart as $item) {
            $total = $total + ($item->product->price * $item->quantity);
        }
        return view('cart', compact('cartCount', 'notificationsCount'))->with('cart', $cart)->with('categories', $categories)->with('total', $total);
    }
    public function checkout(){
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        if($cartCount == 0)
        {
            return redirect(route('cart'))->with('error', 'You have no items in your cart yet! Shop now!');
        }
        $total = 0;
        foreach ($cart as $item) {
            $total = $total + ($item->product->price * $item->quantity);
        }

        return view('checkout', compact('cartCount', 'cart', 'total', 'notificationsCount'))->with('categories', $categories);
    }

    public function orderHistory()
    {
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $orders = Order::where('user_id', $user->id)->get();

        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        // Extract order numbers from the orders collection
        $orderNumbers = $orders->pluck('orderNumber');
        $orderHistoriesData = [];
        foreach($orders as $order)
        {
            $orderedProducts = OrderedProduct::where('orderNumber', $order->orderNumber)->get();
            $orderHistory = new OrderHistory(
                $order['orderNumber'],
                $orderedProducts,
                $order['status'],
                $order['estimateDate'],
                $order['total'],
                $order['id']
            );
            $orderHistoriesData[] = $orderHistory;
        }

        $orderHistoriesData = new Paginator($orderHistoriesData, 10);
        return view('orderHistory', compact('notificationsCount','categories', 'user', 'cartCount', 'orderHistoriesData'));
    }

    public function setupSeller(){
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        if(!Auth::user()->shops->isEmpty())
        {
            return redirect()->back()->with('error', 'You already have a shop! Subscribe to premium to have more than one shop!');
        }
        return view('setupSeller', compact('cartCount', 'notificationsCount'))->with('categories', $categories);
    }

    public function viewCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->withCount('orders')->get();
        $categories = Category::all();
        $recentProducts = Product::where('category_id', $id)->latest()->take(4)->get();
        $featured = Product::where('category_id', $id)->inRandomOrder()->limit(4)->get();
        $shops = Shop::all();

        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return view('viewCategory', compact('notificationsCount','cartCount','shops','category', 'products', 'categories', 'recentProducts', 'featured'));
    }

    public function notifications()
    {
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $unreadNotifications = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->paginate(5);
        $readNotifications = Notification::where('toUser_id', $user->id)->where('status', 'READ')->get();
        foreach($unreadNotifications as $notif)
        {
            $notif->status = 'READ';
            $notif->save();
        }
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return view('notifications', compact('notificationsCount','cartCount', 'categories', 'unreadNotifications', 'readNotifications'));
    }

    public function favorites()
    {
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $favoriteProducts = FavoriteProduct::where('user_id', $user->id)->get();
        $favoriteShops = FavoriteShop::where('user_id', $user->id)->get();
        // dd($favoriteProducts);
        return view('favorites', compact('favoriteProducts', 'favoriteShops','notificationsCount','cartCount', 'categories'));
    }

    public function favoriteProduct($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        $alreadyFavorite = FavoriteProduct::where('user_id', $user->id)->where('product_id', $id)->first();
        if($alreadyFavorite != null)
        {
            $alreadyFavorite->delete();
            return redirect()->back()->with('success', 'You have removed the product ' . $product->name . ' from your favorites!');
        }
        $favorite = FavoriteProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        return redirect()->back()->with('success', 'You have added the product ' . $product->name . ' to your favorites!');
    }
}
