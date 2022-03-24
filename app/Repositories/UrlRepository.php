<?php

namespace App\Repositories;

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
        })->toArray();
    }

    public function findByName(string $name): ?Url
    {
        $url = DB::table('urls')
            ->where('name', $name)
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

    private function stdClassToUrl($stdUrl): Url
    {
        $id = $stdUrl->id;
        $name = $stdUrl->name;

        $created_at = $stdUrl->created_at;
        $carbonTime = Carbon::parse($created_at);

        $url = new Url($name);
        $url->setCreatedAt($carbonTime);
        $url->setId($id);

        return $url;
    }
}
