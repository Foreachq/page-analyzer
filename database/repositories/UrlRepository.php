<?php

namespace Database\Repositories;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class UrlRepository
{
    public function getCount(): int
    {
        return DB::table('urls')->get()->count();
    }

    public function findAllLastUrlsChecks(int $from, int $count): array
    {
        return DB::table('urls')
            ->leftJoin('url_checks', 'urls.id', '=', 'url_checks.url_id')
            ->select(
                'urls.id as id',
                'urls.name as name',
                'url_checks.created_at as check_date',
                'url_checks.status_code as check_code'
            )
            ->distinct('urls.id')
            ->offset($from)
            ->limit($count)
            ->get()
            ->map(fn($entry) => $this->stdClassToArray($entry))
            ->toArray();
    }

    #[ArrayShape(['url' => "\App\Models\Url", 'checks' => "mixed"])]
    public function findAllUrlChecks(int $id): ?array
    {
        $urlChecksInfo = DB::table('urls')
            ->select(
                'urls.id as url_id', 'urls.name as url_name', 'urls.created_at as url_created_at',
                'url_checks.id as check_id', 'url_checks.status_code as check_status_code',
                'url_checks.title as check_title', 'url_checks.description as check_description',
                'url_checks.h1 as check_h1', 'url_checks.created_at as check_created_at',
            )
            ->leftJoin('url_checks', 'url_checks.url_id', '=', 'urls.id')
            ->where('urls.id', '=', $id)
            ->get();

        if (!count($urlChecksInfo)) {
            return null;
        }

        if (count($urlChecksInfo) === 1 && $urlChecksInfo->first()->check_id === null) {
            return ['url' => $urlChecksInfo->first(), 'checks' => []];
        }

        return ['url' => $urlChecksInfo->first(), 'checks' => $urlChecksInfo->toArray()];
    }

    public function findByName(string $name): ?Url
    {
        $url = DB::table('urls')
            ->where('name', '=', $name)
            ->get()
            ->first();

        return $url === null ? null : $this->stdClassToUrl($url);
    }

    public function findById(int $id): ?Url
    {
        $url = DB::table('urls')
            ->where('id', '=', $id)
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

    private function stdClassToUrl(object $stdUrl): Url
    {
        $url = new Url($stdUrl->name);

        $url->setCreatedAt(Carbon::parse($stdUrl->created_at));
        $url->setId($stdUrl->id);

        return $url;
    }

    private function stdClassToArray(object $obj): array
    {
        return json_decode(json_encode($obj), true);
    }
}
