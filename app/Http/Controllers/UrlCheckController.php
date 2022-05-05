<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUrlException;
use App\Exceptions\UrlNotFoundException;
use App\Services\Checkers\SiteChecker;

class UrlCheckController extends Controller
{
    public function __construct(protected SiteChecker $checker)
    {
    }

    public function check(int $urlId)
    {
        try {
            $this->checker->check($urlId);

            flash('Страница успешно проверена')->info();
        } catch (UrlNotFoundException) {
            return abort(404);
        } catch (InvalidUrlException) {
            flash('Не удалось найти страницу по указанному URL.')->error();
        }

        return redirect(route('urls.index', $urlId));
    }
}
