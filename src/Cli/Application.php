<?php

namespace Travelr\Cli;

use Silly\Application as Silly;

class Application extends Silly
{
    public function __construct()
    {
        parent::__construct('Traveler');
    }
}
