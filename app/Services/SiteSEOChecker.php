<?php

namespace App\Services;

use App\Models\Url;
use App\Models\UrlCheck;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

// TODO: 'Unstatic'
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

        // TODO: UrlChecks factory
        $check = new UrlCheck($url->getId());

        $check->setStatusCode($response->status());
        $check->setH1($params['h1']);
        $check->setTitle($params['title']);
        $check->setDescription($params['description']);

        return $check;
    }

    // TODO: make another parser service
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
