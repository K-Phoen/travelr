<?php

declare(strict_types=1);

namespace Travelr\Cli;

use Silly\Application as Silly;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimple\Psr11\Container as Psr11Container;
use Travelr\Config\AlbumConfiguration;
use Travelr\Config\GlobalConfiguration;

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
            ->command('directories:list [webroot]', function (string $webRoot, OutputInterface $output): void {
                $this->service(Command\ListDirectories::class)->run($output, $webRoot);
            })
            ->descriptions('List available directories.');

        $this
            ->command('config:dump-reference [type]', function (string $type, OutputInterface $output): void {
                switch ($type) {
                    case 'album':
                        $config = new AlbumConfiguration();
                        break;
                    case 'global':
                        $config = new GlobalConfiguration();
                        break;
                    default:
                        throw new \LogicException(sprintf('Unknown config type "%s". Known values: album, global.', $type));
                }

                $dumper = new YamlReferenceDumper();

                $output->writeln($dumper->dump($config, 'travelr'));
            })
            ->descriptions('Dumps the default configuration for an album or the global configuration.')
            ->defaults(['type' => 'global']);

        $this
            ->command('build [--config=] [webroot]', function (string $webRoot, OutputInterface $output, string $config = null): void {
                if (!is_dir($webRoot)) {
                    throw new \RuntimeException("Directory '$webRoot' does not exist.");
                }

                $this->service(Command\AlbumsToJson::class)->run($output, $webRoot, $config);
                $this->service(Command\BuildAlbumsMapView::class)->run($output, $webRoot, $config);
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
