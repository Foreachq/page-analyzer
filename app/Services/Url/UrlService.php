<?php

namespace App\Services\Url;

use App\Exceptions\PageNotFoundException;
use App\Exceptions\UrlAlreadyExistsException;
use App\Exceptions\UrlNotFoundException;
use App\Models\Url;
use Database\Repositories\UrlRepository;
use JetBrains\PhpStorm\ArrayShape;

class UrlService
{
    private const RESULTS_PER_PAGE = 10;

    public function __construct(
        protected UrlFormatter $urlFormatter,
        protected UrlRepository $urlRepository,
    ) {
    }

    /**
     * @throws UrlAlreadyExistsException
     */
    public function submitUrl(string $url): void
    {
        $queryResult = $this->urlRepository->findByName($url);
        if ($queryResult !== null) {
            throw new UrlAlreadyExistsException();
        }

        $url = new Url($url);
        $this->urlRepository->save($url);
    }

    /**
     * @throws PageNotFoundException
     */
    #[ArrayShape(
        [
            'urlsInfo' => "array",
            'urlsFrom' => "float|int",
            'urlsTo' => "float|int|void",
            'urlsCount' => "int",
            'currentPage' => "int",
            'pagesCount' => "float"
        ])]
    public function getUrlsPage(int $page): array
    {
        $urlsCount = $this->urlRepository->getCount();
        $pagesCount = ceil($urlsCount / self::RESULTS_PER_PAGE);

        if ($page <= 0 || $pagesCount < $page) {
            throw new PageNotFoundException();
        }

        $startUrl = ($page - 1) * self::RESULTS_PER_PAGE;
        $urls = $this->urlRepository->findLastUrlsChecks($startUrl, self::RESULTS_PER_PAGE);
        $endUrl = $startUrl + count($urls);

        return [
            'urlsInfo' => $urls,
            'urlsFrom' => $startUrl + 1,
            'urlsTo' => $endUrl,
            'urlsCount' => $this->urlRepository->getCount(),
            'currentPage' => $page,
            'pagesCount' => $pagesCount
        ];
    }

    public function getUrlByName(string $urlName): Url
    {
        return $this->urlRepository->findByName($urlName);
    }

    /**
     * @throws UrlNotFoundException
     */
    #[ArrayShape(['url' => "\App\Models\Url", 'checks' => "\App\Models\UrlCheck"])]
    public function getAllUrlChecks($urlId): array
    {
        $info = $this->urlRepository->findAllUrlChecks($urlId);

        if ($info === null) {
            throw new UrlNotFoundException();
        }

        return $info;
    }
}
