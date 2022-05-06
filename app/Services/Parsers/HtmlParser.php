<?php

namespace App\Services\Parsers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class HtmlParser
{
    public function parseSEO(string $body): array
    {
        $params = [];
        $params['h1'] = null;
        $params['title'] = null;
        $params['description'] = null;

        if (!strlen(trim($body))) {
            return $params;
        }

        try {
            $params = $this->getSEOParams($body);
        } catch (InvalidSelectorException) {
        }

        return $params;
    }

    /**
     * @throws InvalidSelectorException
     */
    public function getSEOParams(string $body): array
    {
        $document = new Document($body);
        $params = [];

        $params['h1'] = $document->has('h1')
            ? $document->first('h1')->text()
            : null;

        $params['title'] = $document->has('title')
            ? $document->first('title')->text()
            : null;

        $params['description'] = $document->has('meta[name="description"]')
            ? $document->first('meta[name="description"]')->attr('content')
            : null;

        return $params;
    }
}
