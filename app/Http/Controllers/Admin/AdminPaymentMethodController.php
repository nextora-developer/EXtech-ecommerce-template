<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $paymentMethods = PaymentMethod::query()
            ->when(
                $request->keyword,
                fn($q) =>
                $q->where('name', 'like', "%$request->keyword%")
                    ->orWhere('code', 'like', "%$request->keyword%")
                    ->orWhere('bank_name', 'like', "%$request->keyword%")
            )
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $paymentMethod = new PaymentMethod([
            'is_active' => true,
        ]);

        return view('admin.payment-methods.form', compact('paymentMethod'));
    }

    /** Store new payment method */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:100|unique:payment_methods,code',
            'short_description'  => 'nullable|string|max:255',
            'is_active'           => 'nullable|boolean',
            'is_default'          => 'nullable|boolean',
            'bank_name'           => 'nullable|string|max:255',
            'bank_account_name'   => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'instructions'        => 'nullable|string',
            'duitnow_qr'          => 'nullable|image|max:4096', // 4MB
        ]);

        $data['is_active']  = (bool) ($data['is_active'] ?? false);
        $data['is_default'] = (bool) ($data['is_default'] ?? false);

        // 上传 QR
        if ($request->hasFile('duitnow_qr')) {
            $data['duitnow_qr_path'] = $request->file('duitnow_qr')
                ->store('duitnow_qr', 'public');
        }

        // 如果设为 default，把其他 default 清掉
        if ($data['is_default']) {
            PaymentMethod::where('is_default', true)->update(['is_default' => false]);
        }

        PaymentMethod::create($data);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Payment method created.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.form', compact('paymentMethod'));
    }

    /** Update existing payment method */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'code'                => 'required|string|max:100|unique:payment_methods,code,' . $paymentMethod->id,
            'short_description'  => 'nullable|string|max:255',
            'is_active'           => 'nullable|boolean',
            'is_default'          => 'nullable|boolean',
            'bank_name'           => 'nullable|string|max:255',
            'bank_account_name'   => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'instructions'        => 'nullable|string',
            'duitnow_qr'          => 'nullable|image|max:4096',
            'remove_qr'           => 'nullable|boolean',
        ]);

        $data['is_active']  = (bool) ($data['is_active'] ?? false);
        $data['is_default'] = (bool) ($data['is_default'] ?? false);

        // 删除旧 QR
        if (!empty($data['remove_qr']) && $paymentMethod->duitnow_qr_path) {
            Storage::disk('public')->delete($paymentMethod->duitnow_qr_path);
            $data['duitnow_qr_path'] = null;
        }

        // 新 QR 替换
        if ($request->hasFile('duitnow_qr')) {
            if ($paymentMethod->duitnow_qr_path) {
                Storage::disk('public')->delete($paymentMethod->duitnow_qr_path);
            }

            $data['duitnow_qr_path'] = $request->file('duitnow_qr')
                ->store('duitnow_qr', 'public');
        }

        // 处理 default
        if ($data['is_default']) {
            PaymentMethod::where('is_default', true)
                ->where('id', '<>', $paymentMethod->id)
                ->update(['is_default' => false]);
        }

        $paymentMethod->update($data);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated.');
    }

    /** Delete */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->duitnow_qr_path) {
            Storage::disk('public')->delete($paymentMethod->duitnow_qr_path);
        }

        $paymentMethod->delete();

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted.');
    }
}
