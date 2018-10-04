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

    public function report($id, $group, Request $request)
    {
        $formatArr = [
            'days' => 'Y-m-d',
            'hours' => 'Y-m-d H',
            'min' => 'Y-m-d H:i',
        ];

        $fromDate = $request->get('from_date', '0000-00-00');
        $toDate = $request->get('toDate', '9999-00-00');

        $shortUrl = ShortUrl::where('id', $id)->auth()->firstOrFail();
        $referers = $shortUrl
            ->referers()
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->select('created_at')
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
