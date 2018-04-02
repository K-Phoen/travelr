<?php

declare(strict_types=1);

namespace Travelr\Thumbnail;

use Travelr\Image;

class KeepOriginal implements Thumbnailer
{
    public function forImage(Image $image): Image
    {
        return $image;
    }
}
