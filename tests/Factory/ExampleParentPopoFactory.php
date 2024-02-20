<?php

declare(strict_types=1);

namespace Tests\Factory;

use Tests\Popo\ExamplePopo;
use Scrumble\Popo\PopoFactory;
use Tests\Popo\ExampleParentPopo;

class ExampleParentPopoFactory extends PopoFactory
{
    /**
     * @var null|string
     */
    public ?string $popoClass = ExampleParentPopo::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'popo' => ExamplePopo::factory(),
        ];
    }
}
