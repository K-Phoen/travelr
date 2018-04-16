<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Symfony\Component\Filesystem\Filesystem;
use Travelr\GlobalConfig;

class AlbumsMapView
{
    /** @var \Twig_Environment */
    private $twig;

    /** @var Filesystem */
    private $fs;

    public function __construct(\Twig_Environment $twig, Filesystem $fs = null)
    {
        $this->twig = $twig;
        $this->fs = $fs ?: new Filesystem();
    }

    public function compile(string $webRoot, GlobalConfig $config): void
    {
        $html = $this->twig->render('index.html.twig', [
            'map_tile_layer_url' => $config->mapTileLayerUrl(),
            'map_api_key' => $config->mapApiKey(),
        ]);

        $this->fs->dumpFile($webRoot.'/index.html', $html);
    }
}
