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

        $states = [
            ['name' => 'Johor',           'zone' => 'west_my'],
            ['name' => 'Kedah',           'zone' => 'west_my'],
            ['name' => 'Kelantan',        'zone' => 'west_my'],
            ['name' => 'Melaka',          'zone' => 'west_my'],
            ['name' => 'Negeri Sembilan', 'zone' => 'west_my'],
            ['name' => 'Pahang',          'zone' => 'west_my'],
            ['name' => 'Perak',           'zone' => 'west_my'],
            ['name' => 'Perlis',          'zone' => 'west_my'],
            ['name' => 'Penang',          'zone' => 'west_my'],
            ['name' => 'Selangor',        'zone' => 'west_my'],
            ['name' => 'Terengganu',      'zone' => 'west_my'],
            ['name' => 'Kuala Lumpur',    'zone' => 'west_my'],
            ['name' => 'Putrajaya',       'zone' => 'west_my'],

            ['name' => 'Sabah',           'zone' => 'east_my'],
            ['name' => 'Sarawak',         'zone' => 'east_my'],
            ['name' => 'Labuan',          'zone' => 'east_my'],
        ];

        return view('admin.users.address-form', [
            'user'    => $user,
            'address' => $address,
            'states'  => $states,
        ]);
    }


    public function store(Request $request, User $user)
    {
        $data = $this->validateData($request);

        // 1️⃣ 先把 checkbox 转成 true/false
        $requestedDefault = $request->boolean('is_default');

        // 2️⃣ 检查这个 user 目前有没有默认地址
        $hasDefault = $user->addresses()
            ->where('is_default', true)
            ->exists();

        // 3️⃣ 如果目前还没有任何默认地址，即使没勾，也自动帮他设为默认
        if (!$hasDefault && !$requestedDefault) {
            $requestedDefault = true;
        }

        // 4️⃣ 如果这条要当默认 → 先把旧的全部清掉
        if ($requestedDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        // 5️⃣ 写回到 data 里
        $data['is_default'] = $requestedDefault;

        // 6️⃣ 创建地址
        $address = $user->addresses()->create($data);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Address added.');
    }

    public function edit(UserAddress $address)
    {
        $user = $address->user;

        $states = [
            ['name' => 'Johor',           'zone' => 'west_my'],
            ['name' => 'Kedah',           'zone' => 'west_my'],
            ['name' => 'Kelantan',        'zone' => 'west_my'],
            ['name' => 'Melaka',          'zone' => 'west_my'],
            ['name' => 'Negeri Sembilan', 'zone' => 'west_my'],
            ['name' => 'Pahang',          'zone' => 'west_my'],
            ['name' => 'Perak',           'zone' => 'west_my'],
            ['name' => 'Perlis',          'zone' => 'west_my'],
            ['name' => 'Penang',          'zone' => 'west_my'],
            ['name' => 'Selangor',        'zone' => 'west_my'],
            ['name' => 'Terengganu',      'zone' => 'west_my'],
            ['name' => 'Kuala Lumpur',    'zone' => 'west_my'],
            ['name' => 'Putrajaya',       'zone' => 'west_my'],

            ['name' => 'Sabah',           'zone' => 'east_my'],
            ['name' => 'Sarawak',         'zone' => 'east_my'],
            ['name' => 'Labuan',          'zone' => 'east_my'],
        ];

        return view('admin.users.address-form', [
            'user'    => $user,
            'address' => $address,
            'states'  => $states,
        ]);
    }

    public function update(Request $request, UserAddress $address)
    {
        $data = $this->validateData($request);

        $user = $address->user; // 这个 address 属于谁

        // 1️⃣ checkbox → true / false
        $requestedDefault = $request->boolean('is_default');

        // 2️⃣ 检查这个 user 目前有没有默认地址
        $hasDefault = $user->addresses()
            ->where('is_default', true)
            ->exists();

        // 3️⃣ 如果目前还没有任何默认地址，而这次也没勾，就自动把这条当默认
        if (!$hasDefault && !$requestedDefault) {
            $requestedDefault = true;
        }

        // 4️⃣ 如果这条要设为默认 → 先把同 user 的其他地址 default 清掉
        if ($requestedDefault) {
            $user->addresses()
                ->where('id', '<>', $address->id)
                ->update(['is_default' => false]);
        }

        // 5️⃣ 写入 data
        $data['is_default'] = $requestedDefault;

        // 6️⃣ 更新地址
        $address->update($data);

        return redirect()
            ->route('admin.users.edit', $user)
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
            'email'          => ['required', 'string', 'email', 'max:255'],
            'address_line1'  => ['required', 'string', 'max:255'],
            'address_line2'  => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:100'],
            'state'          => ['required', 'string', 'max:100'],
            'postcode'       => ['required', 'string', 'max:20'],
            'country'        => ['required', 'string', 'max:100'],
            'is_default'     => ['nullable', 'boolean'], // 👈 加这个

        ]);
    }
}
