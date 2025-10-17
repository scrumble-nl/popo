<?php

namespace Tests\Popo;

use Scrumble\Popo\BasePopo;
use Scrumble\Popo\HasPopoFactory;

class PopoWithInvalidFactory extends BasePopo
{
    use HasPopoFactory;

    public static function popoFactory(): ?string
    {
        return self::class;
    }
}