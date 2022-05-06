<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Url;
use Database\Repositories\UrlRepository;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $urlRepo = $this->app->make(UrlRepository::class);

        $url = new Url('https://laravel.com');
        $urlRepo->save($url);

        $url = new Url('https://google.com');
        $urlRepo->save($url);
    }

    public function testExistingIndex()
    {
        $response = $this->get(route('urls.index', 1));

        $response->assertOk();
    }

    public function invalidIndexProvider(): array
    {
        return [
            [999],
            [1.2],
            ['abc'],
            [-1]
        ];
    }

    /**
     * @dataProvider invalidIndexProvider
     */
    public function testInvalidIndex($index)
    {
        $responseNumber = $this->get(route('urls.index', $index));
        $responseNumber->assertNotFound();
    }

    public function testShow()
    {
        $response = $this->get(route('urls'));

        $response->assertOk();
    }

    public function invalidPageProvider(): array
    {
        return [
            [['page' => 2]],
            [['page' => 2.2]],
            [['page' => -1]],
            [['page' => 'asdf']]
        ];
    }

    /**
     * @dataProvider invalidPageProvider
     */
    public function testInvalidShowPage($page)
    {
        $response = $this->get(route('urls', $page));
        $response->assertNotFound();
    }

    public function testValidShowPage()
    {
        $response = $this->get(route('urls', ['page' => 1]));
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
