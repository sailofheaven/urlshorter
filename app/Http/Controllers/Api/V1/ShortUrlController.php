<?php

namespace App\Http\Controllers\Api\V1;

use App\ShortUrl;
use App\UrlReferer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ShortUrl::auth()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'url' => 'required|unique:short_urls,full_url|url|max:255',
        ]);

        $shortUrl = new ShortUrl();

        $shortUrl->user_id = Auth::user()->id;
        $shortUrl->full_url = $data['url'];
        $shortUrl->hash = base64_encode($data['url']);


        $shortUrl->save();

        return response()->json($shortUrl, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return ShortUrl::where('id', $id)
                ->auth()
                ->withCount('referers')
                ->first() ?? abort(404);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ShortUrl::where('id', $id)->auth()->delete();
    }

    public function redirect($hash, Request $request)
    {
        $shortUrl = ShortUrl::where('hash', $hash)->first() ?? abort(404);

        $urlReferer = new UrlReferer();
        $urlReferer->short_url_id = $shortUrl->id;
        $urlReferer->referer = $request->headers->get('referer', null);
        $urlReferer->save();

        return redirect($shortUrl->full_url);
    }
}
