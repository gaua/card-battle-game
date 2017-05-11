<?php

namespace CardBattleGame\Tests\Functional\Application;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use CardBattleGame\Application\Command\PlayCard;
use CardBattleGame\Application\Command\PlayCardHandler;
use CardBattleGame\Domain\Event\CardPlayed;
use CardBattleGame\Tests\Functional\Domain\EventSourcedContextTrait;

final class CardsPlayingContext implements Context
{
    use CqrsContextTrait;
    use EventSourcedContextTrait;

    /**
     * @When player on the move plays the card of type :arg1 with value :arg2 and cost of :arg3 MP
     */
    public function playerOnTheMovePlaysTheCardOfTypeWithValueAndCostOfMp($type, $hp, $mp)
    {
        $gameRepository = $this->eventSourcedContext->getGameRepository();

        $this->cqrsContext->getCommandRouter()
            ->route(PlayCard::class)
            ->to(new PlayCardHandler($gameRepository));

        $gameId = $this->eventSourcedContext->getAggregateId();

        $dealCard = new PlayCard($gameId, $type, $hp, $mp);

        $this->cqrsContext->getCommandBus()->dispatch($dealCard);
    }

    /**
     * @Then card of type :arg1 with value :arg2 and cost of :arg3 MP was played
     */
    public function cardOfTypeWithValueAndCostOfMpWasPlayed($type, $hp, $mp)
    {
        $gameId = $this->eventSourcedContext->getAggregateId();

        $wasPlayed = $this->eventSourcedContext->hasAggregateRecordedEvent(
            CardPlayed::occur($gameId, [
                'card' => [
                    'type' => $type,
                    'value' => $hp,
                    'move-points' => $mp,
                ]
            ])
        );

        Assertion::true($wasPlayed);
    }
}
