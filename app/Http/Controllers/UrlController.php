<?php

namespace App\Http\Controllers;

use App\Exceptions\CannotAddUrlException;
use App\Exceptions\UrlAlreadyExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Http\Requests\UrlRequest;
use App\Services\Url\UrlFormatter;
use App\Services\Url\UrlService;
use Illuminate\Support\Facades\Route;

class UrlController extends Controller
{
    public function __construct(
        protected UrlFormatter $urlFormatter,
        protected UrlService $urlService
    ) {
    }

    public function submit(UrlRequest $request)
    {
        $rawUrl = $request->input('url')['name'];
        $urlName = $this->urlFormatter->normalizeUrl($rawUrl);

        try {
            $this->urlService->submitUrl($urlName);
        } catch (UrlAlreadyExistsException) {
            flash('Страница уже существует')->info();
            $existingUrl = $this->urlService->getUrlByName($urlName);

            return redirect()->route('urls.index', $existingUrl->getId());
        } catch (CannotAddUrlException) {
            return abort(500, "Couldn't add url.");
        }

        flash('Страница успешно добавлена')->info();
        $createdUrl = $this->urlService->getUrlByName($urlName);

        return redirect()->route('urls.index', $createdUrl->getId());
    }

    public function showAllUrls()
    {
        $urlsInfo = $this->urlService->getAllLastUrlsChecks();

        return view('urls', ['urlsInfo' => $urlsInfo]);
    }

    public function showUrl()
    {
        $route = Route::current();
        if ($route === null || $route->parameter('id') === null) {
            return abort(404);
        }

        $id = intval($route->parameter('id'));

        try {
            $urlInfo = $this->urlService->getAllUrlChecks($id);
        } catch (UrlNotFoundException) {
            return abort(404);
        }

        $params = [
            'url' => $urlInfo['url'],
            'checks' => $urlInfo['checks']
        ];

        return view('url', $params);
    }
}
