<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        do{
            $token = Str::random(8);
        } while (ShortUrl::where('token', $token)->exists());

        $shortUrl = ShortUrl::create([
            'token' => $token,
            'long_url' => $request->url,
        ]);

        return response()->json([
            'short_url' => url('/r/'.$shortUrl->token),
            'token' => $shortUrl->token,
        ]);
    }

    public function redirect($token)
    {
        $shortUrl = ShortUrl::where('token', $token)->firstOrFail();
        $shortUrl->increment('hits');
        return redirect($shortUrl->long_url);
    }
}
