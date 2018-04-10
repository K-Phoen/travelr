<?php

declare(strict_types=1);

namespace Travelr\Config;

class InvalidConfiguration extends \RuntimeException
{
    public static function inFile(string $file, string $message, \Throwable $previous = null): self
    {
        return new static(sprintf('Invalid configuration in "%s": %s', $file, $message), 0, $previous);
    }
}
