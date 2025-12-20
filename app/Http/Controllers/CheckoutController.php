<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
     public function index()
    {
        // 先随便回一个 view，之后再慢慢做真正的 checkout
        return view('checkout.index');
    }
}
