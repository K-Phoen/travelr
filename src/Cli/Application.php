<?php

declare(strict_types=1);

namespace Travelr\Cli;

use Silly\Application as Silly;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimple\Psr11\Container as Psr11Container;

class Application extends Silly
{
    public function __construct(\Pimple\Container $container)
    {
        parent::__construct('Traveler');

        $this->useContainer(new Psr11Container($container));
    }

    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->registerCommands();

        return parent::run($input, $output);
    }

    private function registerCommands(): void
    {
        $this
            ->command('directories:list', [Command\ListDirectories::class, 'run'])
            ->descriptions('List available directories.');

        $this
            ->command('build:albums:json [webroot]', function (string $webRoot, OutputInterface $output) {
                $this->service(Command\AlbumsToJson::class)->run($webRoot, $output);
            });

        $this
            ->command('build:albums:map [webroot]', function (string $webRoot, OutputInterface $output) {
                $this->service(Command\BuildAlbumsMapView::class)->run($webRoot, $output);
            });

        $this->command('build:albums:galleries', [Command\BuildGalleries::class, 'run']);

        $this
            ->command('build [webroot]', function (string $webRoot, OutputInterface $output) {
                $this->service(Command\AlbumsToJson::class)->run($webRoot, $output);
                $this->service(Command\BuildAlbumsMapView::class)->run($webRoot, $output);
                $this->service(Command\BuildGalleries::class)->run($webRoot, $output);
            })
            ->descriptions('Builds the map and galleries.');
    }

    private function service(string $service)
    {
        return $this->getContainer()->get($service);
    }
}
