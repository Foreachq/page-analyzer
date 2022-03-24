<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;

class UrlController extends Controller
{
    public function submit(UrlRequest $request)
    {
        $urlRepo = new UrlRepository();
        $urlName = $request->input('url')['name'];

        if ($urlRepo->findByName($urlName) !== null) {
            return redirect()->route('home');
        }

        $url = new Url($urlName);
        $urlRepo->save($url);

        return redirect()->route('home');
    }

    public function showAllUrls()
    {
        $urlRepo = new UrlRepository();
        $urls = $urlRepo->findAll();

        $params = [
            'urls' => $urls
        ];

        return view('urls', $params);
    }
}
