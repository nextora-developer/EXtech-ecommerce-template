<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class AdminAddressController extends Controller
{
    public function create(User $user)
    {
        $address = new UserAddress();

        return view('admin.users.address-form', [
            'user'    => $user,
            'address' => $address,
        ]);
    }

    public function store(Request $request, User $user)
    {
        $data = $this->validateData($request);

        $address = $user->addresses()->create($data);

        // 如果是第一个，自动设为默认
        if (!$user->addresses()->where('is_default', true)->exists()) {
            $address->is_default = true;
            $address->save();
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Address added.');
    }

    public function edit(UserAddress $address)
    {
        $user = $address->user;

        return view('admin.users.address-form', [
            'user'    => $user,
            'address' => $address,
        ]);
    }

    public function update(Request $request, UserAddress $address)
    {
        $data = $this->validateData($request);
        $address->update($data);

        return redirect()
            ->route('admin.users.edit', $address->user)
            ->with('success', 'Address updated.');
    }

    public function destroy(UserAddress $address)
    {
        $user = $address->user;
        $address->delete();

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Address deleted.');
    }

    public function makeDefault(UserAddress $address)
    {
        $user = $address->user;

        // 其他地址取消默认
        $user->addresses()->update(['is_default' => false]);

        $address->is_default = true;
        $address->save();

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Default address updated.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', 'max:50'],
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:100'],
            'postcode'       => ['required', 'string', 'max:20'],
            'country'        => ['required', 'string', 'max:100'],
        ]);
    }
}
