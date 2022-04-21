<?php

namespace App\Repositories;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlRepository
{
    // TODO: STDClass to associative array

    public function findAllUrlInfo(): array
    {
        $urls = DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select('urls.id', 'urls.name', 'url_checks.created_at', 'url_checks.status_code')
            ->get();

        return $urls->toArray();
    }

    public function findByName(string $name): ?Url
    {
        $url = DB::table('urls')
            ->where('name', $name)
            ->get()
            ->first();

        return $url === null ? null : $this->stdClassToUrl($url);
    }

    public function findById(int $id): ?Url
    {
        $url = DB::table('urls')
            ->where('id', $id)
            ->get()
            ->first();

        return $url === null ? null : $this->stdClassToUrl($url);
    }

    public function save(Url $url): void
    {
        DB::table('urls')->insert([
            'name' => $url->getName(),
            'created_at' => $url->getCreatedAt()
        ]);
    }

    private function stdClassToUrl(object $stdUrl): Url|null
    {
        if (
            !isset($stdUrl->name)
            || !isset($stdUrl->created_at)
            || !isset($stdUrl->id)
        ) {
            return null;
        }

        $url = new Url($stdUrl->name);

        $carbonCreatedAt = Carbon::parse($stdUrl->created_at);
        $url->setCreatedAt($carbonCreatedAt);

        $url->setId($stdUrl->id);

        return $url;
    }
}
