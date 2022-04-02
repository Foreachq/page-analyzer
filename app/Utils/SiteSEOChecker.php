<?php

namespace App\Utils;

use App\Models\Url;
use App\Models\UrlCheck;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Exception;
use DiDom\Document;

class SiteSEOChecker
{
    public static function check(Url $url): ?UrlCheck
    {
        try {
            $response = Http::get($url->getName());
        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return null;
        }

        $params = self::getBodySEOParams($response);

        $check = new UrlCheck($url->getId());

        $check->setStatusCode($response->status());
        $check->setH1($params['h1']);
        $check->setTitle($params['title']);
        $check->setDescription($params['description']);

        return $check;
    }

    private static function getBodySEOParams(Response $response): array
    {
        $document = new Document($response->body());

        $data = [];

        try {
            $data['h1'] = optional($document->first('h1'))->text();
        } catch (InvalidSelectorException $e) {
        }

        try {
            $data['title'] = optional($document->first('title'))->text();
        } catch (InvalidSelectorException $e) {
        }

        try {
            $data['description'] = optional($document->first('meta[name="description"]'))->attr('content');
        } catch (InvalidSelectorException $e) {
        }

        $data['h1'] ??= '';
        $data['title'] ??= '';
        $data['description'] ??= '';

        return $data;
    }
}
