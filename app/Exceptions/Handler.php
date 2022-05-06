<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        //
    ];


    public function register(): void
    {
        $this->reportable(function (PageNotFoundException $e) {
            return abort(404);
        });

        $this->renderable(function (InvalidUrlException $e, Request $request) {
            flash('Не удалось найти страницу по указанному URL.')->error();

            return redirect(redirect()->getUrlGenerator()->previous());
        });
    }
}
