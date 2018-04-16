<?php

declare(strict_types=1);

namespace Tests\Travelr\Repository;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Travelr\Config\DirectoryParser;
use Travelr\Coordinates;
use Travelr\DirectoryConfig;
use Travelr\GlobalConfig;
use Travelr\Repository\Directories;

class DirectoriesTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $root;

    /** @var DirectoryParser */
    private $configParser;

    /** @var Directories */
    private $repo;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('root_dir', null, [
            'data' => [
                'not_an_album' => ['maybe-a-picture.jpg' => 'content'],
                'first_album' => [
                    'config.yaml' => 'config',
                    '002.jpeg' => 'content',
                    '001.jpeg' => 'content',
                ],
                'second_album' => [
                    'config.yaml' => 'config',
                    'some-text-file.txt' => 'content',
                    '201805101439_001.jpg' => 'content',
                    '201805101439_002.PNG' => 'content',
                ],
            ],
        ]);

        $this->configParser = $this->createMock(DirectoryParser::class);

        $this->repo = new Directories($this->configParser);
    }

    public function testItIgnoresDirectoriesWithNoConfigFile(): void
    {
        $globalConfig = GlobalConfig::default();
        $dummyDirectoryConfig = new DirectoryConfig('title', 'description', new Coordinates(0, 0), 'cover.jpg');

        $this->configParser
            ->expects($this->exactly(2))
            ->method('read')
            ->willReturn($dummyDirectoryConfig);

        $directories = iterator_to_array($this->repo->findAll($this->root->url(), $globalConfig));

        $this->assertCount(2, $directories);
        $this->assertSame($this->root->url().'/data/first_album', $directories[0]->path());
        $this->assertSame($dummyDirectoryConfig, $directories[0]->config());
        $this->assertSame($this->root->url().'/data/second_album', $directories[1]->path());
        $this->assertSame($dummyDirectoryConfig, $directories[1]->config());
    }

    public function testItReturnsTheRightImages(): void
    {
        $globalConfig = GlobalConfig::default();
        $dummyDirectoryConfig = new DirectoryConfig('title', 'description', new Coordinates(0, 0), 'cover.jpg');

        $this->configParser
            ->expects($this->exactly(2))
            ->method('read')
            ->willReturn($dummyDirectoryConfig);

        $directories = iterator_to_array($this->repo->findAll($this->root->url(), $globalConfig));

        $this->assertCount(2, $directories);
        $this->assertSame([
            $this->root->url().'/data/first_album/001.jpeg',
            $this->root->url().'/data/first_album/002.jpeg',
        ], iterator_to_array($directories[0]->images()));

        $this->assertSame([
            $this->root->url().'/data/second_album/201805101439_001.jpg',
            $this->root->url().'/data/second_album/201805101439_002.PNG',
        ], iterator_to_array($directories[1]->images()));
    }

    public function testTheResultsCanBeOrderedByName(): void
    {
        $globalConfig = new GlobalConfig(GlobalConfig::SORT_BY_NAME, GlobalConfig::MAP_OPENSTREETMAP, '');
        $dummyDirectoryConfig = new DirectoryConfig('title', 'description', new Coordinates(0, 0), 'cover.jpg');

        $this->configParser->method('read')->willReturn($dummyDirectoryConfig);

        $directories = iterator_to_array($this->repo->findAll($this->root->url(), $globalConfig));

        $this->assertSame([
            $this->root->url().'/data/second_album/201805101439_001.jpg',
            $this->root->url().'/data/second_album/201805101439_002.PNG',
        ], iterator_to_array($directories[1]->images()));
    }

    public function testTheResultsCanBeOrderedByDate(): void
    {
        touch($this->root->url().'/data/second_album/201805101439_002.PNG', strtotime('yesterday'));

        $globalConfig = new GlobalConfig(GlobalConfig::SORT_BY_MODIFICATION_DATE, GlobalConfig::MAP_OPENSTREETMAP, '');
        $dummyDirectoryConfig = new DirectoryConfig('title', 'description', new Coordinates(0, 0), 'cover.jpg');

        $this->configParser->method('read')->willReturn($dummyDirectoryConfig);

        $directories = iterator_to_array($this->repo->findAll($this->root->url(), $globalConfig));

        $this->assertSame([
            $this->root->url().'/data/second_album/201805101439_002.PNG',
            $this->root->url().'/data/second_album/201805101439_001.jpg',
        ], iterator_to_array($directories[1]->images()));
    }
}
