<?php

namespace CardBattleGame\Tests\Functional\Application;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use CardBattleGame\Application\Command\DealCard;
use CardBattleGame\Application\Command\DealCardHandler;
use CardBattleGame\Domain\Card;
use CardBattleGame\Tests\Functional\Domain\EventSourcedContextTrait;

final class CardsDealingContext implements Context
{
    use CqrsContextTrait;
    use EventSourcedContextTrait;

    /**
     * @Given player on the move was dealt with card of type :arg1 with value :arg2 and cost of :arg3 MP
     */
    public function playerOnTheMoveWasDealtWithCardOfTypeWithValueAndCostOfMp($type, $hp, $mp)
    {
        $gameRepository = $this->eventSourcedContext->getGameRepository();

        $this->cqrsContext->getCommandRouter()
            ->route(DealCard::class)
            ->to(new DealCardHandler($gameRepository));

        $gameId = $this->eventSourcedContext->getAggregateId();

        $dealCard = new DealCard($gameId, $type, $hp, $mp);

        $this->cqrsContext->getCommandBus()->dispatch($dealCard);
    }

    /**
     * @When card of type :arg1 with value :arg2 HP and cost of :arg3 MP is dealt for player on turn
     */
    public function cardOfTypeWithValueHpAndCostOfMpIsDealtForPlayerOnTurn($type, $hp, $mp)
    {
        $gameRepository = $this->eventSourcedContext->getGameRepository();

        $this->cqrsContext->getCommandRouter()
            ->route(DealCard::class)
            ->to(new DealCardHandler($gameRepository));

        $gameId = $this->eventSourcedContext->getAggregateId();

        $dealCard = new DealCard($gameId, $type, $hp, $mp);

        $this->cqrsContext->getCommandBus()->dispatch($dealCard);
    }

    /**
     * @Then player on turn has on hand card of type :arg1 with value :arg2 HP and cost of :arg3 MP
     */
    public function playerOnTurnHasOnHandCardOfTypeWithValueHpAndCostOfMp($type, $hp, $mp)
    {
        $gameRepository = $this->eventSourcedContext->getGameRepository();
        $gameId = $this->eventSourcedContext->getAggregateId();

        $game = $gameRepository->get($gameId);
        $playerOnTurn = $game->getPlayerOnTurn();

        /** @var Card $cardOnHand */
        $cardOnHand = $playerOnTurn->getHand()[0];

        Assertion::eq($type, $cardOnHand->getType());
        Assertion::eq($hp, $cardOnHand->getValue());
        Assertion::eq($mp, $cardOnHand->getMpCost());
    }
}
