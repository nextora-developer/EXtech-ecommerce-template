<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        
        $user = auth()->user();

        // 真实统计
        $stats = [
            'orders'    => $user->orders()->count() ?? 0,
            'favorites' => $user->favorites()->count() ?? 0,
            'addresses' => $user->addresses()->count() ?? 0,
        ];

        $latestOrders = $user->orders()
            ->latest()
            ->take(3)
            ->get();

        return view('account.index', compact('user', 'stats', 'latestOrders'));
    }

}
