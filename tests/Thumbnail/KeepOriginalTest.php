<?php

declare(strict_types=1);

namespace Tests\Travelr\Thumbnail;

use PHPUnit\Framework\TestCase;
use Travelr\Image;
use Travelr\Thumbnail\Intervention;
use Travelr\Thumbnail\KeepOriginal;

class KeepOriginalTest extends TestCase
{
    /** @var Intervention */
    private $thumbnailer;

    public function setUp()
    {
        $this->thumbnailer = new KeepOriginal();
    }

    public function testItReturnsTheImageUntouched()
    {
        $image = Image::fromPath('/dir/img.png');

        $thumb = $this->thumbnailer->forImage($image);

        $this->assertSame($image, $thumb);
    }
}
