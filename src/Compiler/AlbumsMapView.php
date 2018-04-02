<?php

declare(strict_types=1);

namespace Travelr\Compiler;

class AlbumsMapView
{
    /** @var \Twig_Environment */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function compile(string $destinationFile): void
    {
        $html = $this->twig->render('index.html.twig');

        file_put_contents($destinationFile, $html);
    }
}
