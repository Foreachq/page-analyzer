<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;

class UrlController extends Controller
{
    public function submit(UrlRequest $request)
    {
        $url = $request->input('url')['name'] ?? null;


        return $url;
    }
}
