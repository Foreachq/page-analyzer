<?php

namespace Database\Repositories;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class UrlRepository
{
    public function __construct(protected UrlCheckRepository $urlCheckRepo)
    {
    }

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

    #[ArrayShape([
        'url' => "App\\Models\\Url",
        'checks' => "App\\Models\\UrlCheck"
    ])]
    public function findAllUrlChecks(int $id): ?array
    {
        $stdUrl = DB::table('urls')
            ->where('urls.id', '=', $id)
            ->get()
            ->first();

        if ($stdUrl === null) {
            return null;
        }

        $url = $this->stdClassToUrl($stdUrl);
        $checks = $this->urlCheckRepo->findAllByUrlId($id);

        return ['url' => $url, 'checks' => $checks];
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

        $carbonCreatedAt = Carbon::parse($stdUrl->created_at);
        $url->setCreatedAt($carbonCreatedAt);

        $url->setId($stdUrl->id);

        return $url;
    }

    private function stdClassToArray(object $obj): array
    {
        return json_decode(json_encode($obj), true);
    }
}
