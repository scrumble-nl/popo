<?php

declare(strict_types=1);

namespace Tests;

use Scrumble\Popo\BasePopo;
use Tests\Popo\ExamplePopo;
use Scrumble\Popo\PopoFactory;
use Tests\Popo\ExampleParentPopo;
use Illuminate\Support\Collection;
use Tests\Factory\ExamplePopoFactory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @internal
 */
class PopoFactoryTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function can_get_factory(): void
    {
        $popo = ExamplePopo::factory();

        $this->assertInstanceOf(PopoFactory::class, $popo);
    }

    /** @test */
    public function can_override_factory(): void
    {
        $popo = ExamplePopo::factory();

        $this->assertInstanceOf(PopoFactory::class, $popo);
        $this->assertEquals(ExamplePopo::popoFactory(), ExamplePopoFactory::class);
    }

    /** @test */
    public function can_create_popo_with_factory(): void
    {
        $popo = ExamplePopo::factory()->create();

        $this->assertNotNull($popo->name);
        $this->assertNotNull($popo->content);
    }

    /** @test */
    public function can_create_popo_with_factory_and_override_properties(): void
    {
        $name = $this->faker->name;
        $content = $this->faker->text;
        $popo = ExamplePopo::factory()->create([
            'name' => $name,
            'content' => $content,
        ]);

        $this->assertEquals($name, $popo->name);
        $this->assertEquals($content, $popo->content);
    }

    /** @test */
    public function can_get_raw_factory_data(): void
    {
        $data = ExamplePopo::factory()->raw();

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('content', $data);
    }

    /** @test */
    public function can_get_raw_factory_and_override_properties(): void
    {
        $name = $this->faker->name;
        $content = $this->faker->text;
        $data = ExamplePopo::factory()->raw([
            'name' => $name,
            'content' => $content,
        ]);

        $this->assertEquals($name, $data['name']);
        $this->assertEquals($content, $data['content']);
    }

    /** @test */
    public function can_create_multiple_factories_with_count(): void
    {
        $popos = ExamplePopo::factory()->count(3)->create();

        $this->assertCount(3, $popos);
        $this->assertInstanceOf(Collection::class, $popos);
    }

    /** @test */
    public function can_raw_multiple_factories_with_count(): void
    {
        $popos = ExamplePopo::factory()->count(3)->raw();

        $this->assertCount(3, $popos);
        $this->assertIsArray($popos);
    }

    /** @test */
    public function can_create_multiple_factories_with_sequence(): void
    {
        $name1 = $this->faker->name;
        $name2 = $this->faker->name;
        $content1 = $this->faker->text;
        $content2 = $this->faker->text;

        $popos = ExamplePopo::factory()->count(2)->sequence([
            'name' => $name1,
            'content' => $content1,
        ], [
            'name' => $name2,
            'content' => $content2,
        ])->create();

        $this->assertCount(2, $popos);
        $this->assertInstanceOf(Collection::class, $popos);
        $this->assertEquals($name1, $popos->toArray()[0]['name']);
        $this->assertEquals($content1, $popos->toArray()[0]['content']);
        $this->assertEquals($name2, $popos->toArray()[1]['name']);
        $this->assertEquals($content2, $popos->toArray()[1]['content']);
    }

    /** @test */
    public function can_create_multiple_factories_with_sequence_with_missing_keys(): void
    {
        $name = $this->faker->name;
        $content = $this->faker->text;

        $popos = ExamplePopo::factory()->count(2)->sequence([
            'name' => $name,
        ], [
            'content' => $content,
        ])->create();

        $this->assertCount(2, $popos);
        $this->assertInstanceOf(Collection::class, $popos);
        $this->assertEquals($name, $popos->toArray()[0]['name']);
        $this->assertEquals($content, $popos->toArray()[1]['content']);
    }

    /** @test */
    public function can_raw_multiple_factories_with_sequence(): void
    {
        $name1 = $this->faker->name;
        $name2 = $this->faker->name;
        $content1 = $this->faker->text;
        $content2 = $this->faker->text;

        $popos = ExamplePopo::factory()->count(2)->sequence([
            'name' => $name1,
            'content' => $content1,
        ], [
            'name' => $name2,
            'content' => $content2,
        ])->raw();

        $this->assertCount(2, $popos);
        $this->assertIsArray($popos);
        $this->assertEquals($name1, $popos[0]['name']);
        $this->assertEquals($content1, $popos[0]['content']);
        $this->assertEquals($name2, $popos[1]['name']);
        $this->assertEquals($content2, $popos[1]['content']);
    }

    /** @test */
    public function can_override_sequence_with_attributes(): void
    {
        $sequenceName = $this->faker->name;
        $attributeName = $this->faker->name;

        $popos = ExamplePopo::factory()->count(2)->sequence([
            'name' => $sequenceName,
        ], [
            'name' => $sequenceName,
        ])->create(['name' => $attributeName]);

        $this->assertCount(2, $popos);
        $this->assertInstanceOf(Collection::class, $popos);
        $this->assertEquals($attributeName, $popos->toArray()[0]['name']);
        $this->assertEquals($attributeName, $popos->toArray()[0]['name']);
    }

    /** @test */
    public function can_create_with_child_factory(): void
    {
        $popo = ExampleParentPopo::factory()->create();

        $this->assertInstanceOf(BasePopo::class, $popo->popo);
    }

    /** @test */
    public function can_override_state_of_factory(): void
    {
        $oldName = $this->faker->unique()->name;
        $name = $this->faker->unique()->name;

        $popo = ExamplePopo::factory()->state(function () use ($name) {
            return ['name' => $name];
        })->create(['name' => $oldName]);

        $this->assertEquals($name, $popo->name);
        $this->assertNotEquals($oldName, $popo->name);
    }
}
