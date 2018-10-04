<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShortUrl extends Model
{
    public function scopeAuth($query)
    {
        return $query->where('user_id', Auth::id());
    }

    public function referers()
    {
        return $this->hasMany('App\UrlReferer');
    }
}
