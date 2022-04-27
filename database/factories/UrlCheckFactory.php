<?php

namespace Database\Factories;

use App\Models\UrlCheck;
use Carbon\Carbon;

class UrlCheckFactory
{
    public function make(
        int $urlId,
        ?int $statusCode,
        ?string $h1,
        ?string $title,
        ?string $description,
        Carbon $createdAt = null,
        int $id = null
    ): UrlCheck {
        $check = new UrlCheck($urlId);

        if ($id !== null) {
            $check->setId($id);
        }

        if ($createdAt !== null) {
            $check->setCreatedAt($createdAt);
        }

        return $check->setDescription($description)
            ->setH1($h1)
            ->setStatusCode($statusCode)
            ->setTitle($title);
    }
}
