<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Rules\PhoneRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', new PhoneRule(), 'string', 'unique:users,phone,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($request->only(['name', 'phone', 'email', 'address']));

        return back()->with('success', 'Профиль успешно обновлен');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Auth::user()->orders()->with('items.productVariation.product')->findOrFail($id);
        return view('profile.order', compact('order'));
    }
}
