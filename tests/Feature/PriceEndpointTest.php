<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use PHPUnit\Framework\Attributes\DataProvider;

class PriceEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Product::factory()->count(1)->create([
            'price' => 900.00,
            'title' => 'Test Product'
        ]);
    }

    public static function currencyProvider(): array
    {
        return [
            ['rub', '₽', '900.00'],
            ['usd', '$', '10.00'],
            ['eur', '€', '9.00'],
        ];
    }

    #[DataProvider('currencyProvider')]
    public function test_prices_are_converted_and_formatted_correctly(string $currency, string $symbol, string $expectedFormattedAmount): void
    {
        $response = $this->getJson("/api/v1/prices?currency={$currency}");
        $response->assertOk();

        $price = $response->json(['data'])[0]['price'];

        $this->assertStringContainsString($symbol, $price, "Ожидаемая валюта {$symbol} для цены {$currency}");
        $this->assertStringContainsString($expectedFormattedAmount, $price, "Ожидаемая форматированная цена {$expectedFormattedAmount} для валюты {$currency}");
    }

    public function test_it_returns_422_for_invalid_currency(): void
    {
        $response = $this->getJson('/api/v1/prices?currency=btc');

        $response->assertStatus(422);
    }
}