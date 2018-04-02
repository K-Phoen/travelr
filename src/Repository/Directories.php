<?php

declare(strict_types=1);

namespace Travelr\Repository;

use Symfony\Component\Finder\Finder;
use Travelr\Directory;
use Travelr\Config\Parser;

class Directories
{
    private const CONFIG_FILENAME = 'config.yaml';
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /** @var string */
    private $rootDirectory;

    /** @var Parser */
    private $configParser;

    public function __construct(string $rootDirectory, Parser $configParser)
    {
        $this->rootDirectory = $rootDirectory;
        $this->configParser = $configParser;
    }

    /**
     * @return \Generator|Directory[]
     */
    public function findAll(): \Generator
    {
        $finder = new Finder();
        $finder
            ->files()
            ->name(self::CONFIG_FILENAME)
            ->depth(1)
            ->in($this->rootDirectory);

        foreach ($finder as $configFile) {
            $directoryConfig = $this->configParser->read($configFile->getRealPath());

            yield new Directory($configFile->getPath(), $directoryConfig, $this->imagesPaths($configFile->getPath()));
        }
    }

    private function imagesPaths(string $directory): iterable
    {
        $finder = new Finder();
        $finder
            ->files()
            ->filter(function (\SplFileInfo $fileInfo) {
                $extension = strtolower($fileInfo->getExtension());

                return \in_array($extension, self::ALLOWED_EXTENSIONS, true);
            })
            ->depth(0)
            ->in($directory);

        foreach ($finder as $file) {
            yield $file->getRealPath();
        }
    }
}
