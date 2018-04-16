<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Symfony\Component\Filesystem\Filesystem;
use Travelr\Album;
use Travelr\GlobalConfig;

class GalleryView
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

    public function compile(Album $album, GlobalConfig $config): void
    {
        $html = $this->twig->render('album.html.twig', [
            'title' => $config->title(),
            'album' => $album,
        ]);

        $this->fs->dumpFile($album->path().'/index.html', $html);
    }
}
