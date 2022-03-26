<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Repositories\UrlRepository;
use Tests\TestCase;

class UrlControllerTest extends TestCase
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

    public function testExistingIndex()
    {
        $response = $this->get(route('urls.index', 1));

        $response->assertOk();
    }

    public function testInvalidIndex()
    {
        $responseNumber = $this->get(route('urls.index', 999));
        $responseNumber->assertNotFound();

        $responseSymbol = $this->get(route('urls.index', 'abc'));
        $responseSymbol->assertNotFound();
    }

    public function testShow()
    {
        $response = $this->get(route('urls'));

        $response->assertOk();
    }

    public function testSubmitUrl()
    {
        $this->post(route('urls.create'), [
            'url' => [
                'name' => 'https://github.com'
            ]
        ]);

        $this->assertDatabaseHas('urls', [
            'name' => 'https://github.com'
        ]);
    }

    public function testSubmitExistingUrl()
    {
        $this->post(route('urls.create'), [
            'url' => [
                // laravel.com was added in setUp()
                'name' => 'https://laravel.com'
            ]
        ]);

        // 2 sites from setUp()
        $this->assertDatabaseCount('urls', 2);
    }

    public function testSubmitInvalidUrl()
    {
        $this->post(route('urls.create'), [
            'url' => [
                'name' => 'hts://github.com'
            ]
        ]);

        $this->assertDatabaseMissing('urls', [
            'name' =>  'hts://github.com'
        ]);
    }
}
