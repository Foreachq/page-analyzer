<?php

namespace App\Http\Controllers;

use App\Models\UrlCheck;
use App\Repositories\UrlCheckRepository;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function check()
    {
        $urlId = Route::current()->parameter('id');
        $checkRepo = new UrlCheckRepository();

        $check = new UrlCheck($urlId);
        flash('Страница успешно проверена')->info();

        $checkRepo->save($check);

        return redirect(route('urls.index', $urlId));
    }
}
