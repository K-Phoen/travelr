<?php

namespace Tests\Travelr\Thumbnail;

use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Image;
use Travelr\Thumbnail\Intervention;

class InterventionTest extends TestCase
{
    /** @var Filesystem */
    private $fs;

    /** @var ImageManager */
    private $manager;

    /** @var Intervention */
    private $thumbnailer;

    public function setUp()
    {
        $this->manager = $this->createMock(ImageManager::class);
        $this->fs = $this->createMock(Filesystem::class);

        $this->thumbnailer = new Intervention($this->manager, $this->fs);
    }

    public function testItDoesNothingIfTheThumbnailAlreadyExists()
    {
        $image = Image::fromPath('/dir/img.png');

        $this->fs
            ->method('exists')
            ->with('/dir/travelr/thumb_img.png')
            ->willReturn(true);

        $this->manager->expects($this->never())->method('make');
        $this->fs->expects($this->never())->method('mkdir');

        $thumb = $this->thumbnailer->forImage($image);

        $this->assertSame('/dir/travelr/thumb_img.png', $thumb->thumbnailPath());
        $this->assertSame('/dir/img.png', $thumb->path());
    }

    public function testItCreateItIfTheThumbnailDoesNotExist()
    {
        $image = Image::fromPath('/dir/img.png');

        $this->fs
            ->method('exists')
            ->with('/dir/travelr/thumb_img.png')
            ->willReturn(false);

        $interventionImage = $this->getMockBuilder(\Intervention\Image\Image::class)
            ->setMethods(['widen', 'save'])
            ->getMock();
        $interventionImage->method('widen')->willReturnSelf();
        $interventionImage->expects($this->once())
            ->method('save')
            ->with('/dir/travelr/thumb_img.png');

        $this->manager->expects($this->once())
            ->method('make')
            ->with($image->path())
            ->willReturn($interventionImage);

        $this->fs->expects($this->once())
            ->method('mkdir')
            ->with('/dir/travelr');

        $this->thumbnailer->forImage($image);
    }
}
