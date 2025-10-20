<?php

declare(strict_types=1);

namespace Tests\Factory;

use Scrumble\Popo\PopoFactory;

class TestFactoryWithInvalidClass extends PopoFactory
{
    public ?string $popoClass = 'stdClass';

    public function definition(): array
    {
        return [];
    }
}