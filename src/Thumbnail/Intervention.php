<?php

namespace Travelr\Thumbnail;

use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Image;

class Intervention implements Thumbnailer
{
    /** @var ImageManager */
    private $manager;

    /** @var Filesystem */
    private $fs;

    public function __construct(Filesystem $fs = null)
    {
        $this->manager = new ImageManager(['driver' => 'gd']);
        $this->fs = $fs ?: new Filesystem();
    }

    public function forImage(Image $image): Image
    {
        $thumb = Image::thumbFrom($image, 'travelr/thumb_');

        $img = $this->manager->make($image->path());
        $img->widen(400);

        $this->fs->mkdir($thumb->directory());

        $img->save($thumb->path());

        return $thumb;
    }
}
