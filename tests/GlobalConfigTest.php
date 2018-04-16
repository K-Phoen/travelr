<?php

declare(strict_types=1);

namespace Tests\Travelr;

use PHPUnit\Framework\TestCase;
use Travelr\GlobalConfig;

class GlobalConfigTest extends TestCase
{
    /**
     * @dataProvider invalidValuesProvider
     */
    public function testItCanNotBeConstructedWithInvalidValues(string $sort, string $mapProvider, string $mapApiKey): void
    {
        $this->expectException(\DomainException::class);

        new GlobalConfig($sort, $mapProvider, $mapApiKey);
    }

    public function invalidValuesProvider()
    {
        return [
            ['invalid-sort', GlobalConfig::MAP_OPENSTREETMAP, ''],
            [GlobalConfig::SORT_BY_NAME, GlobalConfig::MAP_MAPBOX, ''],
            [GlobalConfig::SORT_BY_NAME, 'invalid-map', ''],
        ];
    }
}
