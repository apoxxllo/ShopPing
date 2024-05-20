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

        foreach ($cart as $item) {
            $total = $total + ($item->product->price * $item->quantity);
            $details .= $item->product->name . ' x' . $item->quantity . "\n";
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

        return redirect(route('orderHistory'))->with('success', 'Order successfully placed');
    }

    public function pingSeller($id)
    {
        $user = Auth::user();
        $orderProduct = OrderedProduct::where('orderNumber', $id)->first();
        $order = Order::find($id);

        $alreadyPinged = Notification::where('orderNumber', $order->orderNumber)->get();
        if(!$alreadyPinged->isEmpty())
        {
            return redirect()->back()->with('error', 'You have already pinged the seller!');
        }
        // Set the timezone to UTC+8
        $dateTime = new DateTime("now", new DateTimeZone("Asia/Shanghai")); // UTC+8 timezone

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
                    $order1['id']
                );
                $orderHistoriesData[] = $orderHistory;
            }
        }

        $orderHistoriesData = new Paginator($orderHistoriesData, 10);
        return view('manageOrders',compact('notificationsCount','cartCount', 'categories', 'orderHistoriesData'));
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

        Mail::to($order->user->email)->send(new SendOrderMail($body));

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
        return redirect()->back()->with('success', 'Successfully received the order! Enjoy!');
    }

}
