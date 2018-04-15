<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Directory;
use Travelr\GlobalConfig;
use Travelr\Repository\Directories;

class ListDirectories
{
    /** @var Directories */
    private $directoriesRepo;

    public function __construct(Directories $directoriesRepo)
    {
        $this->directoriesRepo = $directoriesRepo;
    }

    public function run(OutputInterface $output, string $webRoot): void
    {
        $config = GlobalConfig::default();

        $table = new Table($output);
        $table->setHeaders(['Title', 'Directory', 'Cover', 'Latitude', 'Longitude']);

        /** @var Directory $dir */
        foreach ($this->directoriesRepo->findAll($webRoot, $config) as $dir) {
            $table->addRow([
                $dir->config()->title(),
                $dir->path(),
                $dir->config()->cover(),
                $dir->config()->coordinates()->latitude(),
                $dir->config()->coordinates()->longitude(),
            ]);
        }

        $table->render();
    }
}
