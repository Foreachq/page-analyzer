<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUrlException;
use App\Exceptions\UrlNotFoundException;
use App\Services\Checkers\SiteChecker;
use Illuminate\Http\RedirectResponse;

class UrlCheckController extends Controller
{
    public function __construct(protected SiteChecker $checker)
    {
    }

    /**
     * @throws UrlNotFoundException
     * @throws InvalidUrlException
     */
    public function check(int $urlId): RedirectResponse
    {
        $this->checker->check($urlId);
        flash('Страница успешно проверена')->info();

        return redirect(route('urls.index', $urlId));
    }
}
