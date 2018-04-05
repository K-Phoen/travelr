<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Symfony\Component\Filesystem\Filesystem;

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

    public function compile(string $webRoot): void
    {
        $html = $this->twig->render('index.html.twig');

        $this->fs->dumpFile($webRoot.'/index.html', $html);
    }
}
