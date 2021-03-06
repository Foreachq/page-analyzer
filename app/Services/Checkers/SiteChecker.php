<?php

namespace App\Services\Checkers;

use App\Exceptions\InvalidUrlException;
use App\Exceptions\UrlNotFoundException;
use App\Services\Parsers\HtmlParser;
use Database\Factories\UrlCheckFactory;
use Database\Repositories\UrlCheckRepository;
use Database\Repositories\UrlRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class SiteChecker
{
    public function __construct(
        protected UrlRepository $urlRepository,
        protected UrlCheckRepository $urlCheckRepository,
        protected UrlCheckFactory $urlCheckFactory,
        protected HtmlParser $htmlParser
    ) {
    }

    /**
     * @throws InvalidUrlException
     */
    public function check(int $urlId): void
    {
        $url = $this->urlRepository->findById($urlId);
        if ($url === null) {
            throw new UrlNotFoundException();
        }

        try {
            $response = Http::get($url->getName());
        } catch (Exception $e) {
            throw new InvalidUrlException($e->getMessage());
        }

        $params = $this->htmlParser->parseSEO($response->body());

        $check = $this->urlCheckFactory->create(
            $urlId,
            $response->status(),
            $params['h1'],
            $params['title'],
            $params['description']
        );

        $this->urlCheckRepository->save($check);
    }
}
