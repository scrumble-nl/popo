<?php

declare(strict_types=1);

namespace Tests\Factory;

use Scrumble\Popo\PopoFactory;
use Tests\Popo\ExamplePopo;
use Illuminate\Foundation\Testing\WithFaker;

class TestFactoryWithValidPopoClass extends PopoFactory
{
    use WithFaker;

    public ?string $popoClass = ExamplePopo::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
        ];
    }
}