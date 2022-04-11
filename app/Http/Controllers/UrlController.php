<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use Database\Repositories\UrlCheckRepository;
use Database\Repositories\UrlRepository;
use Illuminate\Support\Facades\Route;

class UrlController extends Controller
{
    public function submit(UrlRequest $request)
    {
        $urlRepo = new UrlRepository();
        $urlName = $request->input('url')['name'];

        $urlArr = parse_url(strtolower($urlName));
        $normalizedUrl = sprintf('%s://%s', $urlArr['scheme'], $urlArr['host']);

        $result = $urlRepo->findByName($normalizedUrl);
        if ($result !== null) {
            flash('Страница уже существует')->info();

            return redirect()->route('urls.index', $result->getId());
        }

        $url = new Url($normalizedUrl);
        $urlRepo->save($url);

        $createdUrl = $urlRepo
            ->findByName($url->getName());

        if ($createdUrl !== null) {
            flash('Страница успешно добавлена')->info();
            return redirect()->route('urls.index', $createdUrl->getId());
        }

        return abort(500, "Couldn't add url.");
    }

    public function showAllUrls()
    {
        $urlRepo = new UrlRepository();
        $urls = $urlRepo->findAll();

        $checksRepo = new UrlCheckRepository();

        $lastChecks = [];
        foreach ($urls as $url) {
            $urlChecks = $checksRepo->findByUrlId($url->getId());

            if (count($urlChecks) === 0) {
                $lastChecks[$url->getId()] = new class {
                    public function getCreatedAt(): string
                    {
                        return '';
                    }

                    public function getStatusCode(): string
                    {
                        return '';
                    }
                };

                continue;
            }

            $lastCheck = collect($urlChecks)->last();
            $lastChecks[$url->getId()] = $lastCheck;
        }

        $params = [
            'urls' => $urls,
            'lastChecks' => $lastChecks
        ];

        return view('urls', $params);
    }

    public function showUrl()
    {
        $urlRepo = new UrlRepository();

        $route = Route::current();
        if ($route === null || $route->parameter('id') === null) {
            return abort(404);
        }

        $id = intval($route->parameter('id'));

        $url = $urlRepo->findById($id);
        if ($url === null) {
            return abort(404);
        }

        $checksRepo = new UrlCheckRepository();

        $checks = array_reverse($checksRepo->findByUrlId($id));
        $normalizedChecks = $this->normalizeUrlChecks($checks);

        $params = [
            'url' => $url,
            'checks' => $normalizedChecks
        ];

        return view('url', $params);
    }

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
