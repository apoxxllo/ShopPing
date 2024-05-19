<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function productDetails($id)
    {
        $product = Product::find($id);
        $featured = Product::where('shop_id', $product->shop_id)
        ->where('id', '!=', $product->id)
        ->get();
        $categories = Category::all();

        $cartCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $cartCount = Cart::where('user_id', $user->id)->count();
        }

        return view('Product.productDetails', compact('cartCount'))->with('product', $product)->with('featured', $featured)->with('categories', $categories);
    }

    public function editProduct()
    {
        if (Gate::allows('edit-product', $product)) {
            // User is authorized to edit the product
            return view('products.edit', compact('product'));
        } else {
            // User is not authorized to edit the product
            abort(403, 'Unauthorized action.');
        }
    }
    public function addProduct($id)
    {
        $shop = Shop::find($id);
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        return view('Product.addProduct', compact('cartCount'))->with('shop', $shop)->with('categories', $categories);
    }

    public function addProductPost(Request $request, $id)
    {
        $shop = Shop::find($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:1|',
            'category' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $product = Product::create([
            'name' => $validatedData['name'],
            'stock' => $validatedData['stock'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'category_id' => $validatedData['category'],
            'shop_id' => $id
        ]);

        $productImagesDir = public_path('img/productImages');
        if (!file_exists($productImagesDir)) {
            mkdir($productImagesDir, 0777, true);
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique filename
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

                // Store the image in the public/img/productImages directory
                $image->move($productImagesDir, $filename);

                // Save image path in ProductImage table
                ProductImage::create([
                    'product_id' => $product->id,
                    'imagePath' => 'img/productImages/' . $filename,
                ]);
            }
        }

        // return redirect('productDetails', $product->id);
        return redirect()->route('viewYourShop', $id)->with('success', 'Product created successfully!');

    }
}
