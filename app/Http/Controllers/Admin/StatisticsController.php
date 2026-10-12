<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\Product;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalClicks = AffiliateClick::query()->count();
        $clicksToday = AffiliateClick::query()->whereDate('created_at', today())->count();
        $clicksThisMonth = AffiliateClick::query()->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topProducts = Product::query()
            ->with('primaryImage')
            ->orderByDesc('clicks')
            ->take(10)
            ->get();

        $daily = AffiliateClick::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $chart = collect(range(13, 0))->map(function ($daysAgo) use ($daily) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->translatedFormat('d M'),
                'value' => (int) ($daily[$date->toDateString()] ?? 0),
            ];
        });

        $maxChart = max(1, $chart->max('value'));

        return view('admin.statistics.index', compact(
            'totalClicks',
            'clicksToday',
            'clicksThisMonth',
            'topProducts',
            'chart',
            'maxChart',
        ));
    }
}
