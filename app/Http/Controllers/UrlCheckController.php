<?php

namespace App\Http\Controllers;

use App\Repositories\UrlCheckRepository;
use App\Repositories\UrlRepository;
use App\Utils\SiteSEOChecker;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function check()
    {
        $urlId = Route::current()->parameter('id');
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
