<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query();

        if ($request->filled('keyword')) {
            $kw = $request->string('keyword');
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('email', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->string('status') === 'active') {
                $q->where('is_active', true);
            } elseif ($request->string('status') === 'inactive') {
                $q->where('is_active', false);
            }
        }

        $users = $q->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('addresses', 'defaultAddress');

        // 最近 5 张订单（根据你自己的关联/字段名微调）
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->withCount('items')   // 如果你有 items 关联
            ->take(7)
            ->get();

        return view('admin.users.show', compact('user', 'recentOrders'));
    }

    public function edit(User $user)
    {
        $user->load('addresses');

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password'  => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->is_active = (bool) ($data['is_active'] ?? false);

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }
}
