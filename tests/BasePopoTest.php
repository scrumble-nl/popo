<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\Mock\ArrayPopo;
use Tests\Mock\SamplePopo;
use Tests\Mock\CollectionPopo;
use Tests\Mock\NestedArrayPopo;
use Illuminate\Support\Facades\Route;
use Tests\Popo\ExampleEnum;
use Tests\Popo\ExampleEnumPopo;

/**
 * @internal
 */
class BasePopoTest extends TestCase
{
    /**
     * @var CollectionPopo
     */
    private CollectionPopo $collectionPopo;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $samplePopo = new SamplePopo('test');
        $this->collectionPopo = new CollectionPopo('private', collect([$samplePopo]), 4);

        Route::get('/popo/test', fn () => $this->collectionPopo);
    }

    /** @test */
    public function can_return_from_route()
    {
        $response = $this->get('/popo/test');
        $response->assertStatus(200);

        $this->assertEquals([
            'samples' => [
                [
                    'name' => 'test',
                ],
            ],
            'number' => 4,
            'nullable' => null,
            'array' => [],
        ], $response->json());
    }

    /** @test */
    public function private_attributes_are_hidden()
    {
        $response = $this->get('/popo/test');
        $response->assertStatus(200);

        $this->assertFalse(isset($response->json()['thisIsPrivate']));
    }

    /** @test */
    public function can_to_array_a_popo()
    {
        $arrayablePopo = new ArrayPopo('Bertrand');

        $array = $arrayablePopo->toArray();

        $this->assertEquals(['firstName' => 'Bertrand'], $array);
    }

    /** @test */
    public function can_to_test_array_a_popo()
    {
        $arrayablePopo = new ArrayPopo('Bertrand');

        $array = $arrayablePopo->toTestArray();

        $this->assertEquals(['first_name' => 'Bertrand'], $array);
    }

    /** @test */
    public function to_test_array_does_not_affect_nested_popos(): void
    {
        $nestedArrayPopo = new NestedArrayPopo(
            'bazBarFoo',
            new ArrayPopo('fooBarBaz'),
        );

        $array = $nestedArrayPopo->toTestArray();

        $this->assertSame([
            'foo_bar' => 'bazBarFoo',
            'array_popo' => [
                'firstName' => 'fooBarBaz',
            ],
        ], $array);
    }

    /** @test */
    public function can_to_snake_case_array_a_popo(): void
    {
        $arrayablePopo = new ArrayPopo('Bertrand');

        $array = $arrayablePopo->toSnakeCaseArray();

        $this->assertEquals(['first_name' => 'Bertrand'], $array);
    }

    #[Test]
    public function can_to_snake_case_array_with_collection(): void {
        $arrayablePopo = new CollectionPopo('private', collect([
            new SamplePopo('Alice'),
            new SamplePopo('Bob'),
        ]), 2);

        $array = $arrayablePopo->toSnakeCaseArray();

        $this->assertEquals([
            'samples' => [
                ['name' => 'Alice'],
                ['name' => 'Bob'],
            ],
            'number' => 2,
            'nullable' => null,
            'array' => [],
        ], $array);
    }

    #[Test]
    public function can_to_snake_case_array_with_enum(): void {
        $enumPopo = new ExampleEnumPopo(ExampleEnum::A, ExampleEnum::B);

        $array = $enumPopo->toSnakeCaseArray();

        $this->assertEquals([
            'a' => 'A',
            'b' => 'B',
        ], $array);
    }

    /** @test */
    public function can_to_snake_case_array_a_nested_popo(): void
    {
        $nestedArrayPopo = new NestedArrayPopo(
            'bazBarFoo',
            new ArrayPopo('fooBarBaz'),
        );

        $array = $nestedArrayPopo->toSnakeCaseArray();

        $this->assertSame([
            'foo_bar' => 'bazBarFoo',
            'array_popo' => [
                'first_name' => 'fooBarBaz',
            ],
        ], $array);
    }

    /** @test */
    public function can_to_array_colletion_popo()
    {
        $array = $this->collectionPopo->toArray();

        $this->assertEquals([
            'samples' => [
                [
                    'name' => 'test',
                ],
            ],
            'number' => 4,
            'nullable' => null,
            'array' => [],
        ], $array);
    }
}
