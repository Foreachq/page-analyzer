<?php

namespace Tests\Feature;

use App\Models\Url;
use Database\Repositories\UrlRepository;
use Illuminate\Support\Facades\Http;
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
        Http::fake([
            'laravel.com' => Http::response([], 404, []),
            'google.com' => Http::response([], 200, [])
        ]);

        $this->post(route('check', 1));
        $this->assertDatabaseHas('url_checks', [
            'url_id' => '1',
            'status_code' => '404'
        ]);

        $this->post(route('check', 2));
        $this->assertDatabaseHas('url_checks', [
            'url_id' => '2',
            'status_code' => '200'
        ]);
    }

    public function testWrongUrlIdCheck()
    {
        $response = $this->post(route('check', 90));

        $response->assertNotFound();
    }
}
