<?php

namespace Database\Repositories;

use App\Models\Url;
use App\Models\UrlCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlCheckRepository
{
    public function findAll(): array
    {
        $checks = DB::table('url_checks')->get();

        return $checks->map(function ($check) {
            return $this->stdClassToCheck($check);
        })->filter()->toArray();
    }

    public function findByUrlId(int $id): array
    {
        $checks = DB::table('url_checks')
            ->where('url_id', $id)
            ->get();

        return $checks->map(function ($check) {
            return $this->stdClassToCheck($check);
        })->filter()->toArray();
    }

    public function findById(int $id): ?UrlCheck
    {
        $url = DB::table('url_checks')
            ->where('id', $id)
            ->get()
            ->first();

        return $url === null ? null : $this->stdClassToCheck($url);
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

    public function removeByUrl(Url $url): void
    {
        DB::table('url_checks')
            ->where('id', $url->getId())
            ->delete();
    }

    public function update(UrlCheck $oldCheck, UrlCheck $newCheck): void
    {
        DB::table('url_checks')
            ->where('id', $oldCheck->getId())
            ->update([
                'url_id' => $newCheck->getUrlId(),
                'status_code' => $newCheck->getStatusCode(),
                'h1' => $newCheck->getH1(),
                'title' => $newCheck->getTitle(),
                'description' => $newCheck->getDescription(),
                'created_at' => $newCheck->getCreatedAt(),
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
