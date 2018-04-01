<?php

namespace Travelr\Thumbnail;

use Intervention\Image\ImageManager;
use Travelr\Image;

class Intervention implements Thumbnailer
{
    /** @var ImageManager */
    private $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(['driver' => 'gd']);
    }

    public function forImage(Image $image): Image
    {
        $thumb = Image::thumbFrom($image, 'thumb_');

        $img = $this->manager->make($image->path());
        $img->widen(400);

        $img->save($thumb->path());

        return $thumb;
    }
}
