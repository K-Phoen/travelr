<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Travelr\Album;

class GalleryView
{
    /** @var \Twig_Environment */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function compile(Album $album): void
    {
        $html = $this->twig->render('album.html.twig', [
            'album' => $album,
        ]);

        file_put_contents($album->path().'/index.html', $html);
    }
}
