<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Repositories\UrlRepository;
use Tests\TestCase;

class UrlCheckControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $repo = new UrlRepository();

        $url = new Url('https://laravel.com');
        $repo->save($url);

        $url = new Url('https://google.com');
        $repo->save($url);
    }

    public function testSubmitUrlCheck()
    {
        $this->post(route('check', 1));
        $this->assertDatabaseHas('url_checks', [
            'url_id' => '1'
        ]);

        $this->post(route('check', 2));
        $this->assertDatabaseHas('url_checks', [
            'url_id' => '2'
        ]);
    }

    public function testWrongUrlIdCheck()
    {
        $response = $this->post(route('check', 90));

        $response->assertNotFound();
    }
}
