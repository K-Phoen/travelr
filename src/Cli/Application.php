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
            ->command('directories:list', function (string $webRoot, OutputInterface $output, string $config = null): void {
                $this->service(Command\ListDirectories::class)->run($output, $webRoot, $config);
            })
            ->descriptions('List available directories.');

        $this
            ->command('build [--config=] [webroot]', function (string $webRoot, OutputInterface $output, string $config = null): void {
                $this->service(Command\AlbumsToJson::class)->run($output, $webRoot, $config);
                $this->service(Command\BuildAlbumsMapView::class)->run($output, $webRoot);
                $this->service(Command\BuildGalleries::class)->run($output, $webRoot, $config);
                $this->service(Command\CopyDist::class)->run($output, $webRoot);
            })
            ->descriptions('Builds the map and galleries.')
            ->defaults(['webroot' => '.']);
    }

    private function service(string $service)
    {
        return $this->getContainer()->get($service);
    }
}
