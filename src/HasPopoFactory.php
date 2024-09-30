<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use Scrumble\Popo\Exception\InvalidTypeException;

trait HasPopoFactory
{
    /**
     * @throws InvalidTypeException
     * @return null|string
     */
    abstract public static function popoFactory(): ?string;

    /**
     * @throws InvalidTypeException
     * @return PopoFactory
     */
    public static function factory(): PopoFactory
    {
        $factoryClass = self::getFactoryPath();

        if (!(new ($factoryClass) instanceof PopoFactory)) {
            throw new InvalidTypeException("{$factoryClass} is not a valid popo factory.");
        }

        // @var PopoFactory $factory
        return new $factoryClass();
    }

    /**
     * @throws InvalidTypeException
     * @return string
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
