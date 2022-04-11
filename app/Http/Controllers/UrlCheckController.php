<?php

namespace App\Http\Controllers;

use App\Utils\SiteSEOChecker;
use Database\Repositories\UrlCheckRepository;
use Database\Repositories\UrlRepository;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function check()
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
