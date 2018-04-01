<?php

namespace Travelr\Compiler;

use Travelr\Album;

class AlbumView
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

        file_put_contents($album->directory().'/index.html', $html);
    }
}
