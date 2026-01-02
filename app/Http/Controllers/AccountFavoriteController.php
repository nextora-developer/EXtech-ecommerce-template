<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class AccountFavoriteController extends Controller
{
    // 列表：用户中心 /account/favorites
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with('product')
            ->latest()
            ->paginate(8);

        return view('account.favorites.index', compact('favorites'));
    }

    // 加入收藏（或忽略重复）
    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        $user->favorites()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        // return back()->with('status', 'favorite-added');
        return back()->with('success', 'Added to wistlist');
    }

    // 移除收藏
    public function destroy(Request $request, Product $product)
    {
        $request->user()
            ->favorites()
            ->where('product_id', $product->id)
            ->delete();

        // return back()->with('status', 'favorite-removed');
        return back()->with('success', 'Remove from wistlist');
    }
}
