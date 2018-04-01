<?php

namespace Travelr\Thumbnail;

use Travelr\Image;

interface Thumbnailer
{
    public function forImage(Image $image): Image;
}
