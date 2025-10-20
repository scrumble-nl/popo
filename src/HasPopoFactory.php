<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use Scrumble\Popo\Exception\InvalidTypeException;
use Spatie\LaravelData\Support\Creation\CreationContext;

trait HasPopoFactory
{
    /**
     * @throws InvalidTypeException
     * @return null|string
     */
    public static function popoFactory(): ?string {
        return null;
    }

    /**
     * @throws InvalidTypeException
     * @return PopoFactory
     */
    public static function factory(?CreationContext $creationContext = null): PopoFactory
    {
        $factoryClass = self::getFactoryPath();

        if (!(new ($factoryClass) instanceof PopoFactory)) {
            throw new InvalidTypeException("{$factoryClass} is not a valid popo factory.");
        }

        return new $factoryClass(static::class, $creationContext);
    }

    /**
     * @throws InvalidTypeException
     * @return class-string<PopoFactory>
     */
    public static function getFactoryPath(): string
    {
        if (self::popoFactory()) {
            return self::popoFactory();
        }

        $popoClass = static::class;

        return str_replace('\\Popo\\', '\\Factory\\', $popoClass . 'Factory');
    }
}
