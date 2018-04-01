<?php

namespace Travelr\Compiler;

class MapView
{
    /** @var \Twig_Environment */
    private $twig;

    /** @var string */
    private $destinationFile;

    public function __construct(\Twig_Environment $twig, string $destinationFile)
    {
        $this->twig = $twig;
        $this->destinationFile = $destinationFile;
    }

    public function compile(): void
    {
        $html = $this->twig->render('index.html.twig');

        file_put_contents($this->destinationFile, $html);
    }
}
