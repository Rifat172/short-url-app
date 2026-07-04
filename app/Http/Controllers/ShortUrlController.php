<?php

namespace App\Http\Controllers;

use App\Models\LinkClick;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url',
        ]);

        do {
            $token = Str::random(8);
        } while (ShortUrl::where('token', $token)->exists());

        $shortUrl = ShortUrl::create([
            'token' => $token,
            'long_url' => $request->long_url,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard');
    }
    public function destroy(Request $request, $id)
    {
        $link = ShortUrl::where('user_id', auth()->id())
            ->findOrFail($id);

        $link->delete();

        return back()->with('message', 'Ссылка удалена');
    }
    public function redirect($token)
    {
        $shortUrl = ShortUrl::where('token', $token)->firstOrFail();
        $shortUrl->increment('hits');

        LinkClick::create([
            'short_url_id' => $shortUrl->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'clicked_at' => now(),
        ]);

        return redirect($shortUrl->long_url);
    }
}
