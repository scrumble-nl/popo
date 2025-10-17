<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use JsonSerializable;
use Spatie\LaravelData\Data;
use Scrumble\Popo\Traits\ToSnakeCaseArray;
use Illuminate\Contracts\Support\Arrayable;
use Scrumble\Popo\Contracts\SnakeCaseArrayable;
use Spatie\LaravelData\Support\Creation\CreationContextFactory;

/**
 * @implements Arrayable<int|string, mixed>
 */
class BasePopo extends Data implements JsonSerializable, Arrayable, SnakeCaseArrayable
{
    use ToSnakeCaseArray;

    /**
     * @return array<array-key, mixed>
     * @deprecated Use toSnakeCaseArray instead
     */
    public function toTestArray(): array
    {
        $properties = [];

        foreach ($this->toArray() as $property => $value) {
            $properties[snake_case($property)] = $value;
        }

        return $properties;
    }

    /**
     * @return CreationContextFactory<static>
     */
    public static function builder(): CreationContextFactory
    {
        return self::factory();
    }
}
