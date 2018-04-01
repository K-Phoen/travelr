<?php

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Album;
use Travelr\Repository\Albums;

class ListAlbums
{
    private $albumsRepo;

    public function __construct(Albums $albumsRepo)
    {
        $this->albumsRepo = $albumsRepo;
    }

    public function run(OutputInterface $output): void
    {
        $table = new Table($output);
        $table->setHeaders(['Title', 'Directory', 'Cover', 'Latitude', 'Longitude']);

        /** @var Album $album */
        foreach ($this->albumsRepo->findAll() as $album) {
            $table->addRow([
                $album->title(),
                $album->directory(),
                $album->cover()->filename(),
                $album->latitude(),
                $album->longitude(),
            ]);
        }

        $table->render();
    }
}
