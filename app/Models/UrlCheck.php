<?php

namespace App\Models;

use Carbon\Carbon;

class UrlCheck
{
    private int $id;
    private int $urlId;
    private ?int $statusCode;
    private ?string $h1;
    private ?string $title;
    private ?string $description;
    private Carbon $createdAt;

    public function __construct(int $urlId)
    {
        $this->urlId = $urlId;
        $this->createdAt = Carbon::now();
        $this->statusCode = null;
        $this->h1 = '';
        $this->title = '';
        $this->description = '';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUrlId(): int
    {
        return $this->urlId;
    }

    /**
     * @param int $urlId
     */
    public function setUrlId(int $urlId): void
    {
        $this->urlId = $urlId;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param int|null $statusCode
     */
    public function setStatusCode(?int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return string|null
     */
    public function getH1(): ?string
    {
        return $this->h1;
    }

    /**
     * @param string|null $h1
     */
    public function setH1(?string $h1): void
    {
        $this->h1 = $h1;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $createdAt
     */
    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
