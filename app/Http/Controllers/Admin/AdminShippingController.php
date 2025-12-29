<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;


class AdminShippingController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::orderBy('id')->get();

        return view('admin.shipping.index', compact('rates'));
    }

    public function edit(ShippingRate $rate)
    {
        return view('admin.shipping.form', compact('rate'));
    }

    public function create()
    {
        $rate = new ShippingRate();

        return view('admin.shipping.form', compact('rate'));
    }

    public function update(Request $request, ShippingRate $rate)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'rate' => 'required|numeric|min:0',
        ]);

        $rate->update($data);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping rate updated.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:shipping_rates,code',
            'rate' => 'required|numeric|min:0',
        ]);

        ShippingRate::create($data);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'Shipping rate created.');
    }
}
