<?php

namespace JustBetter\AkeneoClient\Tests\Actions;

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
    public function it_throws_exceptions_when_method_is_not_callable(): void
    {
        /** @var Akeneo $akeneo */
        $akeneo = app(Akeneo::class);

        $this->expectException(AkeneoException::class);

        // @phpstan-ignore-next-line
        $akeneo->invalidMethod();
    }
}
