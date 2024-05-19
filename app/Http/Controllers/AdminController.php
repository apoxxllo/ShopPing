<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('adminDashboard');
    }

    public function products()
    {
        $products = Product::all();
        $shops = Shop::all();
        $categories = Category::all();
        return view('productsAdmin', compact('products', 'categories', 'shops'));
    }

    public function categories()
    {
        $categories = Category::all();
        return view('categoriesAdmin', compact('categories'));
    }

    public function shops()
    {
        $shops = Shop::all();
        return view('shopsAdmin', compact('shops'));
    }

    public function users()
    {
        $users = User::all();
        return view('usersAdmin', compact('users'));
    }


    // CRUD CATEGORY
    public function addCategory(Request $request)
    {
        // Validate the form input, including the image file
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'imagePath' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Customize the validation rules as needed
        ]);

        // Handle the file upload
        if ($request->hasFile('categoryImage')) {
            $image = $request->file('categoryImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'img/categoryImage/' . $imageName;
            $image->move(public_path('img/categoryImage'), $imageName);
        } else {
            $imagePath = null;
        }

        $category = new Category();
        if($imagePath != null)
        {
            $category->imagePath = $imagePath;
        }
        $category->categoryName = $request->input('categoryName');
        // $category->description = $request->input('description');
        $category->save();

        // Redirect or return a response
        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function deleteCategory(Request $request)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($request->input('categoryId'));

            // Delete the category
            $category->delete();

            // Optionally, you can return a success message or redirect to a success page
            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            // Handle any errors, such as the category not found
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    public function updateCategory(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'categoryName' => 'required|string|max:255',
            'categoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Find the category by ID
            $category = Category::findOrFail($request->input('categoryId'));

            // Handle file upload if an image is provided
            if ($request->hasFile('categoryImage')) {
                $image = $request->file('categoryImage');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'img/categoryImage/' . $imageName;
                $image->move(public_path('img/categoryImage'), $imageName);

                // Delete the old image if it exists
                // if ($category->imagePath && File::exists(public_path($category->imagePath))) {
                //     File::delete(public_path($category->imagePath));
                // }

                // Update the category's image path
                $category->imagePath = $imagePath;
            }

            // Update the category details
            $category->categoryName = $validatedData['categoryName'];

            // Save the updated category details to the database
            $category->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }


    // CRUD PRODUCT
    public function addProductAdmin(Request $request)
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
            'shop_id' => $request->input('shop')
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

    public function updateProductAdmin(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'category' => 'required|exists:categories,id',
            'shop' => 'required|exists:shops,id',
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

    public function deleteProduct(Request $request)
    {
        try {
            // Find the category by ID
            $product = Product::findOrFail($request->input('productId'));

            // Delete the category
            $product->delete();

            // Optionally, you can return a success message or redirect to a success page
            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            // Handle any errors, such as the category not found
            return redirect()->back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    // CRUD USERS
    public function addUser(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'firstName' => ['string', 'max:255'],
            'lastName' => ['string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstName' =>$request->firstName,
            'lastName' =>$request->lastName
        ]);

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function deleteUser(Request $request)
    {
        try {
            // Find the category by ID
            $user = User::findOrFail($request->input('userId'));

            // Delete the category
            $user->delete();

            // Optionally, you can return a success message or redirect to a success page
            return redirect()->back()->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            // Handle any errors, such as the category not found
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->input('userId'));
            // Validate incoming request data

            $request->validate([
                'username' => ['required','string','max:255', Rule::unique('users')->ignore($user->id)],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255']
            ]);
            // Update the category details
            $user->username = $request['username'];
            $user->email = $request['email'];
            $user->lastName = $request['lastName'];
            $user->firstName = $request['firstName'];

            // Save the updated category details to the database
            $user->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }


    // RUD SHOP
    public function updateShop(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'shopName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:2048'
        ]);

        try {
            // Find the category by ID
            $shop = Shop::findOrFail($request->input('shopId'));

            // Update the category details
            $shop->shopName = $validatedData['shopName'];
            $shop->address = $validatedData['address'];
            $shop->description = $validatedData['description'];

            // Save the updated category details to the database
            $shop->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Shop updated successfully!');

        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Failed to update shop: ' . $e->getMessage());
        }
    }

    public function deleteShop(Request $request)
    {
        try {
            // Find the category by ID
            $shop = Shop::findOrFail($request->input('shopId'));

            // Delete the category
            $shop->delete();

            // Optionally, you can return a success message or redirect to a success page
            return redirect()->back()->with('success', 'Shop deleted successfully!');
        } catch (\Exception $e) {
            // Handle any errors, such as the category not found
            return redirect()->back()->with('error', 'Failed to delete shop: ' . $e->getMessage());
        }
    }
}
