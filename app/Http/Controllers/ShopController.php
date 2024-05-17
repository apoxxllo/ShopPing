<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
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
        return view('viewShop')->with('shop', $shop)->with('categories', $categories);
    }
    public function viewYourShop($id)
    {
        $user = User::findOrFail(Auth::user()->id);
        $shop = $user->shops()->where('id', $id)->get();
        if($shop == null){
            return redirect(route('yourShops'))->with('error', 'No shop found');
        }
        // print_r($shop);
        $shop = Shop::find($id);
        $products = Product::where('shop_id', $id)->get();
        $categories = Category::all();

        return view('viewYourShop')->with('shop', $shop)->with('products', $products)->with('categories', $categories);
    }
}
