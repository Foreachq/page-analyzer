<?php

namespace Database\Repositories;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrlRepository
{
    public function findAll(): array
    {
        $urls = DB::table('urls')->get();

        return $urls->map(function ($url) {
            return $this->stdClassToUrl($url);
        })->filter()->toArray();
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

    public function remove(Url $url): void
    {
        DB::table('urls')
            ->where('name', $url->getName())
            ->delete();
    }

    public function update(Url $oldUrl, Url $newUrl): void
    {
        DB::table('urls')
            ->where('name', $oldUrl->getName())
            ->update([
                'name' => $newUrl->getName(),
                'created_at' => $newUrl->getCreatedAt()
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
