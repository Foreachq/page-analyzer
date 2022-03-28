<?php

namespace App\Http\Controllers;

use App\Models\UrlCheck;
use App\Repositories\UrlCheckRepository;
use App\Repositories\UrlRepository;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function check()
    {
        $urlId = Route::current()->parameter('id');

        $urlRepo = new UrlRepository();
        if ($urlRepo->findById($urlId) === null) {
            return abort(404);
        }

        $checkRepo = new UrlCheckRepository();

        $check = new UrlCheck($urlId);
        flash('Страница успешно проверена')->info();

        $checkRepo->save($check);

        return redirect(route('urls.index', $urlId));
    }
}
