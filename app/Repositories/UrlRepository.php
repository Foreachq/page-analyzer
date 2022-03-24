<?php

namespace App\Repositories;

use App\Models\Url;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UrlRepository
{
    public function findAll(): array
    {
        $builders = DB::table('urls')->get()->toArray();

        return array_map(function (Builder $builder) {
            return $this->builderToUrl($builder);
        }, $builders);
    }

    public function findByName(string $name): Url
    {
        $builder = DB::table('urls')->where('name', $name);

        return $this->builderToUrl($builder);
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

    private function builderToUrl(Builder $builder): Url
    {
        $id = $builder->value('id');
        $name = $builder->value('name');
        $created_at = $builder->value('created_at');

        $url = new Url($name);
        $url->setCreatedAt($created_at);
        $url->setId($id);

        return $url;
    }
}
