<?php

declare(strict_types=1);

namespace Scrumble\Popo\Exception;

use Exception;
use Throwable;

class ClassNotDefinedException extends Exception
{
    /**
     * @param null|string    $message
     * @param int            $code
     * @param null|Throwable $previous
     */
    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'The $popoClass property could not be found in a factory', $code, $previous);
    }
}
