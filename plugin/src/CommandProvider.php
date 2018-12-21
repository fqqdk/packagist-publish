<?php

namespace Fqqdk\Packagist;

use Composer\Command\BaseCommand;
use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;

class CommandProvider implements ComposerCommandProvider
{
    /**
     * @return BaseCommand[]
     * @throws \Exception
     */
    public function getCommands()
    {
        return array(new Command('publish'));
    }
}
