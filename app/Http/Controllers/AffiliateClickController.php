<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AffiliateClickController extends Controller
{
    public function go(Request $request, Product $product)
    {
        $product->affiliateClicks()->create([
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'referrer' => (string) $request->headers->get('referer'),
        ]);

        $product->increment('clicks');

        return redirect()->away($product->affiliate_url);
    }
}
