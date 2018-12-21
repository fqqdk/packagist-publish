<?php

namespace Fqqdk\Packagist;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Exception;
use InvalidArgumentException;

class SelfPublisher
{
    /**
     * We only use this to publish ourselves
     *
     * @param Event $event
     * @throws Exception
     */
    public static function main(Event $event)
    {
        $arguments = $event->getArguments();
        if (!isset($arguments[0])) {
            throw new InvalidArgumentException("Missing packagist user name");
        }
        if (!isset($arguments[1])) {
            throw new InvalidArgumentException("Missing packagist api token");
        }

        /** @var Composer $composer */
        $composer = $event->getComposer();

        Publisher::create(
            $composer->getConfig()->get('vendor-dir'),
            Publisher::PACKAGIST_BASE_URL,
            $arguments[0],
            $arguments[1]
        )->updatePackage($composer->getPackage()->getName());
    }
}
