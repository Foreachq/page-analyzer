<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Illuminate\Support\Facades\Route;

class UrlController extends Controller
{
    public function submit(UrlRequest $request)
    {
        $urlRepo = new UrlRepository();
        $urlName = $request->input('url')['name'];

        $urlArr = parse_url(strtolower($urlName));
        $normalizedUrl = sprintf('%s://%s', $urlArr['scheme'], $urlArr['host']);

        if ($urlRepo->findByName($normalizedUrl) !== null) {
            return redirect()->route('home');
        }

        $url = new Url($normalizedUrl);
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

    public function showUrl()
    {
        $urlRepo = new UrlRepository();
        $id = Route::current()->parameter('id');

        $url = $urlRepo->findById($id);
        if ($url === null) {
            return abort(404);
        }

        $params = [
          'url' => $url
        ];

        return view('url', $params);
    }
}
