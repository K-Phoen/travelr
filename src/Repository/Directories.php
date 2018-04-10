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

    /** @var Parser */
    private $configParser;

    public function __construct(Parser $configParser)
    {
        $this->configParser = $configParser;
    }

    /**
     * @return \Generator|Directory[]
     */
    public function findAll(string $webRoot): \Generator
    {
        $finder = new Finder();
        $finder
            ->files()
            ->name(self::CONFIG_FILENAME)
            ->depth(1)
            ->sortByName()
            ->in($webRoot.'/data');

        foreach ($finder as $configFile) {
            $directoryConfig = $this->configParser->read($configFile->getPathname());

            yield new Directory(
                \dirname($configFile->getPathname()),
                $directoryConfig,
                $this->imagesPaths($configFile->getPath())
            );
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
            yield $file->getPathname();
        }
    }
}
