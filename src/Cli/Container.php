<?php

namespace Travelr\Cli;

use Pimple\Container as Pimple;
use Travelr\Repository\Albums;
use Travelr\Metadata\AlbumReader;
use Travelr\Thumbnail;
use Travelr\Compiler;

class Container extends Pimple
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->configure();
    }

    private function configure(): void
    {
        $this->directories();
        $this->templating();
        $this->compilers();
        $this->commands();

        $this[AlbumReader::class] = function () {
            return new AlbumReader();
        };

        $this[Albums::class] = function ($c) {
            return new Albums($c['data_dir'],  $c[AlbumReader::class]);
        };

        $this[Thumbnail\KeepOriginal::class] = function () {
            return new Thumbnail\KeepOriginal();
        };

        $this[Thumbnail\Intervention::class] = function () {
            return new Thumbnail\Intervention();
        };
    }

    private function directories(): void
    {
        $this['data_dir'] = realpath(__DIR__.'/../../web/data/');
        $this['web_dir'] = realpath(__DIR__.'/../../web/');
        $this['views_dir'] = realpath(__DIR__.'/../../views/');
    }

    private function templating(): void
    {
        $loader = new \Twig_Loader_Filesystem($this['views_dir']);

        $this['twig'] = new \Twig_Environment($loader, [
            'strict_variables' => true,
        ]);
        $this['twig']->addGlobal('MAP_ACCESS_TOKEN', $_ENV['MAP_ACCESS_TOKEN']);
    }

    private function compilers(): void
    {
        $this[Compiler\AlbumsToJson::class] = function ($c) {
            return new Compiler\AlbumsToJson(
                $c[Albums::class],
                $c[Thumbnail\Intervention::class],
                $c['web_dir']
            );
        };

        $this[Compiler\AlbumsMapView::class] = function ($c) {
            return new Compiler\AlbumsMapView($c['twig']);
        };

        $this[Compiler\GalleryView::class] = function ($c) {
            return new Compiler\GalleryView($c['twig']);
        };
    }

    private function commands(): void
    {
        $this[Command\ListAlbums::class] = function ($c) {
            return new Command\ListAlbums($c[Albums::class]);
        };

        $this[Command\BuildAlbumsMapView::class] = function ($c) {
            return new Command\BuildAlbumsMapView($c[Compiler\AlbumsMapView::class]);
        };

        $this[Command\AlbumsToJson::class] = function ($c) {
            return new Command\AlbumsToJson($c[Compiler\AlbumsToJson::class]);
        };

        $this[Command\BuildGalleries::class] = function ($c) {
            return new Command\BuildGalleries(
                $c[Albums::class],
                $c[Compiler\GalleryView::class]
            );
        };
    }
}
