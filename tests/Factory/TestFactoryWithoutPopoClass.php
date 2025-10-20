<?php

declare(strict_types=1);

namespace Tests\Factory;

use Scrumble\Popo\PopoFactory;

/**
 * Test factory without $popoClass property set
 */
class TestFactoryWithoutPopoClass extends PopoFactory
{
    public ?string $popoClass = null;

    public function definition(): array
    {
        return [];
    }
}