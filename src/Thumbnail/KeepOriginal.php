<?php

namespace Travelr\Thumbnail;

use Travelr\Image;

class KeepOriginal implements Thumbnailer
{
    public function forImage(Image $image): Image
    {
        return $image;
    }
}
