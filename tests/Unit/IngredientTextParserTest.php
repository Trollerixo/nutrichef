<?php

namespace Tests\Unit;

use App\Support\IngredientTextParser;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IngredientTextParserTest extends TestCase
{
    #[Test]
    public function it_parses_ingredients_from_common_separators(): void
    {
        $parsed = IngredientTextParser::parse("Tomate, cebolla y ajo\npimienta / aceite.");

        $this->assertSame(
            ['Tomate', 'cebolla', 'ajo', 'pimienta', 'aceite'],
            $parsed,
        );
    }

    #[Test]
    public function it_returns_an_empty_array_for_blank_input(): void
    {
        $this->assertSame([], IngredientTextParser::parse('   '));
        $this->assertSame([], IngredientTextParser::parse(null));
    }
}
