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

        $this['twig'] = new \Twig_Environment($loader);
        $this['twig']->addGlobal('MAP_ACCESS_TOKEN', $_ENV['MAP_ACCESS_TOKEN']);
    }

    private function compilers(): void
    {
        $this[Compiler\AlbumsList::class] = function ($c) {
            return new Compiler\AlbumsList(
                $c[Albums::class],
                $c[Thumbnail\Intervention::class],
                $c['web_dir'],
                $c['web_dir'].'/albums.json'
            );
        };

        $this[Compiler\MapView::class] = function ($c) {
            return new Compiler\MapView($c['twig'], $c['web_dir'].'/index.html');
        };
    }

    private function commands(): void
    {
        $this[Command\ListAlbums::class] = function ($c) {
            return new Command\ListAlbums($c[Albums::class]);
        };

        $this[Command\CompileAlbums::class] = function ($c) {
            return new Command\CompileAlbums($c[Compiler\AlbumsList::class]);
        };

        $this[Command\CompileHtml::class] = function ($c) {
            return new Command\CompileHtml($c[Compiler\MapView::class]);
        };
    }
}
