<?php

namespace Database\Repositories;

use App\Models\UrlCheck;
use Database\Factories\UrlCheckFactory;
use Illuminate\Support\Facades\DB;

class UrlCheckRepository
{
    public function __construct(protected UrlCheckFactory $urlCheckFactory)
    {
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
}
