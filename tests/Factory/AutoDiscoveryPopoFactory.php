<?php

declare(strict_types=1);

namespace Tests\Factory;

use Tests\Popo\AutoDiscoveryPopo;
use Scrumble\Popo\PopoFactory;

class AutoDiscoveryPopoFactory extends PopoFactory
{
    public ?string $popoClass = AutoDiscoveryPopo::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
        ];
    }
}