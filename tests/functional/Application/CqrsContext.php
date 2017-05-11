<?php

namespace CardBattleGame\Tests\Functional\Application;

use Behat\Behat\Context\Context;
use CardBattleGame\Application\Command\CreateGame;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;

final class CqrsContext implements Context
{
    private $commandBus;
    private $commandRouter;

    public function __construct()
    {
        $this->commandRouter = new CommandRouter();
        $this->commandBus = new CommandBus();

        $this->commandRouter->attachToMessageBus($this->getCommandBus());
    }

    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }

    public function getCommandRouter(): CommandRouter
    {
        return $this->commandRouter;
    }
}
