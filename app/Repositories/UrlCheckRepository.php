<?php

namespace App\Repositories;

use App\Models\UrlCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlCheckRepository
{
    public function findByUrlId(int $id): array
    {
        $checks = DB::table('url_checks')
            ->where('url_id', $id)
            ->get();

        return $checks->map(function ($check) {
            return $this->stdClassToCheck($check);
        })->filter()->toArray();
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
        if (
            !isset($stdCheck->url_id)
            || !isset($stdCheck->id)
            || !isset($stdCheck->created_at)
            || !isset($stdCheck->description)
            || !isset($stdCheck->h1)
            || !isset($stdCheck->status_code)
            || !isset($stdCheck->title)
        ) {
            return null;
        }
        $check = new UrlCheck($stdCheck->url_id);

        $check->setId($stdCheck->id);
        $carbonTime = Carbon::parse($stdCheck->created_at);

        $check->setCreatedAt($carbonTime);
        $check->setDescription($stdCheck->description);
        $check->setH1($stdCheck->h1);
        $check->setStatusCode($stdCheck->status_code);
        $check->setTitle($stdCheck->title);

        return $check;
    }
}
