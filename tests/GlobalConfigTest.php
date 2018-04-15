<?php

declare(strict_types=1);

namespace Tests\Travelr;

use PHPUnit\Framework\TestCase;
use Travelr\GlobalConfig;

class GlobalConfigTest extends TestCase
{
    public function testItCanNotBeConstructedWithInvalidValues(): void
    {
        $this->expectException(\DomainException::class);

        new GlobalConfig('invalid-sort');
    }
}
