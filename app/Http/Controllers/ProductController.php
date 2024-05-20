<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Notification;
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
        $notificationsCount = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $cartCount = Cart::where('user_id', $user->id)->count();
            $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        }

        return view('Product.productDetails', compact('cartCount', 'notificationsCount'))->with('product', $product)->with('featured', $featured)->with('categories', $categories);
    }

    public function updateProduct(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'category' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Find the product by ID
            $product = Product::findOrFail($request->input('productId'));

            // Update product details
            $product->name = $validatedData['name'];
            $product->stock = $validatedData['stock'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->category_id = $validatedData['category'];

            // Save the updated product details to the database
            $product->save();

            // Handle image upload if images are provided
            if ($request->hasFile('images')) {
                // Define the directory to store the images
                $productImagesDir = public_path('img/productImages');

                // Get all existing images for the product
                $existingImages = $product->images;

                // Delete old images from storage and database
                foreach ($existingImages as $existingImage) {
                    if (File::exists(public_path($existingImage->imagePath))) {
                        File::delete(public_path($existingImage->imagePath));
                    }
                    $existingImage->delete();
                }

                // Add new images
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

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function addProduct($id)
    {
        $shop = Shop::find($id);
        $categories = Category::all();
        $user = User::findOrFail(Auth::user()->id);
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return view('Product.addProduct', compact('cartCount', 'notificationsCount'))->with('shop', $shop)->with('categories', $categories);
    }

    public function addProductFromManage(Request $request)
    {
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
            'shop_id' => $request->input('shopId')
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

        return redirect()->back()->with('success', 'Product created successfully!');
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
