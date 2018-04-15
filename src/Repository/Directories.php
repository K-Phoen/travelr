<?php

declare(strict_types=1);

namespace Travelr\Repository;

use Symfony\Component\Finder\Finder;
use Travelr\Directory;
use Travelr\Config\DirectoryParser;
use Travelr\GlobalConfig;

class Directories
{
    private const CONFIG_FILENAME = 'config.yaml';

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /** @var DirectoryParser */
    private $configParser;

    public function __construct(DirectoryParser $configParser)
    {
        $this->configParser = $configParser;
    }

    /**
     * @return iterable|Directory[]
     */
    public function findAll(string $webRoot, GlobalConfig $config): iterable
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
                $this->imagesPaths($configFile->getPath(), $config)
            );
        }
    }

    private function imagesPaths(string $directory, GlobalConfig $config): iterable
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

        if ($config->sortImagesBy() === GlobalConfig::SORT_BY_NAME) {
            $finder->sortByName();
        } else if ($config->sortImagesBy() === GlobalConfig::SORT_BY_MODIFICATION_DATE) {
            $finder->sortByModifiedTime();
        }

        foreach ($finder as $file) {
            yield $file->getPathname();
        }
    }
}
