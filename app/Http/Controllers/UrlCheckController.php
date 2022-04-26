<?php

namespace App\Http\Controllers;

use App\Exceptions\UrlNotExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Services\SiteChecker;
use Illuminate\Support\Facades\Route;

class UrlCheckController extends Controller
{
    public function __construct(protected SiteChecker $checker)
    {
    }

    public function check()
    {
        $urlId = optional(Route::current())->parameter('id');
        if ($urlId === null) {
            return abort(404);
        }

        try {
            $this->checker->check($urlId);

            flash('Страница успешно проверена')->info();
        } catch (UrlNotFoundException) {
            return abort(404);
        } catch (UrlNotExistsException $e) {
            flash($e->getMessage())->error();
        }

        return redirect(route('urls.index', $urlId));
    }
}
