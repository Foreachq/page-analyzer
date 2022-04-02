<?php

namespace App\Http\Controllers;

use App\Repositories\UrlCheckRepository;
use App\Repositories\UrlRepository;
use App\Utils\SiteSEOChecker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function check(): RedirectResponse|Application|Redirector
    {
        $urlId = optional(Route::current())->parameter('id');
        if ($urlId === null) {
            return abort(404);
        }

        $urlRepo = new UrlRepository();

        $url = $urlRepo->findById($urlId);
        if ($url === null) {
            return abort(404);
        }

        $checkRepo = new UrlCheckRepository();

        $check = SiteSEOChecker::check($url);
        if ($check === null) {
            return redirect(route('urls.index', $urlId));
        }

        $checkRepo->save($check);
        flash('Страница успешно проверена')->info();

        return redirect(route('urls.index', $urlId));
    }
}
