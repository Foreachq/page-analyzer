<?php

namespace App\Http\Controllers;

use App\Exceptions\PageNotFoundException;
use App\Exceptions\UrlAlreadyExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Http\Requests\UrlRequest;
use App\Services\Url\UrlFormatter;
use App\Services\Url\UrlService;
use Illuminate\Http\Request;

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
        }

        flash('Страница успешно добавлена')->info();
        $createdUrl = $this->urlService->getUrlByName($urlName);

        return redirect()->route('urls.index', $createdUrl->getId());
    }

    public function index(Request $request)
    {
        $page = $request->input('page', '1');

        if (!ctype_digit($page)) {
            return abort(404);
        }

        try {
            $pageInfo = $this->urlService->getUrlsPage($page);
        } catch (PageNotFoundException) {
            return abort(404);
        }

        return view('urls', $pageInfo);
    }

    public function show(int $id)
    {
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
