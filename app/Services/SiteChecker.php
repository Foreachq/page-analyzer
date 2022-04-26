<?php

namespace App\Services;

use App\Exceptions\UrlNotExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Models\UrlCheck;
use Database\Repositories\UrlCheckRepository;
use Database\Repositories\UrlRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class SiteChecker
{
    public function __construct(
        protected UrlRepository $urlRepository,
        protected UrlCheckRepository $urlCheckRepository
    ) {
    }

    /**
     * @throws UrlNotExistsException
     * @throws UrlNotFoundException
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
            throw new UrlNotExistsException($e->getMessage());
        }

        $parser = new HtmlParser();
        $params = $parser->getBodySEOParams($response->body());

        // TODO: UrlChecks factory
        $check = new UrlCheck($url->getId());

        $check->setStatusCode($response->status())
            ->setH1($params['h1'])
            ->setTitle($params['title'])
            ->setDescription($params['description']);

        $this->urlCheckRepository->save($check);
    }
}
