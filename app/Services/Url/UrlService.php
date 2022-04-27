<?php

namespace App\Services\Url;

use App\Exceptions\CannotAddUrlException;
use App\Exceptions\UrlAlreadyExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Models\Url;
use Database\Repositories\UrlRepository;
use JetBrains\PhpStorm\ArrayShape;

class UrlService
{
    public function __construct(
        protected UrlFormatter $urlFormatter,
        protected UrlRepository $urlRepository,
    ) {
    }

    /**
     * @throws UrlAlreadyExistsException
     * @throws CannotAddUrlException
     */
    public function submitUrl(string $url): void
    {
        $queryResult = $this->urlRepository->findByName($url);
        if ($queryResult !== null) {
            throw new UrlAlreadyExistsException();
        }

        $url = new Url($url);
        $this->urlRepository->save($url);

        $createdUrl = $this->urlRepository->findByName($url->getName());

        if ($createdUrl === null) {
            throw new CannotAddUrlException();
        }
    }

    public function getAllLastUrlsChecks(): array
    {
        return $this->urlRepository->findLastUrlsChecks();
    }

    public function getUrlByName(string $urlName): Url
    {
        return $this->urlRepository->findByName($urlName);
    }

    /**
     * @throws UrlNotFoundException
     */
    #[ArrayShape(['url' => "\App\Models\Url", 'checks' => "\App\Models\UrlCheck"])]
    public function getAllUrlChecks($id): array
    {
        $info = $this->urlRepository->findAllUrlChecks($id);

        if ($info === null) {
            throw new UrlNotFoundException();
        }

        return $info;
    }
}
