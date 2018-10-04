<?php

namespace App\Http\Controllers\Api\V1;

use App\ShortUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function referers($id)
    {
        $shortUrl = ShortUrl::where('id', $id)->auth()->firstOrFail();
        return $shortUrl
            ->referers()
            ->whereNotNull('referer')
            ->selectRaw('referer, count(*) as summ')
            ->groupBy('referer')
            ->orderByRaw('summ DESC')
            ->get();

    }

    public function report($id, $group)
    {
        $formatArr = [
            'days' => 'Y-m-d',
            'hours' => 'Y-m-d H',
            'min' => 'Y-m-d H:i',
        ];

        $shortUrl = ShortUrl::where('id', $id)->auth()->firstOrFail();
        $referers = $shortUrl
            ->referers()
            ->selectRaw('created_at')
            ->get();

        $groupArr = [];

        foreach ($referers as $item) {
            $format = Carbon::parse($item->created_at)->format($formatArr[$group]);
            $groupArr[$format] = $groupArr[$format] ?? 0;
            $groupArr[$format]++;
        }

        return $groupArr;
    }
}
