<?php

declare(strict_types=1);

namespace Tests\Travelr\Config;

use Geocoder\Geocoder;
use Geocoder\Model\Address;
use Geocoder\Model\AddressCollection;
use Geocoder\Model\Coordinates;
use Geocoder\Query\GeocodeQuery;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Travelr\Config\InvalidConfiguration;
use Travelr\Config\DirectoryParser;
use Travelr\DirectoryConfig;

class DirectoryParserTest extends TestCase
{
    /** @var Geocoder */
    private $geocoder;

    /** @var vfsStreamDirectory */
    private $root;

    /** @var DirectoryParser */
    private $parser;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('root_dir', null, [
            'full_config_file.yaml' => '
title: Barcelone – 2018
description: blablabla
cover: 0001.jpg

latitude: 41.3947688
longitude: 2.0787284
',
            'textual_location.yaml' => '
title: Valence – 2018
description: blablabla
cover: 0001.jpg

location: Valencia, Spain
',
            'invalid_missing_cover.yaml' => '
title: Barcelone – 2018
description: blablabla

latitude: 41.3947688
longitude: 2.0787284
',
            'invalid_location.yaml' => '
title: Barcelone – 2018
description: blablabla
cover: 0001.jpg

latitude: 41.3947688
',
        ]);

        $this->geocoder = $this->createMock(Geocoder::class);

        $this->parser = new DirectoryParser($this->geocoder);
    }

    public function testItReadsAFullConfigFile(): void
    {
        $this->geocoder->expects($this->never())->method('geocodeQuery');

        $config = $this->parser->read($this->root->url().'/full_config_file.yaml');

        $this->assertInstanceOf(DirectoryConfig::class, $config);
        $this->assertSame('Barcelone – 2018', $config->title());
        $this->assertSame('blablabla', $config->description());
        $this->assertSame('0001.jpg', $config->cover());
        $this->assertSame(41.3947688, $config->coordinates()->latitude());
        $this->assertSame(2.0787284, $config->coordinates()->longitude());
    }

    public function testItGeocodesTextualLocations(): void
    {
        $location = $this->createMock(Address::class);
        $location->method('getCoordinates')->willReturn(new Coordinates(42.2, 24.4));

        $geocodingResults = new AddressCollection([$location]);
        $this->geocoder->expects($this->once())
            ->method('geocodeQuery')
            ->with($this->callback(function (GeocodeQuery $query) {
                $this->assertSame('Valencia, Spain', $query->getText());

                return true;
            }))
            ->willReturn($geocodingResults);

        $config = $this->parser->read($this->root->url().'/textual_location.yaml');

        $this->assertInstanceOf(DirectoryConfig::class, $config);
        $this->assertSame('Valence – 2018', $config->title());
        $this->assertSame('blablabla', $config->description());
        $this->assertSame('0001.jpg', $config->cover());
        $this->assertSame(42.2, $config->coordinates()->latitude());
        $this->assertSame(24.4, $config->coordinates()->longitude());
    }

    public function testItThrowsAnErrorIfTheCoverIsNotSpecified(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessage('The child node "cover" at path "album" must be configured');

        $this->parser->read($this->root->url().'/invalid_missing_cover.yaml');
    }

    public function testItThrowsAnErrorIfTheLocationIsNotSpecified(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessage('either specify the "location" option or the latitude/longitude ones');

        $this->parser->read($this->root->url().'/invalid_location.yaml');
    }
}
