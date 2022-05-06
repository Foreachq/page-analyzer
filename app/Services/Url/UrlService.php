<?php

namespace App\Services\Url;

use App\Exceptions\PageNotFoundException;
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

    public function addUrl(string $url): bool
    {
        $queryResult = $this->urlRepository->findByName($url);
        if ($queryResult !== null) {
            return false;
        }

        $url = new Url($url);
        $this->urlRepository->save($url);

        return true;
    }

    /**
     * @throws PageNotFoundException
     */
    #[ArrayShape([
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
        $urls = $this->urlRepository->findAllLastUrlsChecks($startUrl, self::RESULTS_PER_PAGE);
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

    #[ArrayShape(['url' => "\App\Models\Url", 'checks' => "\App\Models\UrlCheck"])]
    public function getAllUrlChecks($urlId): array
    {
        $urlInfo = $this->urlRepository->findAllUrlChecks($urlId);

        if ($urlInfo === null) {
            throw new UrlNotFoundException();
        }

        return $urlInfo;
    }
}
