<?php

namespace Tests\Travelr\Cli;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Cli\Application;
use Travelr\Cli\Container;

class ApplicationTest extends TestCase
{
    /** @var Container */
    private $container;

    /** @var Container */
    private $application;

    public function setUp()
    {
        $this->container = new Container();

        $this->application = new Application($this->container);
        $this->application->setAutoExit(false);
    }

    public function testItCanBeConstructed(): void
    {
        $this->assertInstanceOf(Application::class, $this->application);
    }

    public function testItCanRun(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $this->application->run(null, $output);

        $this->assertTrue($this->application->has('directories:list'));
        $this->assertTrue($this->application->has('build'));
    }
}
