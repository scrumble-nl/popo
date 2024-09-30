<?php

declare(strict_types=1);

namespace Tests\Mock;

use Scrumble\Popo\BasePopo;

class NestedArrayPopo extends BasePopo
{
    /**
     * @var string
     */
    public string $fooBar;

    /**
     * @var ArrayPopo
     */
    public ArrayPopo $arrayPopo;

    /**
     * @param string    $fooBar
     * @param ArrayPopo $arrayPopo
     */
    public function __construct(string $fooBar, ArrayPopo $arrayPopo)
    {
        $this->fooBar = $fooBar;
        $this->arrayPopo = $arrayPopo;
    }
}
