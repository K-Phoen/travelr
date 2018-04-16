<?php

declare(strict_types=1);

namespace Travelr\Cli;

use Geocoder;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use Pimple\Container as Pimple;
use Travelr\Repository\Albums;
use Travelr\Config\DirectoryParser as DirectoryConfigParser;
use Travelr\Config\GlobalParser as GlobalConfigParser;
use Travelr\Repository\Directories;
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
        $this['views_dir'] = dirname(__DIR__, 2).'/views/';
        $this['dist_dir'] = dirname(__DIR__, 2).'/dist/';

        $this->templating();
        $this->geocoder();
        $this->compilers();
        $this->commands();

        $this[DirectoryConfigParser::class] = function ($c) {
            return new DirectoryConfigParser($c[Geocoder\Geocoder::class]);
        };

        $this[GlobalConfigParser::class] = function () {
            return new GlobalConfigParser();
        };

        $this[Albums::class] = function ($c) {
            return new Albums($c[Directories::class], $c[Thumbnail\Intervention::class]);
        };

        $this[Directories::class] = function ($c) {
            return new Directories($c[DirectoryConfigParser::class]);
        };

        $this[Thumbnail\KeepOriginal::class] = function () {
            return new Thumbnail\KeepOriginal();
        };

        $this[Thumbnail\Intervention::class] = function () {
            return new Thumbnail\Intervention();
        };
    }

    private function geocoder(): void
    {
        $this[Geocoder\Geocoder::class] = function () {
            $httpClient = new GuzzleClient();
            $provider = Geocoder\Provider\Nominatim\Nominatim::withOpenStreetMapServer($httpClient);

            return new Geocoder\StatefulGeocoder($provider);
        };
    }

    private function templating(): void
    {
        $this['twig'] = function () {
            $loader = new \Twig_Loader_Filesystem($this['views_dir']);

            return new \Twig_Environment($loader, [
                'strict_variables' => true,
            ]);
        };
    }

    private function compilers(): void
    {
        $this[Compiler\AlbumsToJson::class] = function ($c) {
            return new Compiler\AlbumsToJson($c[Albums::class]);
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
        $this[Command\ListDirectories::class] = function ($c) {
            return new Command\ListDirectories($c[Directories::class]);
        };

        $this[Command\CopyDist::class] = function ($c) {
            return new Command\CopyDist($c['dist_dir']);
        };

        $this[Command\BuildAlbumsMapView::class] = function ($c) {
            return new Command\BuildAlbumsMapView($c[Compiler\AlbumsMapView::class], $c[GlobalConfigParser::class]);
        };

        $this[Command\AlbumsToJson::class] = function ($c) {
            return new Command\AlbumsToJson($c[Compiler\AlbumsToJson::class], $c[GlobalConfigParser::class]);
        };

        $this[Command\BuildGalleries::class] = function ($c) {
            return new Command\BuildGalleries($c[Albums::class], $c[Compiler\GalleryView::class], $c[GlobalConfigParser::class]);
        };
    }
}
