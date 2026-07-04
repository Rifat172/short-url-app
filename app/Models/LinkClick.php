<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkClick extends Model
{
    protected $fillable = ['short_url_id', 'ip_address', 'user_agent'];

    public function shortUrl() : BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
