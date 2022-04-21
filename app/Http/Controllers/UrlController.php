<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlCheckRepository;
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
        $urls = $this->urlRepository->findAllUrlInfo();

        return view('urls', ['urls' => $urls]);
    }

    public function showUrl()
    {
        $route = Route::current();
        if ($route === null || $route->parameter('id') === null) {
            return abort(404);
        }

        $id = intval($route->parameter('id'));

        $url = $this->urlRepository->findById($id);
        if ($url === null) {
            return abort(404);
        }
        // TODO: Implement join in repo

        $checksRepo = new UrlCheckRepository();

        $checks = array_reverse($checksRepo->findByUrlId($id));
        $normalizedChecks = $this->normalizeUrlChecks($checks);

        $params = [
            'url' => $url,
            'checks' => $normalizedChecks
        ];

        return view('url', $params);
    }

    // TODO: Make one method for three fields

    private function normalizeUrlChecks(array $checks): array
    {
        $normalizedChecks = [];
        foreach ($checks as $check) {
            $h1 = $check->getH1();
            $title = $check->getTitle();
            $description = $check->getDescription();

            $check->setH1(
                mb_strlen($h1) > 10
                    ? mb_substr($h1, 0, 10) . '...'
                    : $h1
            );

            $check->setTitle(
                mb_strlen($title) > 30
                    ? mb_substr($title, 0, 30) . '...'
                    : $title
            );

            $check->setDescription(
                mb_strlen($description) > 30
                    ? mb_substr($description, 0, 30) . '...'
                    : $description
            );

            $normalizedChecks[] = $check;
        }

        return $normalizedChecks;
    }
}
