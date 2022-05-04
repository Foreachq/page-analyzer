<?php

namespace App\Services\Parsers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class HtmlParser
{
    public function getBodySEOParams(string $body): array
    {
        $data = [];

        $data['h1'] ??= '';
        $data['title'] ??= '';
        $data['description'] ??= '';

        if (!strlen(trim($body))) {
            return $data;
        }

        $document = new Document($body);

        try {
            $data['h1'] = optional($document->first('h1'))->text();
        } catch (InvalidSelectorException) {
        }

        try {
            $data['title'] = optional($document->first('title'))->text();
        } catch (InvalidSelectorException) {
        }

        try {
            $data['description'] = optional($document->first('meta[name="description"]'))->attr('content');
        } catch (InvalidSelectorException) {
        }

        return $data;
    }
}
