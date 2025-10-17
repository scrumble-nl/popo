<?php

declare(strict_types=1);

namespace Tests\Popo;

use Scrumble\Popo\BasePopo;
use Scrumble\Popo\HasPopoFactory;

class AutoDiscoveryPopo extends BasePopo
{
    use HasPopoFactory;

    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }
}