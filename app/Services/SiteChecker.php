<?php

namespace App\Services;

use App\Models\Url;
use App\Models\UrlCheck;
use Exception;
use Illuminate\Support\Facades\Http;

class SiteChecker
{
    public function check(Url $url): ?UrlCheck
    {
        try {
            $response = Http::get($url->getName());
        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return null;
        }

        $parser = new HtmlParser();
        $params = $parser->getBodySEOParams($response->body());

        // TODO: UrlChecks factory
        $check = new UrlCheck($url->getId());

        $check->setStatusCode($response->status())
            ->setH1($params['h1'])
            ->setTitle($params['title'])
            ->setDescription($params['description']);

        return $check;
    }
}
