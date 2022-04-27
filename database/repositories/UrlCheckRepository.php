<?php

namespace Database\Repositories;

use App\Models\UrlCheck;
use Carbon\Carbon;
use Database\Factories\UrlCheckFactory;
use Illuminate\Support\Facades\DB;

class UrlCheckRepository
{
    public function __construct(protected UrlCheckFactory $urlCheckFactory)
    {
    }

    public function findByUrlId(int $id): array
    {
        return DB::table('url_checks')
            ->where('url_id', $id)
            ->get()
            ->map(fn($entry) => $this->stdClassToCheck($entry))
            ->toArray();
    }

    public function save(UrlCheck $check): void
    {
        DB::table('url_checks')->insert([
            'url_id' => $check->getUrlId(),
            'status_code' => $check->getStatusCode(),
            'h1' => $check->getH1(),
            'title' => $check->getTitle(),
            'description' => $check->getDescription(),
            'created_at' => $check->getCreatedAt(),
        ]);
    }

    private function stdClassToCheck(object $stdCheck): ?UrlCheck
    {
        return $this->urlCheckFactory->make(
            $stdCheck->url_id,
            $stdCheck->status_code,
            $stdCheck->h1,
            $stdCheck->title,
            $stdCheck->description,
            Carbon::parse($stdCheck->created_at),
            $stdCheck->id
        );
    }
}
