<?php

declare(strict_types=1);

namespace Tests\Factory;

use Tests\Popo\ExamplePopo;
use Scrumble\Popo\PopoFactory;

class ExamplePopoFactory extends PopoFactory
{
    /**
     * @var null|string
     */
    public ?string $popoClass = ExamplePopo::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
        ];
    }
}
