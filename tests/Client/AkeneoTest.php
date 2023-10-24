<?php

namespace JustBetter\AkeneoClient\Tests\Actions;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JustBetter\AkeneoClient\Client\Akeneo;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;
use JustBetter\AkeneoClient\Tests\TestCase;

class AkeneoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Akeneo::fake();
    }

    /** @test */
    public function it_can_get_products(): void
    {
        $product = [
            'identifier' => '1000',
            'enabled' => true,
            'family' => 'hydras',
            'categories' => [],
            'groups' => [],
            'parent' => null,
            'values' => [
                'name' => [
                    [
                        'locale' => 'nl_NL',
                        'scope' => 'ecommerce',
                        'data' => 'Ziggy',
                    ],
                ],
            ],
        ];

        Http::fake([
            'akeneo/api/rest/v1/products/1000' => Http::response($product),
        ]);

        /** @var Akeneo $akeneo */
        $akeneo = app(Akeneo::class);

        $response = $akeneo->getProductApi()->get('1000');

        $this->assertEquals($product, $response);
    }

    /** @test */
    public function it_can_upsert_async_products(): void
    {
        $product = [
            'enabled' => true,
            'family' => 'tshirt',
            'categories' => ['summer_collection'],
            'groups' => [],
            'parent' => null,
            'values' => [
                'name' => [
                    [
                        'data' => 'top',
                        'locale' => 'en_US',
                        'scope' => null,
                    ],
                    [
                        'data' => 'Débardeur',
                        'locale' => 'fr_FR',
                        'scope' => null,
                    ],
                ],
                'price' => [
                    [
                        'data' => [
                            [
                                'amount' => '15.5',
                                'currency' => 'EUR',
                            ],
                            [
                                'amount' => '15',
                                'currency' => 'USD',
                            ],
                        ],
                        'locale' => null,
                        'scope' => null,
                    ],
                ],
            ],
        ];

        Http::fake([
            'akeneo/api/rest/v1/products/top' => Http::response($product),
        ]);

        /** @var Akeneo $akeneo */
        $akeneo = app(Akeneo::class);

        $akeneo->getProductApi()->upsertAsync('top')->then(function (Response $response) use ($product): void {
            $this->assertEquals($product, $response->json());
        })->wait();
    }

    /** @test */
    public function it_throws_exceptions_when_method_is_not_callable(): void
    {
        /** @var Akeneo $akeneo */
        $akeneo = app(Akeneo::class);

        $this->expectException(AkeneoException::class);

        // @phpstan-ignore-next-line
        $akeneo->invalidMethod();
    }
}
