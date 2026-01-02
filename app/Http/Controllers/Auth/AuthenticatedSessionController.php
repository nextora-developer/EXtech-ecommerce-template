<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Cart;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // ① 登录前，先抓当前 session 的 guest cart
        $guestCart = Cart::where('session_id', $request->session()->getId())
            ->with('items')
            ->first();

        // ② 正常登录流程
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user(); // 已登录用户对象

        // ③ 如果有 guest cart，就把它合并 / 认领给这个用户
        if ($guestCart && $user) {

            // 查一下这个用户原本有没有自己的 cart
            $userCart = Cart::where('user_id', $user->id)
                ->with('items')
                ->first();

            if ($userCart) {
                // ✅ 情况 A：用户原本就有 cart → 把 guest 的东西合并进去

                foreach ($guestCart->items as $guestItem) {
                    $existing = $userCart->items()
                        ->where('product_id', $guestItem->product_id)
                        ->where('product_variant_id', $guestItem->product_variant_id)
                        ->first();

                    if ($existing) {
                        // 同一个 product + variant → 数量相加
                        $existing->qty += $guestItem->qty;
                        $existing->save();
                    } else {
                        // 否则直接搬过去
                        $userCart->items()->create([
                            'product_id'         => $guestItem->product_id,
                            'product_variant_id' => $guestItem->product_variant_id,
                            'qty'                => $guestItem->qty,
                            'unit_price'         => $guestItem->unit_price,
                            'variant_label'      => $guestItem->variant_label,
                        ]);
                    }
                }

                // 清掉 guest cart
                $guestCart->items()->delete();
                $guestCart->delete();
            } else {
                // ✅ 情况 B：用户之前没有 cart → 直接把 guest cart 认领过来

                $guestCart->user_id    = $user->id;
                $guestCart->session_id = null; // 可以清掉 session_id，之后都用 user_id 找
                $guestCart->save();
            }
        }

        // 登录后要跳去哪里，你自己决定：
        // 1) 回原本 intended 页面：
        // return redirect()->intended(RouteServiceProvider::HOME);

        // 2) 或者你想登录完直接去购物车：
        return redirect()->intended();
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
