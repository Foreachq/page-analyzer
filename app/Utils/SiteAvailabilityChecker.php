<?php

namespace App\Utils;

use App\Models\Url;
use App\Models\UrlCheck;
use Exception;
use Illuminate\Support\Facades\Http;

class SiteAvailabilityChecker
{
    public static function check(Url $url): ?UrlCheck
    {
        try {
            $response = Http::get($url->getName());
        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return null;
        }

        $check = new UrlCheck($url->getId());
        $check->setStatusCode($response->status());

        return $check;
    }
}
