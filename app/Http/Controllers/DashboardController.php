<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\LinkClick;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = ShortUrl::where('user_id', $user->id)
            ->withCount('clicks')
            ->latest()
            ->paginate(15);

        $totalClicks = LinkClick::whereHas('shortUrl', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        return Inertia::render('Dashboard', [
            'links' => $links,
            'totalClicks' => $totalClicks,
        ]);
    }

    public function linkStats(Request $request, ShortUrl $shortUrl)
    {
        if ($shortUrl->user_id !== $request->user()->id) {
            abort(403);
        }

        $clicks = $shortUrl->clicks()
            ->select('id', 'ip_address', 'clicked_at', 'user_agent')
            ->latest('clicked_at')
            ->paginate(20);

        return Inertia::render('Dashboard/LinkStats', [
            'shortUrl' => $shortUrl,
            'clicks' => $clicks,
        ]);
    }
}
