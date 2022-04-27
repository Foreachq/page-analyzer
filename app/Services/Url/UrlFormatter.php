<?php

namespace App\Services\Url;

class UrlFormatter
{
    public function normalizeUrl(string $url): string
    {
        $urlArr = parse_url(strtolower($url));

        return sprintf('%s://%s', $urlArr['scheme'], $urlArr['host']);
    }
}
