<?php

declare(strict_types=1);

namespace Tests\Popo;

use Scrumble\Popo\BasePopo;
use Scrumble\Popo\HasPopoFactory;
use Tests\Factory\ExamplePopoFactory;

class ExamplePopo extends BasePopo
{
    use HasPopoFactory;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $content;

    /**
     * @param string $name
     * @param string $content
     */
    public function __construct(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public static function popoFactory(): string
    {
        return ExamplePopoFactory::class;
    }
}
