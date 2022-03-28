<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlCheckRepository;
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

        $result = $urlRepo->findByName($normalizedUrl);
        if ($result !== null) {
            flash('Страница уже существует')->info();

            return redirect()->route('urls.index', $result->getId());
        }

        $url = new Url($normalizedUrl);
        $urlRepo->save($url);
        flash('Страница успешно добавлена')->info();

        $urlId = $urlRepo
            ->findByName($url->getName())
            ->getId();

        return redirect()->route('urls.index', $urlId);
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
                $lastChecks[$url->getId()] = '';
                continue;
            }

            $lastCheck = collect($urlChecks)->last();
            $lastChecks[$url->getId()] = $lastCheck->getCreatedAt();
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
        $id = Route::current()->parameter('id');

        $url = $urlRepo->findById($id);
        if ($url === null) {
            return abort(404);
        }

        $checksRepo = new UrlCheckRepository();
        $checks = array_reverse($checksRepo->findByUrlId($id));

        $params = [
            'url' => $url,
            'checks' => $checks
        ];

        return view('url', $params);
    }
}
