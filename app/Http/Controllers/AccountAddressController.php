<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class AccountAddressController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 把默认地址排在最上面
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('account.address.index', compact('user', 'addresses'));
    }

    public function create()
    {
        $user = auth()->user();

        return view('account.address.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'max:30'],
            'email'          => ['required', 'string', 'email', 'max:255'],
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:100'],
            'postcode'       => ['required', 'string', 'max:20'],
            'country'        => ['nullable', 'string', 'max:100'],
            'is_default'     => ['nullable', 'boolean'],
        ]);

        // 默认国家 Malaysia
        if (empty($data['country'])) {
            $data['country'] = 'Malaysia';
        }

        // 如果勾选 is_default，把其他地址 default 取消
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $data['user_id'] = $user->id;
        $data['is_default'] = $isDefault;

        UserAddress::create($data);

        return redirect()
            ->route('account.address.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(UserAddress $address)
    {
        $user = auth()->user();

        // 确保是自己的地址
        if ($address->user_id !== $user->id) {
            abort(404);
        }

        return view('account.address.edit', compact('user', 'address'));
    }

    public function update(Request $request, UserAddress $address)
    {
        $user = auth()->user();

        if ($address->user_id !== $user->id) {
            abort(404);
        }

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'max:30'],
            'email'          => ['required', 'string', 'email', 'max:255'],
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:100'],
            'postcode'       => ['required', 'string', 'max:20'],
            'country'        => ['nullable', 'string', 'max:100'],
            'is_default'     => ['nullable', 'boolean'],
        ]);

        if (empty($data['country'])) {
            $data['country'] = 'Malaysia';
        }

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            // 把同一个 user 的其它地址取消 default
            $user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $data['is_default'] = $isDefault;

        $address->update($data);

        return redirect()
            ->route('account.address.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(UserAddress $address)
    {
        $user = auth()->user();

        if ($address->user_id !== $user->id) {
            abort(404);
        }

        $address->delete();

        return redirect()
            ->route('account.address.index')
            ->with('success', 'Address deleted successfully.');
    }

    public function setDefault(UserAddress $address)
    {
        $user = auth()->user();

        // 确保是自己的地址
        if ($address->user_id !== $user->id) {
            abort(404);
        }

        // 1. 先把这个 user 的所有地址设成非默认
        $user->addresses()->update(['is_default' => false]);

        // 2. 再把这条地址设为默认
        $address->update(['is_default' => true]);

        return redirect()
            ->route('account.address.index')
            ->with('success', 'Default address updated.');
    }
}
