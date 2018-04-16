<?php

declare(strict_types=1);

namespace Tests\Travelr\Config;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Travelr\Config\GlobalParser;
use Travelr\Config\InvalidConfiguration;
use Travelr\GlobalConfig;

class GlobalParserTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $root;

    /** @var GlobalParser */
    private $parser;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('root_dir', null, [
            'full_config_file.yaml' => '
sort_images_by: name
map_provider: mapbox
map_api_key: some-api-key
title: Some title
',
            'invalid_unknown_option.yaml' => '
unknown_option: foo
',
        ]);

        $this->parser = new GlobalParser();
    }

    public function testItReadsAFullConfigFile(): void
    {
        $config = $this->parser->read($this->root->url().'/full_config_file.yaml');

        $this->assertInstanceOf(GlobalConfig::class, $config);
        $this->assertSame('name', $config->sortImagesBy());
    }

    public function testItThrowsAnUnknownOptionIsGiven(): void
    {
        $this->expectException(InvalidConfiguration::class);
        $this->expectExceptionMessage('Unrecognized option "unknown_option"');

        $this->parser->read($this->root->url().'/invalid_unknown_option.yaml');
    }
}
