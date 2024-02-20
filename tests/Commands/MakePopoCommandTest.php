<?php

declare(strict_types=1);

namespace Tests\Commands;

use Tests\TestCase;

/**
 * @internal
 */
class MakePopoCommandTest extends TestCase
{
    /** @test */
    public function can_make_popo(): void
    {
        $result = $this->artisan('make:popo');

        $result->expectsQuestion('What should the popo be named?', 'TestPopo');
        $result->execute();
        $result->assertOk();
    }
}
