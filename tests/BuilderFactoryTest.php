<?php

declare(strict_types=1);

namespace Tests;

use Tests\Popo\ExamplePopo;
use Scrumble\Popo\PopoFactory;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Support\Creation\CreationContextFactory;

/**
 * @internal
 */
class BuilderFactoryTest extends TestCase
{
    /** @test */
    public function builder_returns_laravel_data_factory(): void
    {
        $builder = ExamplePopo::builder();

        $this->assertInstanceOf(CreationContextFactory::class, $builder);
        $this->assertSame(ExamplePopo::class, $builder->dataClass);
    }

    /** @test */
    public function builder_can_create_instance_from_array(): void
    {
        $name = 'Bob';
        $content = 'World';

        $example =ExamplePopo::builder()
            ->from([
                'name' => $name,
                'content' => $content,
            ]);

        $this->assertInstanceOf(ExamplePopo::class, $example);
        $this->assertSame($name, $example->name);
        $this->assertSame($content, $example->content);
    }

    /** @test */
    public function popo_factory_and_builder_do_not_conflict(): void
    {
        $popoFactory = ExamplePopo::factory();
        $builder = ExamplePopo::builder();

        $this->assertInstanceOf(PopoFactory::class, $popoFactory);
        $this->assertNotInstanceOf(CreationContextFactory::class, $popoFactory);
        $this->assertInstanceOf(CreationContextFactory::class, $builder);
        $this->assertNotInstanceOf(PopoFactory::class, $builder);
    }

    /** @test */
    public function builder_can_collect_multiple_items(): void
    {
        $items = [
            ['name' => 'A', 'content' => 'a'],
            ['name' => 'B', 'content' => 'b'],
        ];

        $result = ExamplePopo::builder()->collect($items, Collection::class);

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(ExamplePopo::class, $result->first());
    }
}
