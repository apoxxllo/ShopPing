<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Mail\SendOrderMail;
use App\Models\Notification;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use App\Models\OrderedProduct;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validatedData = $request->validate([
            'userId' => ['required', 'exists:users,id'],
            'email' => ['required', 'exists:users,email'],
            'address' => 'required|string|max:2048',
            'firstName' => 'required|string|max:2048',
            'lastName' => 'required|string|max:2048',
            'payment' => 'required|string|max:255',
            'contact' => ['required', 'regex:/^[0-9]{11}$/']
        ]);
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $total = 0;
        $details = '';


        $maxOrderNumber = Order::max('orderNumber');
        $nextOrderNumber = $maxOrderNumber ? $maxOrderNumber + 1 : 1000;
        $shopId = 0;
        foreach ($cart as $item) {
            $total = $total + ($item->product->price * $item->quantity);
            $details .= $item->product->name . ' x' . $item->quantity . "\n";
            $shopId = $item->product->shop->id;
        }


        $order = Order::create([
            'orderNumber' => $nextOrderNumber,
            'user_id' => $validatedData['userId'],
            'total' => $total,
            'address' => $validatedData['address'],
            'payment' => $validatedData['payment'],
            'status' => 'PENDING',
            'details' => $details
        ]);

        foreach ($cart as $item) {
        for($i = 0; $i < $item->quantity; $i++)
            {
                $ordered = OrderedProduct::create([
                    'orderNumber' => $nextOrderNumber,
                    'product_id' => $item->product->id,
                    'order_id' => $order->id
                ]);
            }
            $product = $item->product;
            $product = Product::findOrFail($product->id);
            $product->stock -= $item->quantity;
            $product->save();
            $item->delete();
        }

        $shop = Shop::findOrFail($shopId);
        $dateTime = new DateTime("now", new DateTimeZone("Asia/Shanghai")); // UTC+8 timezone
        $notification = Notification::create([
            'fromUser_id' => $user->id,
            'orderNumber' => $order->orderNumber,
            'toUser_id' => $shop->user->id,
            'created_at' => $dateTime->format('Y-m-d h:i:s a'),
            'description' => 'Customer ' . $user->username . ' has ordered from your shop ' . $shop->shopName . ', Order#' . $order->id . '. Order Details: ' . $order->details
        ]);
        $body = 'Customer ' . $user->username . ' has ordered from your shop, Order#' . $order->id . '. Order Details: ' . $order->details;
        $header = 'A new order in your shop ' . $shop->shopName . ' by Customer ' . $user->username;
        Mail::to($shop->user->email)->send(new SendOrderMail($header, $body));

        $body = 'You have successfully placed your order in shop ' . $shop->shopName . '. Order Details: ' . $order->details . ' Total: P' . number_format($total, 2);
        $header = 'RECEIPT Order#' . $order->orderNumber;
        Mail::to($shop->user->email)->send(new SendOrderMail($header, $body));

        return redirect(route('orderHistory'))->with('success', 'Order successfully placed');
    }

    public function pingSeller($id)
    {
        $user = Auth::user();
        $orderProduct = OrderedProduct::where('orderNumber', $id)->first();
        $order = Order::where('orderNumber', $id)->first();
        // dd($order);
        $alreadyPinged = Notification::where('orderNumber', $order->orderNumber)->count();
        if($alreadyPinged > 1)
        {
            return redirect()->back()->with('error', 'You have already pinged the seller!');
        }
        // Set the timezone to UTC+8
        $dateTime = new DateTime("now", new DateTimeZone("Asia/Shanghai")); // UTC+8 timezone
        // dd($orderProduct);
        $toUserId = $orderProduct->product->shop->user->id;
        $notification = Notification::create([
            'fromUser_id' => $user->id,
            'orderNumber' => $order->orderNumber,
            'toUser_id' => $toUserId,
            'created_at' => $dateTime->format('Y-m-d h:i:s a'),
            'description' => 'Your customer ' . $user->username . ' has pinged their order, Order#' . $order->id . '. Order Details: ' . $order->details
        ]);
        return redirect()->back()->with('success', 'You have successfully pinged your order to the seller!');
    }

    public function manageOrders($id)
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

        $categories = Category::all();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $orders = OrderedProduct::select('orderNumber', 'product_id', 'order_id')
                ->groupBy('orderNumber', 'product_id', 'order_id')->get();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        $favoriteProductsCount = User::where('id', $user->id)->withCount('favoriteProducts')->first();
        $favoriteShopsCount = User::where('id', $user->id)->withCount('favoriteShops')->first();
        $favoritesCount = $favoriteProductsCount->favorite_products_count + $favoriteShopsCount->favorite_shops_count;
        $orderHistoriesData = [];
        foreach($orders as $order)
        {
            if($order->product->shop->id == $id)
            {
                $orderedProducts = OrderedProduct::where('orderNumber', $order->orderNumber)->get();
                $order1 = Order::find($order->order_id);
                $orderHistory = new OrderHistory(
                    $order['orderNumber'],
                    $orderedProducts,
                    $order1['status'],
                    $order1['estimateDate'],
                    $order1['total'],
                    $order1['id'],
                    $orderedProducts->first()->product->shop->shopName
                );
                $orderHistoriesData[] = $orderHistory;
            }
        }

        $orderHistoriesData = new Paginator($orderHistoriesData, 10);
        return view('manageOrders',compact('favoritesCount','notificationsCount','cartCount', 'categories', 'orderHistoriesData'));
    }

    public function sendOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderedProduct = OrderedProduct::where('orderNumber', $id)->first();
        $user = Auth::user();
        // dd($orderedProduct->product->shop->user->id);
        // dd($orderedProduct);
        if($orderedProduct->product->shop->user->id != $user->id)
        {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        $order->status = "ON THE WAY";

        $order->save();
        $dateTime = new DateTime("now", new DateTimeZone("Asia/Shanghai")); // UTC+8 timezone
        $notification = Notification::create([
            'fromUser_id' => $user->id,
            'orderNumber' => $order->orderNumber,
            'toUser_id' => $order->user->id,
            'created_at' => $dateTime->format('Y-m-d h:i:s a'),
            'description' => 'Seller ' . $user->username . ' has delivered your order, Order#' . $order->id . '. Order Details: ' . $order->details
        ]);
        $body = 'Seller ' . $user->username . ' has delivered your order, Order#' . $order->id . '. Order Details: ' . $order->details;
        $header = 'Order #' . $order->id.' has been confirmed by seller ' . $user->username . ' from shop ' . $orderedProduct->product->shop->shopName . ' and is on the way now! Order Details: ' . $order->details;

        Mail::to($order->user->email)->send(new SendOrderMail($header, $body));

        return redirect()->back()->with('success', 'Successfully sent the order for delivery!');
    }

    public function receiveOrder($id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();
        if($order->user_id != $user->id)
        {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }
        $order->status = "RECEIVED";

        $order->save();

        $orderedProduct = OrderedProduct::where('orderNumber', $id)->first();

        $dateTime = new DateTime("now", new DateTimeZone("Asia/Shanghai")); // UTC+8 timezone
        $notification = Notification::create([
            'fromUser_id' => $user->id,
            'orderNumber' => $order->orderNumber,
            'toUser_id' => $orderedProduct->product->shop->user->id,
            'created_at' => $dateTime->format('Y-m-d h:i:s a'),
            'description' => 'Customer ' . $user->username . ' has succesfully received the item from your shop ' . $orderedProduct->product->shop->shopName . ', Order#' . $order->id . '. Order Details: ' . $order->details
        ]);
        $body = 'Customer ' . $user->username . ' has received your order, Order#' . $order->id . '. Order Details: ' . $order->details;
        $header = 'Their payment will automatically be added into your Account. Thank you!';
        Mail::to($orderedProduct->product->shop->user->email)->send(new SendOrderMail($header, $body));
        return redirect()->back()->with('success', 'Successfully received the order! Enjoy!');
    }

}
