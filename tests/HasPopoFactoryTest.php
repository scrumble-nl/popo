<?php

declare(strict_types=1);

namespace Tests;

use Tests\Popo\ExamplePopo;
use Scrumble\Popo\PopoFactory;
use Orchestra\Testbench\TestCase;

/**
 * @internal
 */
class HasPopoFactoryTest extends TestCase
{
    /** @test */
    public function can_get_factory(): void
    {
        $factory = ExamplePopo::factory();

        $this->assertInstanceOf(PopoFactory::class, $factory);
    }

    /** @test */
    public function can_get_factory_path(): void
    {
        $factoryPath = ExamplePopo::getFactoryPath();

        $this->assertEquals('Tests\\Factory\\ExamplePopoFactory', $factoryPath);
    }
}
