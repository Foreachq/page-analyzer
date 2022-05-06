<?php

namespace App\Http\Controllers;

use App\Exceptions\PageNotFoundException;
use App\Exceptions\UrlNotFoundException;
use App\Http\Requests\UrlRequest;
use App\Services\Url\UrlFormatter;
use App\Services\Url\UrlService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function __construct(
        protected UrlFormatter $urlFormatter,
        protected UrlService $urlService
    ) {
    }

    public function add(UrlRequest $request): RedirectResponse
    {
        $rawUrl = $request->input('url')['name'];
        $urlName = $this->urlFormatter->normalizeUrl($rawUrl);

        $isAdded = $this->urlService->addUrl($urlName);

        if (!$isAdded) {
            flash('Страница уже существует')->info();
            $existingUrl = $this->urlService->getUrlByName($urlName);

            return redirect()->route('urls.index', $existingUrl->getId());
        }

        flash('Страница успешно добавлена')->info();
        $createdUrl = $this->urlService->getUrlByName($urlName);

        return redirect()->route('urls.index', $createdUrl->getId());
    }

    /**
     * @throws PageNotFoundException
     */
    public function index(Request $request): View
    {
        $page = $request->input('page', '1');

        if (!ctype_digit($page)) {
            return abort(404);
        }

        $pageInfo = $this->urlService->getUrlsPage($page);

        return view('urls', $pageInfo);
    }

    /**
     * @throws UrlNotFoundException
     */
    public function show(int $id): View
    {
        $urlInfo = $this->urlService->getAllUrlChecks($id);

        $params = [
            'url' => $urlInfo['url'],
            'checks' => $urlInfo['checks']
        ];

        return view('url', $params);
    }
}
