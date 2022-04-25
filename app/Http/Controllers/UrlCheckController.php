<?php

namespace App\Http\Controllers;

use App\Repositories\UrlCheckRepository;
use App\Repositories\UrlRepository;
use App\Services\SiteSEOChecker;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    protected UrlRepository $urlRepository;
    protected UrlCheckRepository $urlCheckRepository;

    public function __construct(UrlRepository $urlRepository, UrlCheckRepository $urlCheckRepository)
    {
        $this->urlRepository = $urlRepository;
        $this->urlCheckRepository = $urlCheckRepository;
    }

    public function check()
    {
        $urlId = optional(Route::current())->parameter('id');
        if ($urlId === null) {
            return abort(404);
        }

        $url = $this->urlRepository->findById($urlId);
        if ($url === null) {
            return abort(404);
        }

        $check = SiteSEOChecker::check($url);
        if ($check === null) {
            return redirect(route('urls.index', $urlId));
        }

        $this->urlCheckRepository->save($check);
        flash('Страница успешно проверена')->info();

        return redirect(route('urls.index', $urlId));
    }
}
