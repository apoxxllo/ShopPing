<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function editProfile(Request $request): View
    {
        $categories = Category::all();
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();
        return view('editProfile', [
            'user' => $request->user(),
            'categories' => $categories,
            'cartCount' => $cartCount,
            'notificationsCount' => $notificationsCount
        ]);
    }

    public function profile(Request $request){
        $categories = Category::all();
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get();
        $cartCount = Cart::where('user_id', $user->id)->count();
        $notificationsCount = Notification::where('toUser_id', $user->id)->where('status', 'UNREAD')->count();

        return View('profile', [
            'user' => $request->user(),
            'categories' => $categories,
            'cartCount' =>$cartCount,
            'notificationsCount' => $notificationsCount
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->address = $request->address;
        $request->user()->contact = $request->contact;

        $request->user()->save();

        return Redirect::route('profile')->with('success', 'Profile successfully updated!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
