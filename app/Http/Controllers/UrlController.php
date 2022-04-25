<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Illuminate\Support\Facades\Route;

class UrlController extends Controller
{
    protected UrlRepository $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function submit(UrlRequest $request)
    {
        $urlName = $request->input('url')['name'];
        $urlArr = parse_url(strtolower($urlName));
        $normalizedUrl = sprintf('%s://%s', $urlArr['scheme'], $urlArr['host']);

        $result = $this->urlRepository->findByName($normalizedUrl);
        if ($result !== null) {
            flash('Страница уже существует')->info();

            return redirect()->route('urls.index', $result->getId());
        }

        $url = new Url($normalizedUrl);
        $this->urlRepository->save($url);

        $createdUrl = $this->urlRepository
            ->findByName($url->getName());

        if ($createdUrl !== null) {
            flash('Страница успешно добавлена')->info();

            return redirect()->route('urls.index', $createdUrl->getId());
        }

        return abort(500, "Couldn't add url.");
    }

    public function showAllUrls()
    {
        $urlsInfo = $this->urlRepository->findLastUrlsChecks();

        return view('urls', ['urlsInfo' => $urlsInfo]);
    }

    public function showUrl()
    {
        $route = Route::current();
        if ($route === null || $route->parameter('id') === null) {
            return abort(404);
        }

        $id = intval($route->parameter('id'));

        $urlInfo = $this->urlRepository->findAllUrlChecks($id);

        if ($urlInfo === null) {
            return abort(404);
        }

        $params = [
            'url' => $urlInfo['url'],
            'checks' => $urlInfo['checks']
        ];

        return view('url', $params);
    }
}
